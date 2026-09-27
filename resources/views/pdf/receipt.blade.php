<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>إيصال حجز - {{ $booking->reference_number }}</title>
    <style>
        body {
            font-family: 'xbriyaz', sans-serif;
            font-size: 14px;
            color: #333;
            direction: rtl;
        }
        .header {
            width: 100%;
            border-bottom: 3px solid #800020;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header table { width: 100%; }
        .header td { vertical-align: middle; }
        .title { color: #132A4F; font-size: 24px; font-weight: bold; margin: 0; }
        .subtitle { color: #555; font-size: 14px; margin-top: 5px; }
        .status-badge { padding: 8px 15px; border-radius: 5px; font-weight: bold; font-size: 16px; text-align: center; }
        .paid { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .pending { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .expired { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .section-title { background-color: #132A4F; color: white; padding: 8px 12px; font-weight: bold; margin-top: 20px; margin-bottom: 10px; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-table th { text-align: right; background-color: #f8f9fa; border: 1px solid #ddd; padding: 10px; width: 30%; color: #132A4F; }
        .info-table td { border: 1px solid #ddd; padding: 10px; width: 70%; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #777; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <table>
            <tr>
                <td style="width: 20%; text-align: right;">
                    @if(file_exists($logo_path))
                        <img src="{{ $logo_path }}" style="width: 80px; height: 80px;">
                    @endif
                </td>
                <td style="width: 60%; text-align: center;">
                    <h1 class="title">جامعة الزقازيق</h1>
                    <p class="subtitle">كلية الصيدلة - نظام الحجوزات والمبيعات</p>
                </td>
                <td style="width: 20%; text-align: left;">
                    @if($booking->status === 'paid')
                        <div class="status-badge paid">مدفوع (مؤكد)</div>
                    @elseif($booking->status === 'pending')
                        <div class="status-badge pending">في انتظار الدفع</div>
                    @else
                        <div class="status-badge expired">ملغي / منتهي</div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div style="text-align: center; margin-bottom: 20px;">
        <h2 style="color: #800020; margin: 0;">إيصال رقم: <span dir="ltr">{{ $booking->reference_number }}</span></h2>
        <p style="margin-top: 5px; font-size: 14px;">تاريخ الطلب: <span dir="ltr">{{ $booking->created_at->format('Y-m-d H:i') }}</span></p>
    </div>

    <div class="section-title">بيانات الطالب / العميل</div>
    <table class="info-table">
        <tr>
            <th>الاسم الرباعي</th>
            <td>{{ $booking->customer_name }}</td>
        </tr>
        <tr>
            <th>الرقم القومي</th>
            <td dir="ltr" style="text-align: right;">{{ $booking->customer_national_id }}</td>
        </tr>
        <tr>
            <th>رقم الهاتف</th>
            <td dir="ltr" style="text-align: right;">{{ $booking->customer_phone }}</td>
        </tr>
    </table>

    <div class="section-title">تفاصيل الحجز / الخدمة</div>
    <table class="info-table">
        <tr>
            <th>اسم العنصر</th>
            <td>{{ $booking->item->name }}</td>
        </tr>
        <tr>
            <th>الكمية / المدة المطلوبة</th>
            <td dir="ltr" style="text-align: right;">{{ (float)$booking->requested_amount }} {{ $booking->pricingRule->unit_type }}</td>
        </tr>
        <tr>
            <th>إجمالي المبلغ المطلوب</th>
            <td style="font-weight: bold; color: #132A4F; font-size: 18px;">
                {{ number_format($booking->total_price, 2) }} ج.م
            </td>
        </tr>
    </table>

    <div class="footer">
        هذا الإيصال مُصدر إلكترونياً من نظام الحجوزات بكلية الصيدلة - جامعة الزقازيق.<br>
        في حالة الاستفسار يرجى مراجعة إدارة المعامل بالكلية.<br>
        تاريخ الطباعة: <span dir="ltr">{{ now()->format('Y-m-d H:i') }}</span>
    </div>

</body>
</html>