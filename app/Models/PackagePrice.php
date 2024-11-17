<?php

namespace App\Models;

use App\Models\Package;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PackagePrice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'package_id', 'days', 'price', 'off_price'
    ];

    public function form($list, $user_id)
    {
        $user = User::find($user_id);
        if ($list->count() > 0) {
            ?>
            <form action="<?php echo route('startPayment') ?>" method="post">
                <input type="hidden" name="_token" value="<?php echo csrf_token() ?>">
                <?php
                foreach ($list as $l) {
                    ?>
                    <div class="callout callout-success">
                        <h5>
                            <span>بسته</span>
                            <b><?php echo per_number(number_format($l->days)); ?></b>
                            <span><?=request('type') == "sms" ? "عددی" : "روزه"?></span>
                        </h5>
                        <p class="pb-3 pl-1">
                            <?php
                            if (($l->off_price != $l->price) && $l->price != 0) {
                                ?>
                                <del style="color: red"><?php echo per_number(number_format($l->price)) ?></del>
                                <br>
                                <?php echo per_number(number_format($l->off_price)); ?>
                                <?php
                            } else {
                                echo per_number(number_format($l->price));
                            }
                            ?>
                            <button type="button" class="btn btn-outline-success pull-left select-package" data-id="<?php echo $l->id; ?>" data-price="<?php echo ($l->price)?? '0'; ?>" data-off_price="<?php echo ($l->off_price)?? '0'; ?>" data-days="<?php echo $l->days ?>">
                                <i class="fa fa-check-circle-o"></i>
                                انتخاب
                            </button>
                        </p>
                    </div>
                    <?php
                } ?>
                <div class="input-group input-group-sm mb-3">
                    <input type="text" id="offer-code" class="form-control" placeholder="کد تخفیف...">
                    <span class="input-group-append">
                        <button type="button" id="check-offer" class="btn btn-info btn-flat">اعمال تخفیف</button>
                    </span>
                </div>
                <div id="offer-price"></div>
                <div class="input-group-sm mb-3">
                    <label class="form-label">مبلغ نهایی: </label>
                    <input type="text" name="price" id="price" class="form-control d-block" placeholder="مبلغ نهایی..." readonly>
                    <input type="hidden" name="package_price_id" id="package-price-id">
                    <input type="hidden" name="user_id" value="<?php echo $user->id ?>">
                    <input type="hidden" name="type" id="type">
                </div>
                <button type="submit" class="btn btn-block btn-warning">
                    <i class="fa fa-check"></i> پرداخت
                </button>
                <?php
        } else {
            ?>
            <div class="alert alert-danger">هیچ بسته ای جهت خرید موجود نیست.</div>
            <?php
        }
        ?>
        <a href="https://helionline.ir" class="btn btn-block btn-danger">
            <i class="fa fa-backward"></i> بازگشت به نرم افزار
        </a>
        <?php
    }

    public function package(){
        return $this->belongsTo(Package::class);
    }

}
