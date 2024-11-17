<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'parent_id', 'name', 'icon', 'url', 'learn_url', 'details', 'display_order', 'display_nav'
    ];

    protected static function boot() {
        parent::boot();
        static::addGlobalScope('lang',function($builder){
            $builder->where('lang',app()->getLocale());
        });
        static::creating(function($model){
            $model->lang=app()->getLocale();
        });
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, "parent_id");
    }

    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, "parent_id");
    }

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class, "package_menu");
    }

    public function childrenListPackage($package)
    {
        if ($this->children->count() > 0) {
        ?>
            <ul class="pr-5">
                <?php
                foreach ($this->children as $child) {
                ?>
                    <li>
                        <label>
                            <input type="checkbox" name="menus[]" value="<?php echo $child->id; ?>" <?php echo $package->menus->contains($child->id) ? "checked" : ''; ?>>
                            <?php echo $child->name; ?>
                        </label>
                        <?php $child->childrenListPackage($package); ?>
                    </li>
                <?php
                }
                ?>
            </ul>
        <?php
        }
    }

}
