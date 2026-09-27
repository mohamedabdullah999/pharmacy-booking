<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['item', 'pricingRule'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('reference_number', 'like', "%{$request->search}%")
                  ->orWhere('customer_national_id', 'like', "%{$request->search}%");
        }

        $bookings = $query->paginate(15);
        return view('admin.bookings.index', compact('bookings'));
    }

    public function confirmPayment($id)
    {
        DB::beginTransaction();

        try {
            $booking = Booking::where('id', $id)->lockForUpdate()->firstOrFail();

            if ($booking->status !== 'pending') {
                DB::rollBack();
                return back()->withErrors(['error' => "هذا الحجز مسجل مسبقاً بحالة: {$booking->status}."]);
            }

            if (now()->greaterThan($booking->expires_at)) {
                $booking->update(['status' => 'expired']);
                DB::commit();
                return back()->withErrors(['error' => 'انتهت مهلة الدفع (48 ساعة) وتم إلغاء الحجز تلقائياً بناءً على سياسة النظام.']);
            }

            $booking->update([
                'status' => 'paid'
            ]);

            DB::commit();

            return back()->with('success', 'تم تأكيد الدفع بنجاح واعتماد الحجز.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'حدث خطأ أثناء معالجة الدفع: ' . $e->getMessage()]);
        }
    }
}