<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>فاتورة رقم {{ $order->reference_number }}</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            direction: rtl;
            text-align: right;
            color: #1f2937;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
            font-size: 12px;
            line-height: 1.5;
        }
        .header-bar {
            background-color: #0f172a;
            color: #ffffff;
            padding: 30px 40px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .brand-title {
            font-size: 22px;
            font-weight: bold;
            color: #38bdf8;
            margin: 0;
        }
        .brand-subtitle {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 4px;
        }
        .invoice-title {
            font-size: 20px;
            font-weight: bold;
            text-align: left;
            color: #ffffff;
        }
        .invoice-number {
            font-size: 12px;
            color: #38bdf8;
            text-align: left;
            margin-top: 4px;
        }
        .container {
            padding: 30px 40px;
        }
        .info-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .info-box {
            width: 48%;
            vertical-align: top;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px 15px;
        }
        .box-title {
            font-size: 11px;
            font-weight: bold;
            color: #64748b;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 5px;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        .info-line {
            margin-bottom: 4px;
            font-size: 11px;
        }
        .info-label {
            color: #475569;
            font-weight: bold;
        }
        .info-value {
            color: #0f172a;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .data-table th {
            background-color: #1e293b;
            color: #ffffff;
            font-size: 11px;
            font-weight: bold;
            padding: 10px;
            text-align: right;
            border: 1px solid #1e293b;
        }
        .data-table td {
            padding: 10px;
            border: 1px solid #e2e8f0;
            font-size: 11px;
            color: #334155;
        }
        .data-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .text-left { text-align: left; }
        .text-center { text-align: center; }
        .totals-table {
            width: 40%;
            margin-left: 0;
            margin-right: auto;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 8px 12px;
            font-size: 12px;
        }
        .total-row {
            background-color: #0f172a;
            color: #ffffff;
            font-weight: bold;
            font-size: 14px;
        }
        .total-row td {
            color: #ffffff;
            border-top: 2px solid #0f172a;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 15px 40px;
            background-color: #f1f5f9;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 10px;
            color: #64748b;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 9px;
            font-weight: bold;
            border-radius: 4px;
            background-color: #e2e8f0;
            color: #334155;
        }
    </style>
</head>
<body>

    <div class="header-bar">
        <table class="header-table">
            <tr>
                <td style="width: 50%;">
                    <div class="brand-title">نظام حجز الصيدلية المركزي</div>
                    <div class="brand-subtitle">جامعة الزقازيق - كلية الصيدلة</div>
                </td>
                <td style="width: 50%;">
                    <div class="invoice-title">فاتورة حجز رقمية</div>
                    <div class="invoice-number">REF: {{ $order->reference_number }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="container">
        
        <table class="info-grid">
            <tr>
                <td class="info-box">
                    <div class="box-title">بيانات المقدم / العميل</div>
                    <div class="info-line"><span class="info-label">الاسم الكامل:</span> <span class="info-value">{{ $order->customer_name }}</span></div>
                    <div class="info-line"><span class="info-label">الرقم القومي:</span> <span class="info-value">{{ $order->customer_national_id }}</span></div>
                    <div class="info-line"><span class="info-label">رقم الهاتف:</span> <span class="info-value">{{ $order->customer_phone }}</span></div>
                    <div class="info-line"><span class="info-label">البريد الإلكتروني:</span> <span class="info-value">{{ $order->customer_email }}</span></div>
                </td>
                <td style="width: 4%;"></td>
                <td class="info-box">
                    <div class="box-title">تفاصيل أمر الشراء</div>
                    <div class="info-line"><span class="info-label">تاريخ الإصدار:</span> <span class="info-value">{{ $order->created_at->format('Y-m-d h:i A') }}</span></div>
                    <div class="info-line"><span class="info-label">حالة الفاتورة:</span> <span class="badge">{{ strtoupper($order->status) }}</span></div>
                    <div class="info-line"><span class="info-label">مهلة السداد:</span> <span class="info-value">{{ \Carbon\Carbon::parse($order->expires_at)->format('Y-m-d h:i A') }}</span></div>
                </td>
            </tr>
        </table>

        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 35%;">البند / الجهاز</th>
                    <th style="width: 25%;">تفاصيل الفترة / الموعد</th>
                    <th style="width: 15%;" class="text-center">الكمية/المدة</th>
                    <th style="width: 20%;" class="text-left">الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $index => $booking)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $booking->item->name ?? 'عنصر غير محدد' }}</strong>
                        <div style="font-size: 9px; color: #64748b;">كود المرجع: {{ $booking->reference_number }}</div>
                    </td>
                    <td>
                        @if($booking->booking_date)
                            <div>من: {{ $booking->booking_date }} ({{ $booking->start_time }})</div>
                            <div>إلى: {{ $booking->end_date ?? $booking->booking_date }} ({{ $booking->end_time }})</div>
                        @else
                            <span class="badge">مستلزمات - شراء مباشر</span>
                        @endif
                    </td>
                    <td class="text-center font-bold">
                        {{ (float)$booking->requested_amount }} {{ $booking->pricingRule->unit_type ?? '' }}
                    </td>
                    <td class="text-left font-bold" style="color: #0f172a;">
                        {{ number_format($booking->total_price, 2) }} ج.م
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals-table">
            <tr>
                <td class="info-label">المبلغ الإجمالي:</td>
                <td class="text-left font-bold">{{ number_format($order->total_amount, 2) }} ج.م</td>
            </tr>
            <tr>
                <td class="info-label">الضريبة والرسوم:</td>
                <td class="text-left">0.00 ج.م</td>
            </tr>
            <tr class="total-row">
                <td>الإجمالي المطلوب:</td>
                <td class="text-left">{{ number_format($order->total_amount, 2) }} ج.م</td>
            </tr>
        </table>

    </div>

    <div class="footer">
        هذه وثيقة رسمية ممررة إلكترونياً من نظام حجز المعامل والأجهزة - كلية الصيدلة جامعة الزقازيق.
    </div>

</body>
</html>