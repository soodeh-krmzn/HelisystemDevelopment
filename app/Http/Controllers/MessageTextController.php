<?php

namespace App\Http\Controllers;

use App\Models\MessageText;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class MessageTextController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $messageTexts = MessageText::all();
        confirmDelete('مطمئنید؟', 'آیا از حذف این مورد اطمینان دارید؟');
        return view('message-text.index', compact('messageTexts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:message_texts,name',
            'text' => 'required'
        ]);

        MessageText::create([
            'name' => $request->name,
            'text' => $request->text
        ]);

        Alert::success('موفق', 'با موفقیت ثبت شد.');
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(MessageText $messageText)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MessageText $messageText)
    {
        return view('message-text.edit', compact('messageText'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MessageText $messageText)
    {
        $validated = $request->validate([
            'name' => 'required|unique:message_texts,name,' . $messageText->id,
            'text' => 'required'
        ]);

        $messageText->update([
            'name' => $request->name,
            'text' => $request->text
        ]);

        Alert::success('موفق', 'با موفقیت ثبت شد.');
        return redirect()->route('message-text.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MessageText $messageText)
    {
        $messageText->delete();
        Alert::success('موفق', 'با موفقیت حذف شد.');
        return back();
    }
}
