<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\SMS;
use App\Models\Option;
use App\Models\Account;
use App\Models\Package;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use PDO;
use RealRashid\SweetAlert\Facades\Alert;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $accounts = Account::filter()->orderByDesc('created_at')->paginate(20);
        return view('account.index', compact('accounts', 'request'));
    }

    public function create()
    {
        return view('account.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'family' => 'required',
            'center' => 'required',
            'phone' => 'required|numeric|regex:/(0)[0-9]{10}/',
            'mobile' => 'required|numeric|regex:/(09)[0-9]{9}/',
            'city' => 'required',
            'town' => 'required',
            'days' => 'required|integer',
            'sms_charge' => 'nullable|integer',
        ]);

        $account = Account::create([
            'name' => $request->name,
            'family' => $request->family,
            'center' => $request->center,
            'phone' => $request->phone,
            'mobile' => $request->mobile,
            'city' => $request->city,
            'town' => $request->town,
            'address' => $request->address,
            'days' => $request->days,
            'charge_date' => $request->charge_date,
            'sms_charge' => $request->sms_charge,
            'group_id' => $request->group_id,
            'slug' => $request->slug,
            'zarinpal' => $request->zarinpal
        ]);

        Alert::success("موفق", "اشتراک جدید با موفقیت ایجاد شد.");
        return back();
    }

    public function edit(Account $account)
    {
        $sms_packages = Package::where('type', 'sms')->get();
        $account_packages = Package::withoutGlobalScope('lang')->where('type', 'account')->get();
        return view('account.edit', compact('account', 'sms_packages', 'account_packages'));
    }

    public function update(Request $request, Account $account)
    {
        $validated = $request->validate([
            'name' => 'required',
            'family' => 'required',
            'center' => 'required',
            'phone' => 'required|numeric|regex:/(0)[0-9]{10}/',
            'mobile' => 'required|numeric|regex:/(09)[0-9]{9}/',
            'city' => 'required',
            'town' => 'required',
            'days' => 'required|integer',
            'sms_charge' => 'nullable|integer',
            'pc_token' => 'nullable'
        ]);
        // dd($request->group_id);
        $account->update([
            'name' => $request->name,
            'family' => $request->family,
            'center' => $request->center,
            'phone' => $request->phone,
            'mobile' => $request->mobile,
            'city' => $request->city,
            'town' => $request->town,
            'address' => $request->address,
            'days' => $request->days,
            'charge_date' => $request->charge_date,
            'sms_charge' => $request->sms_charge,
            // 'group_id' => $request->group_id,
            'slug' => $request->slug,
            'zarinpal' => $request->zarinpal,
            'sms_package_id' => $request->sms_package,
            'package_id' => $request->account_package,
            'pc_token' => $request->pc_token,
        ]);

        Alert::success("موفق", "اشتراک با موفقیت ویرایش شد.");
        return back();
    }

    public function changeStatus(Request $request, Account $account)
    {

        $account->status = $request->status;
        $account->status_detail = $request->status_detail;
        if ($request->status == "active" && $account->charge_date == null) {
            $account->charge_date = today();
        }
        // if ($request->status == "active") {
        //     $a = new SMS();
        //     $fullName = $account->name . ' ' . $account->family;
        //     $option = new Option;
        //     $signiture = $option->get_option('sms_signiture_fa');

        //     $message = __('message.activation', [
        //         'name' => $fullName,
        //         'center' => $account->center,
        //         'signiture' => $signiture,
        //     ], 'fa');

        //     $a->send($account->mobile, $message);
        // }
        Alert::success("موفق", "وضعیت اشتراک با موفقیت تغییر کرد.");
        $account->save();
    }

    public function showDb(Account $account)
    {
        return view('account.database', compact('account'));
    }

    public function storeDb(Request $request, Account $account)
    {
        $dbName = $request->db_name;
        $newUsername = $request->db_user;
        $newPassword = $request->db_pass;
        try {
            DB::statement("CREATE DATABASE IF NOT EXISTS `$dbName`");
            DB::statement("CREATE USER '$newUsername'@'%' IDENTIFIED BY '$newPassword'");
            DB::statement("GRANT ALL PRIVILEGES ON `$dbName`.* TO '$newUsername'@'%';");
        } catch (\Throwable $th) {
            dd($th, $newPassword);
        }
        $account->db_name = Crypt::encryptString($request->db_name);
        $account->db_user = Crypt::encryptString($request->db_user);
        $account->db_pass = Crypt::encryptString($request->db_pass);
        $account->save();

        Alert::success("موفق", "اشتراک با موفقیت ویرایش شد.");
        return redirect()->route('account.index');
    }
    public function privilages()
    {
        return false;
        $accounts = Account::all();
        $Errors = collect([]);
        foreach ($accounts as $account) {
            $dbName = $account->id . 'db';
            $newUsername = $account->id . 'user';
            $newPassword = Str::random(12);
            if ($account->db_name) {
                try {
                    DB::statement("CREATE DATABASE IF NOT EXISTS `$dbName`");
                    DB::statement("CREATE USER '$newUsername'@'%' IDENTIFIED BY '$newPassword'");
                    DB::statement("GRANT ALL PRIVILEGES ON `$dbName`.* TO '$newUsername'@'%';");
                } catch (\Throwable $th) {
                    $Errors[$account->id] = $th->getMessage();
                }
            } else {
                $Errors[$account->id] = 'dose not have a database yet';
            }

            $account->db_name = Crypt::encryptString($dbName);
            $account->db_user = Crypt::encryptString($newUsername);
            $account->db_pass = Crypt::encryptString($newPassword);
            $account->save();
        }
        dd($Errors);
    }

    public function license(Account $account)
    {
        $licenses = $account->licenses();
        dd($licenses);
        return view('account.license', compact('licenses' , 'account'));
    }


    public function changeLicenseStatus(Request $request, Account $account)
    {
        $license = $account->licenses()->findOrFail($request->license_id);
        $license->status = $request->status;
        $license->save();

        Alert::success("موفق", "وضعیت لایسنس با موفقیت تغییر کرد.");
        return back();
    }
}
