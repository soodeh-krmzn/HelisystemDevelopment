<?php

namespace App\Services;

use PDO;
use Illuminate\Support\Facades\DB;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;

class Database
{
    public function connect($dbConfig)
    {
        DB::purge('useraccount');
        Config::set('database.connections.useraccount', [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => $dbConfig['name'],
            'username' => $dbConfig['user'],
            'password' => $dbConfig['pass'],
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ]);
        //dd( config('database.connections.mysql'));
        try {
            DB::connection()->getPdo();
            if (DB::connection()->getDatabaseName()) {
                return 'ok';
            } else {
                abort(500, "خطای پیکربندی! لطفا با پشتیبان سیستم تماس بگیرید.");
            }
        } catch (\Exception $e) {
            abort(500, "خطای پیکربندی! لطفا با پشتیبان سیستم تماس بگیرید. " . $e->getMessage());
        }
    }
}
