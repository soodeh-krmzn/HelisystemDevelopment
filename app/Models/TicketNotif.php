<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketNotif extends Model
{
    use HasFactory;
    protected $table='ticket_notifs';
    protected $guarded=[];

    public function fromUser() {
        return $this->belongsTo(Admin::class,'from');
    }
    public function toUser() {
        return $this->belongsTo(Admin::class,'to');
    }
}
