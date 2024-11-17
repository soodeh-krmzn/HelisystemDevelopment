<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;
    protected $fillable = ['meta_key', 'meta_value', 'lang'];

    public static function get_option($meta_key)
    {
        $check = Option::where('meta_key', $meta_key)->first();
       
        if ($check) {
            return $check->meta_value;
        } else {
            return "";
        }
    }

    public static function get_option_lang($meta_key)
    {
        $check = Option::where('meta_key', $meta_key)->where('lang', app()->getLocale())->first();
        if ($check) {
            return $check->meta_value;
        } else {
            return "";
        }
    }

    public function update_option($meta_key, $meta_value)
    {
        $check = Option::where('meta_key', $meta_key)->first();
        if ($check) {
            $check->meta_value = $meta_value;
            $check->save();
        } else {
            Option::create([
               'meta_key' => $meta_key,
               'meta_value' => $meta_value
            ]);
        }
    }

    public function update_option_lang($meta_key, $meta_value)
    {
        $check = Option::where('meta_key', $meta_key)->first();
        if ($check) {
            $check->meta_value = $meta_value;
            $check->lang = app()->getLocale();
            $check->save();
        } else {
            Option::create([
               'meta_key' => $meta_key,
               'meta_value' => $meta_value,
               'lang'=>app()->getLocale()
            ]);
        }
    }

}
