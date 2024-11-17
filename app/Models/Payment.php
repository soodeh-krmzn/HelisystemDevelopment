<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['authority','driver', 'status', 'ref_id', 'message', 'price', 'type', 'user_id', 'username', 'account_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function scopeFilter($query)
    {
        if (request()->filled('status')) {
            if (request('status') == 'ok') {
                $query->where('status', 'OK');
            } else {
                $query->where(function ($q) {
                    $q->whereNot('status', 'OK');
                    $q->orWhere('status',null);
                });
            }
        }
        if (request()->filled('account')) {
            $query->where('account_id', request('account'));
        }
        if (request()->filled('type')) {
            $query->where('type', request('type'));
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


    public function message($response, $request)
    {

        $authority = $request->query('Authority');
        $payment = Payment::where('authority', $authority)->first();
        $authority = str_replace('A', '', $authority);
        $authority = ltrim($authority, 0);

        if ($response->success()) {
            $amount=$response->fee()+($payment->price.'0');
?>
            <div class="alert alert-success">
                <b>پرداخت موفق</b>
                <br>
                <b>کد رهگیری: </b>
                <?php echo per_number($response->referenceId()) ?>
                <br>
                <b>مبلغ: </b>
                <?php echo per_number(number_format($amount)) ?> ریال
                <br>
                <b>شناسه پرداخت: </b>
                <?php echo per_number($authority) ?>
            </div>
        <?php
        } else {
        ?>
            <div class="alert alert-danger">
                <b>
                    <?php echo $response->error()->message() ?>
                </b>
                <br>
                <b>مبلغ: </b>
                <?php echo per_number(number_format($payment->price)) ?>
                <br>
                <b>شناسه پرداخت: </b>
                <?php echo per_number($authority) ?>
            </div>
<?php
        }
    }
}
