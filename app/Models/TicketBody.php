<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketBody extends Model
{
    use HasFactory;
    protected $table = 'ticket_bodies';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }
    public function getFile()
    {
        if ($this->file != null) {
            if ($this->account_id == 0) {
                return "https://helisystem.ir/uploads/thickets/" . $this->file;
            } else {
                return "https://helionline.ir/uploads/thickets/" . $this->file;
            }
        } else {
            return false;
        }
    }
}
