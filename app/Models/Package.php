<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory, SoftDeletes;

        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public static function boot() {
        parent::boot();
        static::addGlobalScope('lang',function($builder){
            $builder->where('lang',app()->getLocale());
        });
        static::creating(function($model){
            $model->lang=app()->getLocale();
        });
    }
    protected $fillable = [
        'name', 'details', 'type'
    ];

    public function menus(): BelongsToMany
    {
        return $this->belongsToMany(Menu::class, "package_menu");
    }

    public function prices(): HasMany
    {
        return $this->hasMany(PackagePrice::class);
    }
}
