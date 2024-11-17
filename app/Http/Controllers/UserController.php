<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Account;
use App\Models\Message;
use App\Models\LoginRecord;
use App\Models\VisitRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $users = User::all();
        $account = "";
        if ($request->account) {
            $account = Account::find($request->account);
            if (!$account) {
                return abort(404);
            }
            $users = $account->users;
        }

        confirmDelete("مطمئنید؟", "آیا از حذف این مورد اطمینان دارید؟");
        return view('user.index', compact('users', 'account'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|integer|exists:accounts,id',
            'name' => 'required',
            'family' => 'required',
            'mobile' => 'required|numeric|regex:/(09)[0-9]{9}/',
            'username' => 'required|unique:users,username',
            'password' => 'required',
            'access' => 'required'
        ]);

        $user = User::create([
            'account_id' => $request->account_id,
            'name' => $request->name,
            'family' => $request->family,
            'mobile' => $request->mobile,
            'username' => $request->username,
            'password' => $request->password,
            'access' => $request->access
        ]);

        Alert::success("موفق", "کاربر جدید با موفقیت ایجاد شد.");
        return back();
    }

    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }

    public function changePassword($userId)
    {
        $user = User::find($userId);
        return view('user.change-password', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required',
            'family' => 'required',
            'mobile' => 'required|numeric|regex:/(09)[0-9]{9}/',
            'username' => 'required|unique:users,username,' . $user->id,
            'access' => 'required'
        ]);

        $user->update([
            'name' => $request->name,
            'family' => $request->family,
            'mobile' => $request->mobile,
            'username' => $request->username,
            'access' => $request->access,
            'status' => $request->status,
            'description' => $request->description,
        ]);

        Alert::success("موفق", "کاربر با موفقیت ویرایش شد.");
        return back();
    }

    public function updatePassword(Request $request)
    {
        $user = User::find($request->user_id);
        $user->password = $request->new_password;
        $user->save();

        Alert::success("موفق", "رمز جدید با موفقیت تنظیم شد.");
        return back();
    }

    public function destroy(User $user)
    {
        $user->delete();
        Alert::success("موفق", "مورد با موفقیت حذف شد.");
        return back();
    }

    public function loginRecord(Request $request)
    {
        $records = LoginRecord::filter()->latest()->paginate(20);
        return view('user.login-record', compact('records', 'request'));
    }

    public function visitRecord(Request $request)
    {
        if ($request->unique == true) {
            $records = VisitRecord::select('account_id', DB::raw('COUNT(*) as count'),DB::raw('MAX(created_at) as max_created_at'))
            ->filter()
            ->groupBy('account_id')
            ->latest()
            ->paginate(20);
        } else {
            $records = VisitRecord::filter()->latest()->paginate(20);
        }
        //$records->unique('account_id');
        $accounts = Account::latest()->get();
        $query = $request->query();
        unset($query['q']); $query['unique']=true;
        return view('user.visit-record', compact('records', 'request', 'accounts','query'));
    }

    public function userActivity(Request $request)
    {
        $default = 3;
        $records = VisitRecord::activity($default)->paginate(20);
       // dd($records);
        return view('user.user-activity', compact('records', 'request'));
    }

    public function phonebook()
    {
        $accounts = Account::all()->groupBy('status');
        $invited = Message::all();
        return view('user.phone-book', compact('accounts', 'invited'));
    }
}
