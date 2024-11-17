<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Zarinpal
{

    //github Repository URL:
    //https://github.com/pishran/Zarinpal

    private $merchentId = '22e6f21a-1348-4887-9c44-995e65e27472';
    private $callbackUrl = 'https://helisystem.ir/payment/verify';
    private $driver = 'zarinpal';
    public function request($price, $user, $description, $days, $type,$package_id)
    {
        $response = zarinpal()
            ->merchantId($this->merchentId) // تعیین مرچنت کد در حین اجرا - اختیاری
            ->amount($price) // مبلغ تراکنش
            ->request()
            ->description($description) // توضیحات تراکنش
            ->callbackUrl($this->callbackUrl . '?days=' . $days . '&type=' . $type.'&package_id=' . $package_id) // آدرس برگشت پس از پرداخت
            ->mobile($user->mobile) // شماره موبایل مشتری - اختیاری
            ->send();

        if (!$response->success()) {
            return $response->error()->message();
        }
        // ذخیره اطلاعات در دیتابیس
        Payment::create([
            'account_id' => $user->account_id,
            'price' => $price,
            'authority' => $response->authority(),
            'user_id' => $user->id,
            'username' => $user->username,
            'driver' => $this->driver,
            'type' => $type
        ]);

        // هدایت مشتری به درگاه پرداخت
        $url = $response->url();
        ?>
        <form id="paymentForm" action="<?php echo $url; ?>"></form>
        <script>
            function submitForm() {
                const form = document.getElementById('paymentForm');
                form.addEventListener('submit', (event) => {
                    event.preventDefault();
                    // Perform validation and processing here
                });
                form.submit();
            }
            submitForm();
        </script>
        <?php
    }

    public function verify()
    {
        $authority = request()->query('Authority'); // دریافت کوئری استرینگ ارسال شده توسط زرین پال
        $status = request()->query('Status'); // دریافت کوئری استرینگ ارسال شده توسط زرین پال
        $days = request()->query('days');
        $type = request()->query('type');

        $payment = Payment::where('authority', $authority)->first();
        $payment->status = $status;
        $payment->save();

        $account_id = $payment->account_id;

        $response = zarinpal()
            ->merchantId($this->merchentId) // تعیین مرچنت کد در حین اجرا - اختیاری
            ->amount($payment->price)
            ->verification()
            ->authority($authority)
            ->send();

        if ($response->success()) {
            $payment->card = $response->cardPan();
            $payment->ref_id = $response->referenceId();
            $payment->save();

            $account = Account::find($account_id);
            switch ($type) {
                case 'account':
                    if (Carbon::parse($account->charge_date)->addDays($account->days) <= today()) {
                        $account->charge_date = today();
                        $account->days = $days;
                        $account->package_id=request('package_id');
                    } else {
                        $account->days = $account->days + $days;
                
                    }
                    break;
                case 'sms':
                    $account->sms_charge += $days;
                    $account->sms_package_id=request('package_id');

                    break;
            }
            $account->save();
        }

        return $response;
    }

}
