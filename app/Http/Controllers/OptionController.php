<?php

namespace App\Http\Controllers;

use App\Models\Option;
use Illuminate\Http\Request;

class OptionController extends Controller
{

    public function index()
    {
        $option = new Option;
        // dd($option->get_option('default_package'));
        return view('option.index', compact('option'));
    }

    public function store(Request $request)
    {
        foreach ($request->all() as $key => $value) {
            $check = Option::where('meta_key', $key)->first();
            if ($check) {
                $check->meta_value = $value;
                $check->save();
            } else {
                Option::create([
                   'meta_key' => $key,
                   'meta_value' => $value
                ]);
            }
        }
        $option = new Option;
        return back();
    }

    // public function get_option($metaKey)
    // {
    //     $item = Option::where('meta_key', $metaKey)->first();
    //     if ($item) {
    //         return $item->meta_value;
    //     } else {
    //         return null;
    //     }
    // }

    public function update_option(Request $request)
    {
        $item = Option::where('meta_key', $request->meta_key)->first();
        if ($item) {
            $item->meta_value = $request->meta_value;
            $item->save();
        } else {
            Option::create([
               'meta_key' => $request->meta_key,
               'meta_value' => $request->meta_value
            ]);
        }
    }

}
