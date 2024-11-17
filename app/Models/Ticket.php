<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $table = 'tickets';
    protected $guarded = [];
    public static function boot()
    {
        parent::boot();
        static::addGlobalScope('lang', function ($builder) {
            $builder->where('lang', app()->getLocale());
        });
    }

    public function scopeFilter($query)
    {
        if (request('unreads')) {
            return $query->whereHas('chats',function($q) {
                 $q->whereNot('account_id',0)->where('seen',0);
             });
         }
        if (request('action') == 'newReferrals') {
            return  $query->whereIn('id', auth()->user()->newReferralIds());
        }
        if (request()->filled('subject')) {
            $query->where('subject', 'LIKE', "%" . request('subject') . "%");
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
        }else{
            $query->whereNot('status','closed');
        }

        if (request()->filled('account')) {
            $query->where('account_id', request('account'));
        }
        if (request()->filled('from')) {
            $from = verta()->parse(request('from'))->datetime();
            $query->where('created_at', '>', $from);
        }
        if (request()->filled('to')) {
            $to = verta()->parse(request('to'))->toCarbon()->addDay();
            $query->where('created_at', '<', $to);
        }
    }

    public function chats()
    {
        return $this->hasMany(TicketBody::class);
    }
    public function references()
    {
        return $this->hasMany(TicketNotif::class);
    }

    public function lastMsgTime()
    {
        return $this->chats()->latest('created_at')->value('created_at')->diffForHumans(['parts' => 1]);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function referencesUser()
    {
        $refs = $this->references;
        return $refs->map(function ($item) {
            $icon = $item->status == 1 ? "(<i class='fa fa-eye text-success'></i>)" : "(<i class='fa fa-eye-slash text-secondary'></i>)";
            return $item->fromUser->getFullName() . ' به ' . $item->toUser->getFullName() . $icon;
        })->implode(' - ');
    }

    public function isTicketReferrencedToUser()
    {
        $res = $this->references()->where(['status' => 0, 'to' => auth()->id()])->first();
        if ($res) {
            $res->update([
                'status' => 1
            ]);
        }
    }
}
