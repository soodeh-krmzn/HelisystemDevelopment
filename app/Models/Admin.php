<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'family', 'mobile', 'username', 'password',
        'reset_password_token', 'acl', 'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    public function getFullName(): string
    {
        return $this->name . ' ' . $this->family;
    }
    public function references(){
        return $this->hasMany(TicketNotif::class,'to');
    }

    public function newReferralIds() {
        return $this->references()->where('status',0)->pluck('ticket_id')->toArray();
    }
    public function newReferralsCount() {
        return $this->references->where('status',0)->count();
    }
}
