<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Ticket;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use RealRashid\SweetAlert\Facades\Alert;
use App\Services\SMS;

class DashboardController extends Controller
{
    public function index()
    {
        $option = new Option;
        $account = new Account;
        return view('index', compact('option', 'account'));
    }

    public function updateMagfaCredit(Request $request)
    {
        $sms = new SMS;
        $credit = $sms->credit()->balance;
        $option = new Option;
        $option->update_option('magfa_credit', $credit);
        return back();
    }
}
