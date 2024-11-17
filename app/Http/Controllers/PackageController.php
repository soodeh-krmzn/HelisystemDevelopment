<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Package;
use App\Models\PackagePrice;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PackageController extends Controller
{
    public function index()
    {
        ert('cd');
        $packages = Package::all();
        $menus = Menu::all();
        return view('package.index', compact('packages', 'menus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'type' => 'required'
        ]);

        $package = Package::create([
            'name' => $request->name,
            'type' => $request->type,
            'details' => $request->details
        ]);

        Alert::success("موفق", "بسته جدید با موفقیت ایجاد شد.");
        return back();
    }

    public function changePackage(Request $request)
    {
        $package_id = $request->package_id;
        $packagePrice = new PackagePrice;
        $list = PackagePrice::where("package_id", $package_id)->get();
        $packages = Package::all();
        return $packagePrice->form($list, $request->user_id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Package $package)
    {
        return view('package.edit', compact('package'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name' => 'required',
            'type' => 'required'
        ]);

        $package->update([
            'name' => $request->name,
            'type' => $request->type,
            'details' => $request->details
        ]);

        Alert::success("موفق", "بسته با موفقیت ویرایش شد.");
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Package $package)
    {
        $package->delete();
        alert()->success('موفق','بسته مورد نظر حذف شد');
        return back();
    }

    public function menu(Package $package)
    {
        $menus = Menu::where("parent_id", 0)->orderBy("name")->get();
        return view('package.menu', compact('package', 'menus'));
    }

    public function storeMenu(Request $request, Package $package)
    {
        $package->menus()->sync($request->menus);
        Alert::success("موفق", "اطلاعات با موفقیت ثبت شد.");
        return redirect()->route('package.index');
    }
}
