<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = Menu::orderBy('parent_id')->get();
        confirmDelete("مطمئنید؟", "آیا از حذف این مورد اطمینان دارید؟");
        return view('menu.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $menus = Menu::all();
        return view('menu.create', compact('menus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required',
            'icon' => 'required',
            'url' => 'required'
        ]);

        $menu = Menu::create([
            'parent_id' => $request->parent_id,
            'name' => $request->label,
            'icon' => $request->icon,
            'url' => $request->url,
            'learn_url' => $request->learn_url,
            'display_order' => $request->display_order ?? 0,
            'display_nav' => $request->display_nav,
            'details' => $request->details
        ]);

        Alert::success("موفق", "منو جدید با موفقیت ایجاد شد.");
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        $children = $menu->children->pluck('id');
        $menus = Menu::whereNot('id', $menu->id)->whereNotIn('id', $children)->get();
        return view('menu.edit', compact('menu', 'menus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'label' => 'required',
            'icon' => 'required',
            'url' => 'required'
        ]);

        $menu->update([
            'parent_id' => $request->parent_id,
            'name' => $request->label,
            'icon' => $request->icon,
            'url' => $request->url,
            'learn_url' => $request->learn_url,
            'display_order' => $request->display_order ?? $menu->display_order,
            'display_nav' => $request->display_nav,
            'details' => $request->details
        ]);

        Alert::success("موفق", "منو با موفقیت ویرایش شد.");
        return back();
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        Alert::success("موفق", "مورد با موفقیت حذف شد.");
        return back();
    }

}
