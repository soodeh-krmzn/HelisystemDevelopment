<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use Carbon\Carbon;
use Hekmatinasser\Verta\Facades\Verta as FacadesVerta;
use Hekmatinasser\Verta\Verta;

class ReportController extends Controller
{
    public function paymentReport(Request $request)
    {
        // $request->validate([
        //     'payment_type' => 'required|in:sms,account',
        //     'from_date' => 'nullable|date',
        //     'to_date' => 'nullable|date',
        // ]);

        $paymentType = $request->input('payment_type', 'sms');
        $fromDate = $request->input('from');
        $toDate = $request->input('to');

        $query = Payment::where('status', 'OK')
            ->where('type', $paymentType);

        // Function to convert Persian date to Gregorian date using Verta
        

        if ($fromDate) {
           $from=  Verta::parse($fromDate)->datetime();
           $query->where('created_at', '>=', Carbon::parse($from));
            
        }

        if ($toDate) {
            $to=  Verta::parse($toDate)->datetime();
            $query->where('created_at', '<=', Carbon::parse($to)->endOfDay());
        }

        $payments = $query->paginate(10);
        $sumPrice = $payments->sum('price');

        $priceCounts = $payments->groupBy('price')->map(function ($group) {
            return $group->count();
        });

        $chartData = [
            'labels' => $priceCounts->keys()->map(fn($price) => number_format($price, 2))->toArray(),
            'series' => $priceCounts->values()->toArray(),
        ];

        return view('report.payment', compact('payments', 'sumPrice', 'chartData', 'request'));
    }
}
