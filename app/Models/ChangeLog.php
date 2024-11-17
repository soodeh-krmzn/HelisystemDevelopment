<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeLog extends Model
{
    use HasFactory;
    protected $guarded=[];

    public static function  boot(){
        parent::boot();
        static::creating(function($model){
            $model->lang=app()->getLocale();
        });
        static::addGlobalScope('lang',function($builder){
            $builder->where('lang',app()->getLocale());
        });

    }
    
}
