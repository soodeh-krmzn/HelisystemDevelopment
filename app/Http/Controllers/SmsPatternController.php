<?php

namespace App\Http\Controllers;

use App\Models\SmsPattern;
use App\Models\SmsPatternCategory;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SmsPatternController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $smsPatterns = SmsPattern::orderBy('category_id', 'desc')->get();
        confirmDelete('مطمئنید؟', 'آیا از حذف این مورد اطمینان دارید؟');
        return view('sms-pattern.index', compact('smsPatterns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $smsPatternCategories = SmsPatternCategory::all();
        return view('sms-pattern.create', compact('smsPatternCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|integer|exists:sms_pattern_categories,id',
            'name' => 'required|unique:sms_patterns,name',
            'text' => 'required',
            'page' => 'required|integer',
            'cost' => 'required|integer'
        ]);

        $smsPattern = SmsPattern::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'text' => $request->text,
            'page' => $request->page,
            'cost' => $request->cost
        ]);

        Alert::success('موفق', 'الگوی پیام جدید با موفقیت ایجاد شد.');
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(SmsPattern $smsPattern)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SmsPattern $smsPattern)
    {
        $smsPatternCategories = SmsPatternCategory::all();
        return view('sms-pattern.edit', compact('smsPattern', 'smsPatternCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SmsPattern $smsPattern)
    {
        $validated = $request->validate([
            'category_id' => 'required|integer|exists:sms_pattern_categories,id',
            'name' => 'required|unique:sms_patterns,name,' . $smsPattern->id,
            'text' => 'required',
            'page' => 'required|integer',
            'cost' => 'required|integer'
        ]);

        $smsPattern->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'text' => $request->text,
            'page' => $request->page,
            'cost' => $request->cost
        ]);

        Alert::success('موفق', 'الگوی پیام با موفقیت ویرایش شد.');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SmsPattern $smsPattern)
    {
        $smsPattern->delete();
        Alert::success('موفق', 'الگوی پیام با موفقیت حذف شد.');
        return back();
    }
}
