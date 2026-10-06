<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items.item')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('reference_number', 'like', "%{$request->search}%")
                  ->orWhere('customer_national_id', 'like', "%{$request->search}%")
                  ->orWhere('customer_name', 'like', "%{$request->search}%");
        }

        $orders = $query->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['items.item', 'items.pricingRule'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function confirmPayment($id)
    {
        DB::beginTransaction();

        try {
            $order = Order::where('id', $id)->lockForUpdate()->firstOrFail();

            if ($order->status !== 'pending') {
                DB::rollBack();
                return back()->withErrors(['error' => "الطلب مسجل مسبقاً بحالة: {$order->status}."]);
            }

            if (now()->greaterThan($order->expires_at)) {
                $order->update(['status' => 'expired']);
                DB::commit();
                return back()->withErrors(['error' => 'انتهت مهلة الدفع (48 ساعة) وتم إلغاء الطلب تلقائياً.']);
            }

            $order->update(['status' => 'paid']);

            DB::commit();

            return back()->with('success', 'تم تأكيد الدفع بنجاح واعتماد الفاتورة.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'حدث خطأ أثناء معالجة الدفع: ' . $e->getMessage()]);
        }
    }

public function downloadPdf($id)
    {
        $order = Order::with(['items.item', 'items.pricingRule'])->findOrFail($id);

        $fileName = "invoices/Invoice-{$order->reference_number}.pdf";

        if (\Storage::disk('public')->exists($fileName)) {
            $pdfPath = \Storage::disk('public')->path($fileName);
            return response()->file($pdfPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Invoice-' . $order->reference_number . '.pdf"'
            ]);
        }

        $config = [
            'mode'                 => 'utf-8',
            'format'               => 'A4',
            'orientation'          => 'P',
            'margin_left'          => 10,
            'margin_right'         => 10,
            'margin_top'           => 10,
            'margin_bottom'        => 10,
            'tempDir'              => storage_path('app/temp'),
            'autoScriptToLang'     => true,
            'autoLangToFont'       => true,
            'useActiveForms'       => false,
        ];

        $pdf = PDF::loadView('pdf.invoice', compact('order'), [], $config);
        $pdfOutput = $pdf->output();

        \Storage::disk('public')->put($fileName, $pdfOutput);

        return response($pdfOutput, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Invoice-' . $order->reference_number . '.pdf"',
            'Cache-Control' => 'public, max-age=86400'
        ]);
    }
}