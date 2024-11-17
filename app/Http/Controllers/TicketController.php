<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Ticket;
use App\Models\Account;
use App\Models\TicketBody;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $tickets = Ticket::filter()->orderBy('updated_at','desc')->orderBy('status')->paginate(20);
        $accounts = Account::latest()->get();
        return view('ticket.index', compact('tickets', 'request', 'accounts'));
    }

    public function openChat(Request $request, Ticket $ticket)
    {
        $ticket->isTicketReferrencedToUser();

        //submit answer
        if ($request->isMethod('post')) {
            $request->validate([
                'body' => $request->hasFile('file') ? 'nullable' : "required",
                'file' => 'file',
            ]);
            $fileName = null;
            if ($request->file('file')) {
                $fileName = doUpload($request->file, ert('t-a'));
            }
            $ticket->chats()->create([
                'user_id' => auth()->id(),
                'body' => $request->body,
                'file' => $fileName,
            ]);
            if ($ticket->status != 'waiting-for-customer') {
                $ticket->update([
                    'status' => 'waiting-for-customer'
                ]);
            }

            Alert::success("موفق", "پاسخ ارسال شد");
            return back();
        }
        //end
        // change status
        if ($request->has('status')) {
            $ticket->update([
                'status' => $request->status
            ]);
            Alert::info("موفق", "وضعیت تغییر کرد");
            return back();
        }
        //end
        //refrence to
        if ($request->has('ref')) {
            $ticket->references()->create([
                'from' => auth()->id(),
                'to' => $request->ref,
                'status' => 0
            ]);
            Alert::info("موفق", "اعلان ارسال شد");
            return back();
        }
        //end
        $ticket->Chats()->whereNot('account_id', '0')->update([
            'seen' => 1
        ]);
        $admins = Admin::all();
        return view('ticket.openChat', compact('ticket', 'admins'));
    }

    /**
     * Store a newly created resource in storage.
     */
}
