<?php

namespace App\Http\Controllers;

use PDO;
use App\Models\Account;
use App\Models\ChangeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class ChangeLogController extends Controller
{
    public function index(Request $request)
    {
        // store-log
        if ($request->action == 'store') {
            $this->store($request);
            return to_route('changeLogIndex');
        }
        //end store-log
        if ($request->action == 'update') {
            $this->update($request);
            return to_route('changeLogIndex');
        }

        //change-status
        if ($request->filled('id')) {
            // ChangeLog::update(['status'=>0]);
            DB::update("UPDATE change_logs SET STATUS=0 where lang='" . app()->getLocale() . "'");
            if ($request->status == 1) {
                $change = ChangeLog::find($request->id);
                $change->update([
                    'status' => $request->status
                ]);
            }
            alert()->success('موفق', "وضعیت ها تغییر کرد.");
            return to_route('changeLogIndex');
        }
        //end change-status
        //----------------------
        $item = null;
        if ($request->action == 'edit') {
            $item = ChangeLog::find($request->item);
        }
        ert('cd');
        $changes = ChangeLog::latest()->paginate(10);
        return view('changeLog.index', compact('changes', 'request', 'item'));
    }

    public function delete(ChangeLog $changeLog)
    {
        $changeLog->delete();
        alert()->info('موفق', "حذف شد.");
        return to_route('changeLogIndex');
    }

    public function store(Request $request)
    {
        if ($request->status == 1) {
            DB::update("UPDATE change_logs SET STATUS=0 where lang='" . app()->getLocale() . "'");
        }
        $request->validate(['text' => 'required']);
        ChangeLog::create([
            'text' => $request->text,
            'status' => $request->status,
            'title' => $request->title,
            'type' => $request->type
        ]);

        alert()->success('موفق', "ثبت شد.");
    }

    public function update(Request $request)
    {
        if ($request->status == 1) {
            DB::update("UPDATE change_logs SET STATUS=0 where lang='" . app()->getLocale() . "'");
        }
        $log = ChangeLog::find($request->item);
        $request->validate(['text' => 'required']);
        $log->update([
            'text' => $request->text,
            'status' => $request->status,
            'title' => $request->title,
            'type' => $request->type
        ]);

        alert()->success('موفق', "ویرایش شد.");
    }
    // run queries
    public function runSql()
    {
        // return false;
        // $accounts = Account::all();
        $account = Account::findOrFail('2');
        // foreach ($accounts  as $account) {

            DB::purge('mysql');
            Config::set('database.connections.mysql', [
                'driver' => 'mysql',
                'host' => 'localhost',
                'database' => $account->id . 'db',
                'username' => 'root',
                'password' => 'v8F7MPy24gFwLizqU05Jqu1l',
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'prefix_indexes' => true,
                'strict' => false,
                'engine' => null,
                'options' => extension_loaded('pdo_mysql') ? array_filter([
                    PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
                ]) : [],
            ]);
            try {
                DB::connection()->getPdo();
                if (DB::connection()->getDatabaseName()) {
                    echo 'ok';
                } else {
                    dump('one');
                    // abort(500, "خطای پیکربندی! لطفا با پشتیبان سیستم تماس بگیرید.");
                }
            } catch (\Exception $e) {
                dump($e->getMessage());
                // abort(500, "خطای پیکربندی! لطفا با پشتیبان سیستم تماس بگیرید. " . $e->getMessage());
            }
            try {
                DB::beginTransaction();
                // DB::statement("
                //    ALTER TABLE `sections` ADD `type` VARCHAR(11) NOT NULL DEFAULT 'waterfall' AFTER `name`;
                // ");

                //To add UUID for table
                DB::statement("ALTER TABLE `people` ADD `uuid` CHAR(36) NULL UNIQUE AFTER `id`");
                DB::statement("UPDATE `people` SET `uuid` = (UUID())");
                
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
                dump($th->getMessage());
            }
        // }
    }
}
