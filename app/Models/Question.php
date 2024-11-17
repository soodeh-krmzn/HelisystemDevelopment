<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory,SoftDeletes;
    protected $table='questions';
    protected $guarded=[];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('lang', function ($builder) {
            $builder->where('lang', app()->getLocale());
        });
    }

    public function component(){
        return $this->belongsTo(QuestionComponent::class, 'component_id');
    }
}
