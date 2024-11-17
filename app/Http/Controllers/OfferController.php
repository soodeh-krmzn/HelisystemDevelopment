<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\PackagePrice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class OfferController extends Controller
{
    public function index()
    {
        $offers = Offer::latest()->get();
        return view('offer.index', compact('offers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:offers,name',
            'type' => 'required',
            'per' => 'required|numeric',
            'min_price' => 'required|numeric',
        ]);

        if ($request->type == "percent" && $request->per > 100) {
            return back()->withErrors(['100percent' => 'تخفیف نمی تواند بیشتر از 100 درصد باشد.']);
        }

        $name = $request->name;
        $type = $request->type;
        $per = $request->per;
        $min_price = $request->min_price;
        $calc = $request->calc;
        $details = $request->details;
        $start_at = $request->start_at;
        $expire_at = $request->expire_at;
        $package = $request->package;

        $offer = Offer::create([
            'name' => $name,
            'type' => $type,
            'per' => $per,
            'min_price' => $min_price,
            'calc' => $calc,
            'details' => $details,
            'start_at' => $start_at,
            'expire_at' => $expire_at,
            'package' => $package
        ]);

        Alert::success("موفق", "کد تخفیف با موفقیت ایجاد شد.");
        return back();
    }

    public function edit(Offer $offer)
    {
        return view('offer.edit', compact('offer'));
    }

    public function update(Request $request, Offer $offer)
    {
        $validated = $request->validate([
            'name' => 'required|unique:offers,name,' . $offer->id,
            'type' => 'required',
            'per' => 'required|numeric',
            'min_price' => 'required|numeric',
        ]);

        if ($request->type == "percent" && $request->per > 100) {
            return back()->withErrors(['100percent' => 'تخفیف نمی تواند بیشتر از 100 درصد باشد.']);
        }

        $name = $request->name;
        $type = $request->type;
        $per = $request->per;
        $min_price = $request->min_price;
        $calc = $request->calc;
        $details = $request->details;
        $start_at = $request->start_at;
        $expire_at = $request->expire_at;
        $package = $request->package;

        $offer->update([
            'name' => $name,
            'type' => $type,
            'per' => $per,
            'min_price' => $min_price,
            'calc' => $calc,
            'details' => $details,
            'start_at' => $start_at,
            'expire_at' => $expire_at,
            'package' => $package
        ]);

        Alert::success("موفق", "کد تخفیف با موفقیت ویرایش شد.");
        return back();
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $offer = Offer::find($id);
        $offer->delete();
        return $offer->showIndex();
    }

    public function check(Request $request): JsonResponse
    {
        $packagePrice_id = $request->package_price_id;
        $packagePrice = PackagePrice::find($packagePrice_id);
        if (!$packagePrice) {
            return response()->json([
                'message' => "لطفا یک بسته را انتخاب کنید."
            ], 404);
        }

        $price = min(array_filter([$packagePrice->price, $packagePrice->off_price], function($var) {
            return $var > 0;
        }));
        if (!$price) {
            return response()->json([
                "message" => "خطا"
            ], 400);
        }

        $offer_code = $request->offer_code;
        $offer = Offer::where('name', $offer_code)->first();
        if (!$offer) {
            return response()->json([
                "message" => "کد تخفیف اشتباه است.",
                "price" => $price
            ], 404);
        }

        $user_selected_package = $request->type;

        $type = $offer->type;
        $per = $offer->per;
        $min_price = $offer->min_price;
        $details = $offer->details;
        $start_at = $offer->start_at;
        $expire_at = $offer->expire_at;
        $package = $offer->package;

        if ($package != "all" && $package != $user_selected_package) {
            return response()->json([
                "message" => "کد تخفیف وارد شده برای این خرید معتبر نیست.",
                "price" => $price
            ], 404);
        }

        if ($start_at != null && $expire_at != null) {
            $now = date('Y-m-d H:i:s');
            if ($start_at <= $now && $expire_at >= $now) {

                if ($price < $min_price) {
                    return response()->json([
                        "message" => "این کد تخفیف برای خریدهای بالای " . $min_price . " می باشد.",
                        "price" => $price
                    ], 400);
                }

                switch ($type) {
                    case "percent":
                        $offer_price = ($price * $per) / 100;
                        break;
                    case "price":
                        $offer_price = $per;
                        break;
                    default:
                        return response()->json([
                            "message" => "کد تخفیف اشتباه است.",
                            "price" => $price
                        ], 404);
                }

                return response()->json([
                    "offer_price" => $offer_price,
                    "final_price" => $price - $offer_price
                ], 200);

            } else {
                return response()->json([
                    "message" => "کد تخفیف وارد شده معتبر نیست.",
                    "price" => $price
                ], 404);
            }
        }
    }

}
