<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Option;
use App\Models\Account;
use App\Models\Package;
use App\Models\Payment;
use App\Services\Zarinpal;
use App\Models\PackagePrice;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PaymentController extends Controller
{

    public function index(Request $request)
    {
        $payments = Payment::filter()->latest();
        $sumPrice = $payments->sum('price');
        $sumPrice = $sumPrice;
        $payments = $payments->paginate(20);
        $accounts = Account::latest()->get();
        return view('payment.index', compact('payments', 'accounts', 'request', 'sumPrice'));
    }

    public function create($username, $type)
    {
        $packagePrice = new PackagePrice;
        $user = User::where('username', $username)->firstOrFail();
        $package_id = $user->account->package_id ?? Option::get_option('default_package');
        $package_name = Package::findOrFail($package_id)->name;
        if ($type == "account") {
            $list = PackagePrice::where('package_id', $package_id)->get();
        } else if ($type == "sms") {
            $package = Package::where('type', 'sms')->first();
            $list = PackagePrice::where('package_id', $package?->id)->get();
        }
        $packages = Package::where("type", $type)->get();
        return view('payment.create', compact('packages', 'list', 'package_name', 'packagePrice', 'user', 'type'));
    }

    public function start(Request $request)
    {
        $request->validate([
            'price' => 'required',
            'package_price_id' => 'required|exists:package_prices,id',
            'user_id' => 'required|exists:users,id',
            'type' => 'required'
        ]);

        $type = $request->type;
        $price = $request->price;
        $user = User::find($request->user_id);
        $account = $user->account;
        $package_price = PackagePrice::find($request->package_price_id);
        if ($type == 'account') {
            if (!($account->package_id == null
                or Carbon::parse($account->charge_date)->addDays($account->days) <= today()
                or $package_price->package_id == $account->package_id)) {
                Alert::error('خطا', 'بسته فعال شما با بسته انتخابی متفاوت است');
                return back();
            }
        }

        $desc = "";
        if ($type == "account") {
            $desc = "شارژ اشتراک هلی آنلاین";
        } else if ($type == "sms") {
            $desc = "شارژ پیامک هلی آنلاین";
        }

        $zarinpal = new Zarinpal();
        $zarinpal->request($price, $user, $desc, $package_price->days, $type, $package_price->package_id);
    }

    public function verify(Request $request)
    {
        $zarinpal = new Zarinpal();
        $response = $zarinpal->verify();
        $payment = new Payment;
        // dd($response);
        return view('payment.verify', compact('response', 'payment'));
    }

    public function getItems(Request $request)
    {
        $user = User::where('username', $request->username)->first();
        if (!$user) {
            return [
                'status' => 'fail',
                'message' => 'اطلاعات یافت نشد.'
            ];
        }

        $account_id = $user->account_id;
        $date = $request->date;

        $payments = Payment::where('account_id', $account_id);

        if (!is_null($date)) {
            $payments->where('created_at', '>', $date);
        }

        $payments = $payments->get();
        return [
            'status' => 'success',
            'payments' => $payments
        ];
    }
}
