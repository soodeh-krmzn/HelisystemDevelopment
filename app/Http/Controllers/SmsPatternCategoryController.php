<?php

namespace App\Http\Controllers;

use App\Models\SmsPatternCategory;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SmsPatternCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $smsPatternCategories = SmsPatternCategory::all();
        confirmDelete('مطمئنید؟', 'آیا از حذف این مورد اطمینان دارید؟');
        return view('sms-pattern-category.index', compact('smsPatternCategories'));
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
            'name' => 'required',
            'display_order' => 'nullable|integer'
        ]);

        $smsPatternCategory = SmsPatternCategory::create([
            'name' => $request->name,
            'display_order' => $request->display_order
        ]);

        Alert::success('موفق', 'دسته پیام جدید با موفقیت ایجاد شد.');
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(SmsPatternCategory $smsPatternCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SmsPatternCategory $smsPatternCategory)
    {
        return view('sms-pattern-category.edit', compact('smsPatternCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SmsPatternCategory $smsPatternCategory)
    {
        $validated = $request->validate([
            'name' => 'required',
            'display_order' => 'nullable|integer'
        ]);

        $smsPatternCategory->name = $request->name;
        $smsPatternCategory->display_order = $request->display_order;
        $smsPatternCategory->save();

        Alert::success('موفق', 'دسته پیام با موفقیت ویرایش شد.');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SmsPatternCategory $smsPatternCategory)
    {
        $smsPatternCategory->delete();
        Alert::success('موفق', 'دسته پیام با موفقیت حذف شد.');
        return back();
    }
}
