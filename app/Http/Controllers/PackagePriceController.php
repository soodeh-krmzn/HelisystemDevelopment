<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\PackagePrice;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PackagePriceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $prices = PackagePrice::all();
        $package = '';

        if ($request->package) {
            $package = Package::find($request->package);
            if (!$package) {
                return abort(404);
            }
            $prices = $package->prices;
        }

        return view('package-price.index', compact('prices', 'package'));
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
            'package_id' => 'required|exists:packages,id',
            'days' => 'required|integer',
            'price' => 'required|numeric',
            'off_price' => 'nullable|numeric'
        ]);

        $check = PackagePrice::where('package_id', $request->package_id)->where('days', $request->days)->first();
        if ($check) {
            return back()->with('error', 'repetition');
        }

        $package_price = PackagePrice::create([
            'package_id' => $request->package_id,
            'days' => $request->days,
            'price' => $request->price,
            'off_price' => $request->off_price,
        ]);

        Alert::success("موفق", "تعرفه جدید با موفقیت ایجاد شد.");
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(PackagePrice $packagePrice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PackagePrice $packagePrice)
    {
        return view('package-price.edit', compact('packagePrice'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PackagePrice $packagePrice)
    {
        $validated = $request->validate([
            'days' => 'required|integer',
            'price' => 'required|numeric',
            'off_price' => 'nullable|numeric'
        ]);

        $check = PackagePrice::whereNot('id', $packagePrice->id)->where('package_id', $packagePrice->package_id)->where('days', $request->days)->first();
        if ($check) {
            return back()->with('error', 'repetition');
        }

        $packagePrice->update([
            'days' => $request->days,
            'price' => $request->price,
            'off_price' => $request->off_price
        ]);

        Alert::success("موفق", "تعرفه با موفقیت ویرایش شد.");
        return back();
    }

}
