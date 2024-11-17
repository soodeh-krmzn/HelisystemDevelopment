<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Message;
use App\Models\MessageText;
use App\Services\SMS;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::orderBy('created_at', 'desc')->get();
        return view('message.index', compact('messages'));
    }

    public function create()
    {
        $accounts = Account::all();
        $texts = MessageText::all();
        return view('message.create', compact('accounts', 'texts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mobile' => 'required|regex:/(09)[0-9]{9}/',
            'text' => 'required'
        ]);

        try {
            $mobile = $request->mobile;
            $text = $request->text;
            $sms = new SMS();
            $sms->send($mobile, $text);

            $message = Message::create([
                'account_id' => $request->account_id,
                'name' => $request->name,
                'family' => $request->family,
                'mobile' => $mobile,
                'text' => $text
            ]);

            Alert::success('موفق', 'با موفقیت ارسال شد.');
            return redirect()->route('message.index');
        } catch (\Exception $e) {
            Alert::error('خطا', $e->getMessage());
            return back();
        }
    }
}
