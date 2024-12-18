<?php

namespace App\Models;

use DateTime;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function getCountByStatus($status)
    {
        return Account::where('status', $status)->get()->count();
    }

    public function scopeFilter($query)
    {
        if (request()->filled('name')) {
            $query->where('name', 'LIKE', "%" . request('name') . "%");
        }
        if (request()->filled('family')) {
            $query->where('family', 'LIKE', "%" . request('family') . "%");
        }
        if (request()->filled('mobile')) {
            $query->where('mobile', 'LIKE', "%" . request('mobile') . "%");
        }
        if (request()->filled('center')) {
            $query->where('center', 'LIKE', "%" . request('center') . "%");
        }
        if (request()->filled('status')) {
            $query->where(function ($q) {
                foreach (request('status') as $key => $value) {
                    if ($key == 0) {
                        $q->where('status', $value);
                    } else {
                        $q->orWhere('status', $value);
                    }
                }
            });
        }
    }

    public function daysLeft()
    {

        // $expire_charge = Carbon::parse($this->charge_date)->addDays($this->days);
        // $today = Carbon::now();
        // return per_number($today->diffInDays($expire_charge, false));
        $expire_charge = new DateTime(Carbon::parse($this->charge_date)->addDays($this->days)->format('Y-m-d'));
        $today = new DateTime();
        return $today->diff($expire_charge, $absolute = false)->format('%R%a');
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
    public function sms_package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'sms_package_id');
    }

    public function getFullName(): string
    {
        return $this->name . ' ' . $this->family;
    }

    public function getStatus()
    {
        switch ($this->status) {
            case 'suspend':
                return 'bg-warning';
                break;

            case 'active':
                return 'bg-success';
                break;

            case 'deactive':
                return 'bg-danger';
                break;

            default:
                return '';
                break;
        }
    }

    public function tokens()
    {
        return $this->hasMany(Token::class);
    }
}
