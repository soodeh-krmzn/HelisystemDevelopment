<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\License;
use App\Models\MyModels\Sync;
use App\Models\User;
use Illuminate\Encryption\Encrypter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDO;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use App\Services\Database;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function getData(Request $request)
    {
        $data = $request->all();
        $resp = [];
        foreach ($data as $model) {
            if (isset($model['name'])) {
                $resp[] = ['name' => $model['name']];
            }
        }
        return response()->json(['data' => $resp], 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function checkCode(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'code' => 'required|string|max:255',
            ]);

            $account = Account::where('pc_token', $validatedData['code'])->first();

            if ($account) {
                if ($account->status != 'active') {
                    $desc = $account->description;
                    return response()->json([
                        'message' => $desc ? "حساب کاربری شما غیرفعال می باشد! لطفا با پشتیبانی تماس بگیرید." .  PHP_EOL . "علت: " . $desc
                            : "حساب کاربری شما غیرفعال می باشد! لطفا با پشتیبانی تماس بگیرید."
                    ], 403);
                } else
                    return response()->json([
                        'successful' => $account,
                    ], 200);
            } else {
                return response()->json([
                    'error' => 'کاربری با این مشخصات یافت نشد.',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'خطایی رخ داده است: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function pcCodeKey(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'pc_token' => 'required|string|max:255',
                'system_code' => 'required|string|max:255',
                'username' => 'required|string|max:255',
                'detail' => 'required|string|max:255',                
            ]);

            $pcToken = $validatedData['pc_token'];
            $systemCode = $validatedData['system_code'];
            $username = $validatedData['username'];
            $detail = $validatedData['detail'];

            $licenseData = [
                'pc_token' => $pcToken,
                'system_code' => $systemCode,
                'issued_at' => now(),
                'license_id' => Str::uuid(),
            ];

            $encryptedLicense = Crypt::encryptString(json_encode($licenseData));

            $account = Account::where('pc_token', $pcToken)->first();
            
            $user = User::where('username', $username)->first();
            return response()->json([
                'error' => 'check.',
            ], 404);
            if ($account && $user) {
                if ($user->account_id === $account->id) {
                    $license = License::where('account_id', $account->id)->first();

                    if ($license) {
                        if ($license->user_active != $user->id) {
                            // License is active and used by another user
                            $user_active = User::where('id', $license->user_active)->first();
                            return response()->json([
                                'error' => 'لایسنس توسط کاربر ' . $user_active->username . ' در حال استفاده است.',
                            ], 404);
                        } else
                            return response()->json([
                                'licenseKey' => $license->license,
                                'user' => $user,
                            ], 200);
                    } else {
                        $license = new License();
                        $license->account_id = $account->id;
                        $license->license = $encryptedLicense;
                        $license->system_code = $systemCode;
                        $license->detail = $detail;
                        $license->status = 1;
                        $license->save();

                        return response()->json([
                            'licenseKey' => $encryptedLicense,
                            'user' => $user,
                        ], 200);
                    }
                } else {
                    return response()->json([
                        'error' => 'شماره تماس با حساب مطابقت ندارد.',
                    ], 404);
                }
            } else {
                if (!$account) {
                    return response()->json([
                        'error' => 'توکن صحیح نیست.',
                    ], 404);
                }
                if (!$user) {
                    return response()->json([
                        'error' => 'شماره تماس صحیح نیست.',
                    ], 404);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'خطایی رخ داده است: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function verifyLicense(Request $request)
    {
        $validatedData = $request->validate([
            'pc_token' => 'required|string',
            'licenseKey' => 'required|string',
            'system_code' => 'required|string|max:255',
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        $inputPcToken = $validatedData['pc_token'];
        $inputLicenseKey = $validatedData['licenseKey'];
        $inputSystemCode = $validatedData['system_code'];
        $inputUsername = $validatedData['username'];
        $inputPassword = $validatedData['password'];

        try {
            $user = User::where('username', $inputUsername)->first();
            if (!$user) {
                return response()->json([
                    'error' => 'کاربر یافت نشد.',
                ], 404);
            }
            if (!Hash::check($inputPassword, $user->password)) {
                return response()->json([
                    'error' => 'رمز عبور اشتباه است.',
                ], 401);
            }

            $license = License::where('license', $inputLicenseKey)->first();
            if (!$license) {
                return response()->json([
                    'error' => 'لایسنس معتبر نیست.',
                ], 404);
            }
            $account = $license->account;


            if ($account && $user) {
                if ($user->account_id === $account->id) {
                    if (!$license->is_active) {
                        // Activate the license with the current user
                        $license->is_active = true;
                        $license->user_active = $user->id;
                        $license->save();
                    } elseif ($license->user_active != $user->id) {
                        // License is active and used by another user
                        $user_active = User::where('id', $license->user_active)->first();
                        return response()->json([
                            'error' => 'لایسنس توسط کاربر ' . $user_active->username . ' در حال استفاده است.',
                        ], 404);
                    }
                } else {
                    return response()->json([
                        'error' => 'مجوز عبور شما مطابق با حساب شما نیست.',
                    ], 404);
                }
            } else {
                if (!$account) {
                    return response()->json([
                        'error' => 'لایسنس صحیح نیست.',
                    ], 404);
                }
                if (!$user) {
                    return response()->json([
                        'error' => 'کاربری با این مشخصات یافت نشد.',
                    ], 404);
                }
            }

            $licenseData = json_decode(Crypt::decryptString($inputLicenseKey), true);
            if ($licenseData['system_code'] !== $inputSystemCode) {
                return response()->json([
                    'error' => 'سیستم شما مطابق با مجوز ثبت شده نیست.',
                ], 403);
            }

            if ($account->status != 'active') {
                $desc = $account->description;
                return response()->json([
                    'message' => $desc
                        ? "حساب کاربری شما غیرفعال می باشد! لطفا با پشتیبانی تماس بگیرید." . PHP_EOL . "علت: " . $desc
                        : "حساب کاربری شما غیرفعال می باشد! لطفا با پشتیبانی تماس بگیرید."
                ], 403);
            }

            return response()->json([
                'user' => $user,
                'license' => $license->license,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'خطایی رخ داده است: ' . $e->getMessage(),
            ], 400);
        }
    }

    public function checkUserKey(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_key' => 'required|string|max:255',
            ]);

            $user = User::where('user_key', $validatedData['user_key'])->first();
            // $account = Account::where('account_id' , $user->id)->select();
            if ($user) {
                return response()->json([
                    'user' => $user,
                    // 'account' =>$account,
                ], 200);
            } else {
                return response()->json([
                    'error' => 'کد وارد شده معتبر نیست.'
                ], 403);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'خطایی رخ داده است: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function decrypt($db_name, $db_user, $db_pass)
    {
        $key = base64_decode(Config::get('app.custom_key'));
        $encrypter = new Encrypter($key, Config::get('app.cipher'));
        $name = $encrypter->decryptString($db_name);
        $user = $encrypter->decryptString($db_user);
        $pass = $encrypter->decryptString($db_pass);

        return compact('name', 'user', 'pass');
    }

    private function account($account)
    {
        $db_name = $account->db_name;
        $db_user = $account->db_user;
        $db_pass = $account->db_pass;

        $decrypted = $this->decrypt($db_name, $db_user, $db_pass);
        DB::purge('useraccount');
        Config::set('database.connections.useraccount', [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => $decrypted['name'],
            'username' => $decrypted['user'],
            'password' => $decrypted['pass'],
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
    }
    public function connectdb(Request $request)
    {
        $userId = $request['userId'];
        $account = Account::where('id', $userId)->first();
        if (!$account) {
            return response()->json(['error' => 'Account not found'], 404);
        }
        $this->account($account);
        try {
            $tables = DB::connection('useraccount')->select('SHOW TABLES');
            return response()->json($tables);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Database connection failed: ' . $e->getMessage()], 500);
        }
    }

    public function collectData(Request $request)
    {
        $tableName = $request['tableName'];
        $offset = (int)$request['offset'];
        $ppp = (int)$request['ppp'];
        $accountId = $request['accountId'];
        $account = Account::findOrFail($accountId);

        if (!$account) {
            return response()->json(['error' => 'Account not found'], 404);
        }

        $this->account($account);

        try {
            $query = "SELECT * FROM {$tableName} LIMIT ? OFFSET ?";
            $data = DB::connection('useraccount')->select($query, [$ppp, $offset]);
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Database connection failed: ' . $e->getMessage()], 500);
        }
    }

    // public function collectAdminData(Request $request)
    // {
    //     $tableName = $request['tableName'];
    //     $offset = (int)$request['offset'];
    //     $ppp = (int)$request['ppp'];
    //     $accountId = $request['accountId'];
    //     $account = Account::findOrFail($accountId);

    //     if (!$account) {
    //         return response()->json(['error' => 'Account not found'], 404);
    //     }

    //     DB::purge('mysql');
    //     Config::set('database.connections.mysql', [
    //         'driver' => 'mysql',
    //         'host' => 'localhost',
    //         'database' => 'helisystem.db',
    //         'username' => 'Heli_dbUser',
    //         'password' => 'by(tUETH@by(tUETH@',
    //         'charset' => 'utf8mb4',
    //         'collation' => 'utf8mb4_unicode_ci',
    //         'prefix' => '',
    //         'prefix_indexes' => true,
    //         'strict' => false,
    //         'engine' => null,
    //         'options' => extension_loaded('pdo_mysql') ? array_filter([
    //             PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
    //         ]) : [],
    //     ]);

    //     try {
    //         if ($tableName == 'users') {
    //             $query = "SELECT * FROM {$tableName} WHERE account_id = ? LIMIT ? OFFSET ?";
    //             $data = DB::connection('mysql')->select($query, [$accountId, $ppp, $offset]);
    //         } else {
    //             $query = "SELECT * FROM {$tableName} LIMIT ? OFFSET ?";
    //             $data = DB::connection('mysql')->select($query, [$ppp, $offset]);
    //         }
    //         return response()->json($data);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'Database connection failed: ' . $e->getMessage()], 500);
    //     }
    // }


    public function collectAdminData(Request $request)
    {
        $tableName = $request->input('tableName');
        $offset = (int)$request->input('offset', 0);
        $ppp = (int)$request->input('ppp', 50);
        $accountId = $request->input('accountId');

        $account = Account::find($accountId);
        if (!$account) {
            return response()->json(['error' => 'Account not found'], 404);
        }

        try {
            $query = DB::table($tableName);

            if ($tableName === 'users') {
                $query->select('id', 'account_id', 'name', 'family', 'mobile', 'username', 'status', 'description', 'otp_code', 'access', 'user_key', 'group_id', 'remember_token', 'created_at', 'updated_at', 'deleted_at')->where('account_id', $accountId);
            }

            $data = $query->limit($ppp)->offset($offset)->get();

            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Database query failed: ' . $e->getMessage());
            return response()->json(['error' => 'Database connection failed'], 500);
        }
    }

    public function syncDataTable(Request $request)
    {
        $tableName = 'sync';
        $accountId = $request['accountId'];
        $account = Account::findOrFail($accountId);

        if (!$account) {
            return response()->json(['error' => 'Account not found'], 404);
        }

        try {
            $this->account($account);
            $query = "SELECT * FROM {$tableName} WHERE status = 0 ";
            $data = DB::connection('useraccount')->select($query);
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Database connection failed from connection: ' . $e->getMessage()], 500);
        }
    }

    public function dataRecordCollect(Request $request)
    {
        $tableName = $request['tableName'];
        $id = (int)$request['id'];
        $accountId = $request['accountId'];
        $account = Account::findOrFail($accountId);

        if (!$account) {
            return response()->json(['error' => 'Account not found'], 404);
        }

        $this->account($account);

        try {
            // if ($tableName === 'games') {
            //     $query = "SELECT * FROM {$tableName} WHERE id = ? AND status = 0 AND deleted_at IS NULL";
            //     $data = DB::connection('useraccount')->select($query, [$id]);
            // } else {
            //     $query = "SELECT * FROM {$tableName} WHERE id = ?";
            //     $data = DB::connection('useraccount')->select($query, [$id]);
            // }
            $query = "SELECT * FROM {$tableName} WHERE id = ?";
            $data = DB::connection('useraccount')->select($query, [$id]);
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Database connection failed: ' . $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request)
    {
        $status = $request['status'];
        $id = (int)$request['id'];
        $accountId = $request['accountId'];
        $account = Account::findOrFail($accountId);

        if (!$account) {
            return response()->json(['error' => 'Account not found'], 404);
        }

        $this->account($account);

        try {
            // $record = Sync::findOrFail($request->id);
            $record = DB::connection('useraccount')->table('sync')->where('id', $request->id)->first();
            DB::connection('useraccount')->table('sync')->where('id', $id)->update([
                'status' => $status,
                'updated_at' => now()
            ]);
            // $record->status = $request->status;
            // $record->save();

            return response()->json([
                'message' => 'Sync status updated successfully.',
                'data' => [
                    'id' => $record->id,
                    'status' => $record->status
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update status.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // public function fetchUnsyncedRecords()
    // {
    //     return Sync::where('status', 0)->with(['model'])->get();
    // }

    // public function markAsSynced(Request $request)
    // {
    //     Sync::where('uuid', $request->uuid)->update(['status' => 1]);
    // }

    // public function storeRecord(Request $request)
    // {
    //     $syncService = new SyncService();
    //     $syncService->storeRecordLocally(
    //         $request->model_name,
    //         $request->uuid,
    //         $request->data,
    //         $request->relationships
    //     );
    //     Sync::updateOrCreate(['uuid' => $request->uuid], ['status' => 1]);
    // }


    //SYNC FROM OFFLINE TO ONLINE
    public function getTableNameFromModel($model)
    {
        if ($model == 'َProductCaregory')
            return 'product_category';
        if (class_exists($model)) {
            return (new $model())->getTable();
        }
        return null;
    }

    public function storeSyncData(Request $request)
    {
        $accountId = $request['accountId'];
        $account = Account::findOrFail($accountId);

        if (!$account) {
            return response()->json(['error' => 'Account not found'], 404);
        }
        $this->account($account);
        try {

            $modelFullClassName = $request['model_name'];
            $modelName = basename(str_replace('\\', '/', $modelFullClassName));
            $modelClass = "App\\Models\\Sync\\{$modelName}";

            if (!class_exists($modelClass)) {
                return response()->json(['error' => 'Invalid model name'], 400);
            }

            $decrypted = $this->decrypt($account->db_name, $account->db_user, $account->db_pass);
            $dbConfig = [
                'name' => $decrypted['name'],
                'user' => $decrypted['user'],
                'pass' => $decrypted['pass'],
            ];

            $database = new Database();
            $database->connect($dbConfig);

            $modelInstance = new $modelClass;
            $modelInstance->setConnection('useraccount');

            $data = $request['data'];
            $id = $request['m_id'] ?? null;
            $uuid = $request['m_uuid'] ?? null;
            $createdAt = $data['created_at'] ?? null;
            $updatedAt = $data['updated_at'] ?? null;

            if ($uuid) {
                $existingRecord = $modelInstance->where('uuid', $uuid)->first();
            } else
                $existingRecord = $modelInstance->find($id);


            if ($existingRecord) {
                unset($data['id']);
                switch ($modelClass) {
                    case 'App\\Models\\Sync\\Factor':
                        $existingRecord = $this->syncFactor($existingRecord, $request);
                        unset($data['person_id'], $data['game_id']);
                        break;

                    case 'App\\Models\\Sync\\FactorBody':
                        $existingRecord = $this->syncFactorBody($existingRecord, $request);
                        unset($data['factor_id'], $data['product_id']);
                        break;

                    case 'App\\Models\\Sync\\Game':
                        $existingRecord = $this->syncGame($existingRecord, $request);
                        unset($data['person_id'], $data['game_id']);
                        break;

                    case 'App\\Models\\Sync\\GameMeta':
                        $existingRecord = $this->syncGameMeta($existingRecord, $request);
                        unset($data['g_id']);
                        break;

                    case 'App\\Models\\Sync\\Payment':
                        $existingRecord = $this->syncPayment($existingRecord, $request);
                        unset($data['person_id']);
                        break;

                        // case 'App\\Models\\Sync\\Person':
                        //     $existingRecord = $this->syncPerson($existingRecord, $request);
                        //     break;
                    case 'App\\Models\\Sync\\PersonMeta':
                        $existingRecord = $this->syncPersonMeta($existingRecord, $request);
                        unset($data['person_id']);
                        break;
                }

                $existingRecord->timestamps = false;
                $existingRecord->fill($data);
                if ($createdAt) $existingRecord->created_at = $createdAt;
                if ($updatedAt) $existingRecord->updated_at = $updatedAt;
                $existingRecord->save();

                return response()->json([
                    'message' => 'Record synced updated',
                    'data' => $existingRecord->toArray(),
                ], 200);
            } else {
                $newRecord = new $modelClass($data);
                switch ($modelClass) {
                    case 'App\\Models\\Sync\\Factor':
                        $newRecord = $this->syncFactor($newRecord, $request);
                        break;

                    case 'App\\Models\\Sync\\FactorBody':
                        $newRecord = $this->syncFactorBody($newRecord, $request);
                        unset($data['product_id']);
                        break;

                    case 'App\\Models\\Sync\\Game':
                        $newRecord = $this->syncGame($newRecord, $request);
                        break;

                    case 'App\\Models\\Sync\\GameMeta':
                        $newRecord = $this->syncGameMeta($newRecord, $request);
                        break;

                    case 'App\\Models\\Sync\\Payment':
                        $newRecord = $this->syncPayment($newRecord, $request);
                        break;
                    case 'App\\Models\\Sync\\PersonMeta':
                        $newRecord = $this->syncPersonMeta($newRecord, $request);
                        break;
                }

                $newRecord->timestamps = false;
                if ($createdAt) $newRecord->created_at = $createdAt;
                if ($updatedAt) $newRecord->updated_at = $updatedAt;
                $newRecord->save();

                return response()->json(['message' => 'Record synced successfully model name->' . $modelClass, 'data' => $newRecord->toArray()], 200);
            }
        } catch (\Exception $e) {
            Log::error("Sync failed: " . $e->getMessage());
            return response()->json(['error' => 'Failed to sync data' . $e->getMessage()], 500);
        }
    }

    public function syncFactor($record, $request)
    {
        if (isset($request->includes['Person'])) {
            $personData = $request->includes['Person'];
            $personModel = "App\\Models\\Sync\\Person";

            $personInstance = $personModel::on('useraccount')->where('uuid', $personData['uuid'])->first();

            if ($personInstance) {
                $personInstance->timestamps = false;
                $personInstance->fill($personData);
                if (isset($personData['created_at'])) {
                    $personInstance->created_at = $personData['created_at'];
                }
                if (isset($personData['updated_at'])) {
                    $personInstance->updated_at = $personData['updated_at'];
                }
                $personInstance->save();
            } else {
                $personInstance = $personModel::on('useraccount')->create($personData);
            }

            $record->person_id = $personInstance->id;
        }

        if (isset($request->includes['Game'])) {
            $gameData = $request->includes['Game'];
            $gameModel = "App\\Models\\Sync\\Game";

            // Set person_id in Game data if Person exists
            if ($personInstance) {
                $gameData['person_id'] = $personInstance->id;
            }

            $gameInstance = $gameModel::on('useraccount')->where('uuid', $gameData['uuid'])->first();

            if ($gameInstance) {
                $gameInstance->timestamps = false;
                $gameInstance->fill($gameData);
                if (isset($gameData['created_at'])) {
                    $gameInstance->created_at = $gameData['created_at'];
                }
                if (isset($gameData['updated_at'])) {
                    $gameInstance->updated_at = $gameData['updated_at'];
                }
                $gameInstance->save();
            } else {
                $gameInstance = $gameModel::on('useraccount')->create($gameData);
            }

            $record->game_id = $gameInstance->id;
        }
        // if (isset($request->includes['FactorBody'])) {
        //     foreach ($request->includes['FactorBody'] as &$body) {
        //         $this->syncFactorBody($record, $body);
        //     }
        // }

        return $record;
    }

    public function syncPayment($record, $request)
    {
        if (isset($request->includes['Person'])) {
            $personData = $request->includes['Person'];
            $personModel = "App\\Models\\Sync\\Person";

            $personInstance = $personModel::on('useraccount')->where('uuid', $personData['uuid'])->first();

            if ($personInstance) {
                $personInstance->timestamps = false;
                $personInstance->fill($personData);
                if (isset($personData['created_at'])) {
                    $personInstance->created_at = $personData['created_at'];
                }
                if (isset($personData['updated_at'])) {
                    $personInstance->updated_at = $personData['updated_at'];
                }
                $personInstance->save();
            } else {
                $personInstance = $personModel::on('useraccount')->create($personData);
            }
            $record->person_id = $personInstance->id;
        }
        // if (isset($request->includes['FactorBody'])) {
        //     foreach ($request->includes['FactorBody'] as &$body) {
        //         $this->syncFactorBody($record, $body);
        //     }
        // }

        return $record;
    }

    public function syncGameMeta($record, $request)
    {
        if (isset($request->includes['Game'])) {
            $gameData = $request->includes['Game'];
            $gameModel = "App\\Models\\Sync\\Game";

            $gameInstance = $gameModel::on('useraccount')->withTrashed()->where('uuid', $gameData['uuid'])->first();
            if ($gameInstance) {
                $gameInstance->timestamps = false;
                $gameInstance->fill($gameData);
                if (isset($gameData['created_at'])) {
                    $gameInstance->created_at = $gameData['created_at'];
                }
                if (isset($gameData['updated_at'])) {
                    $gameInstance->updated_at = $gameData['updated_at'];
                }
                $gameInstance->save();
            } else {
                $gameInstance = $gameModel::on('useraccount')->create($gameData);
            }
            $record->g_id = $gameInstance->id;
        }
        // if (isset($request->includes['FactorBody'])) {
        //     foreach ($request->includes['FactorBody'] as &$body) {
        //         $this->syncFactorBody($record, $body);
        //     }
        // }

        return $record;
    }

    public function syncGame($record, $request)
    {
        if (isset($request->includes['Person'])) {
            $personData = $request->includes['Person'];
            $personModel = "App\\Models\\Sync\\Person";

            $personInstance = $personModel::on('useraccount')->withTrashed()->where('uuid', $personData['uuid'])->first();
            if ($personInstance) {
                $personInstance->timestamps = false;
                $personInstance->fill($personData);
                if (isset($personData['created_at'])) {
                    $personInstance->created_at = $personData['created_at'];
                }
                if (isset($personData['updated_at'])) {
                    $personInstance->updated_at = $personData['updated_at'];
                }
                $personInstance->save();
            } else {
                $personInstance = $personModel::on('useraccount')->create($personData);
            }
            $record->person_id = $personInstance->id;
        }
        // if (isset($request->includes['FactorBody'])) {
        //     foreach ($request->includes['FactorBody'] as &$body) {
        //         $this->syncFactorBody($record, $body);
        //     }
        // }

        return $record;
    }

    public function syncFactorBody($record, $request)
    {
        $factorBodyData = $request['data'];
        // if (isset($request->includes['Product'])) {
        //     $productData = $request->includes['Product'];
        //     $productModel = "App\\Models\\Sync\\Product";

        //     $productInstance = $productModel::on('useraccount')->where('id', $productData['id'])->first();

        //     if ($productInstance) {
        //         $productInstance->timestamps = false;
        //         $productInstance->fill($productData);
        //         if (isset($productData['created_at'])) {
        //             $productInstance->created_at = $productData['created_at'];
        //         }
        //         if (isset($productData['updated_at'])) {
        //             $productInstance->updated_at = $productData['updated_at'];
        //         }
        //         $productInstance->save();
        //     } else {
        //         $productInstance = $productModel::on('useraccount')->create($productData);
        //     }

        //     $record->product_id = $productInstance->id;
        // }

        if (isset($request['includes']['Product'])) {
            $productData = $request['includes']['Product'];
            $productModel = "App\\Models\\Sync\\Product";

            $productInstance = $productModel::on('useraccount')->where('uuid', $productData['uuid'])->first();
            if ($productInstance) {
                $productInstance->timestamps = false;
                $productInstance->fill($productData);
                if (isset($productData['created_at'])) {
                    $productInstance->created_at = $productData['created_at'];
                }
                if (isset($productData['updated_at'])) {
                    $productInstance->updated_at = $productData['updated_at'];
                }
                $productInstance->save();
            } else {
                ##آیا همیشه پروداکت وجود داره و به اینجا برای ساخت محصول نمیرسه؟
                $productInstance = $productModel::on('useraccount')->create($productData);
            }

            $factorBodyData['product_id'] = $productInstance->id;
        }

        if (isset($request->includes['Factor'])) {
            $factorData = $request->includes['Factor'];
            $factorModel = "App\\Models\\Sync\\Factor";

            $factorInstance = $factorModel::on('useraccount')->where('uuid', $factorData['uuid'])->first();

            // if ($factorInstance) {
            //     $factorInstance->timestamps = false;
            //     $factorInstance->fill($factorData);
            //     if (isset($factorData['created_at'])) {
            //         $factorInstance->created_at = $factorData['created_at'];
            //     }
            //     if (isset($factorData['updated_at'])) {
            //         $factorInstance->updated_at = $factorData['updated_at'];
            //     }
            //     $factorInstance->save();
            // } else {
            //     $factorInstance = $factorModel::on('useraccount')->create($factorData);
            // }
            if ($factorInstance) {
                $factorBodyData['factor_id'] = $factorInstance->id;
            } else {
                // Log or ignore if Factor doesn't exist
                Log::warning("Factor not found: " . $factorData['uuid']);
            }

            $record->factor_id = $factorInstance->id;
        }

        return $record;
    }

    public function syncPerson($record, $request)
    {
        $personModel = "App\\Models\\Sync\\Person";

        if (!class_exists($personModel)) {
            throw new \Exception("Person model not found");
        }

        $personInstance = $personModel::where('uuid', $record['uuid'])->first();

        if (!$personInstance) {
            $personInstance = $personModel::create($record);
        }

        return $personInstance;
    }

    public function syncGameEntity($gameData)
    {
        $gameModel = "App\\Models\\Sync\\Game";

        if (!class_exists($gameModel)) {
            throw new \Exception("Game model not found");
        }

        $gameInstance = $gameModel::where('uuid', $gameData['uuid'])->first();

        if (!$gameInstance) {
            $gameInstance = $gameModel::create($gameData);
        }

        return $gameInstance;
    }

    public function syncPersonMeta($record, $request)
    {
        if (isset($request->includes['Person'])) {
            $personData = $request->includes['Person'];
            $personModel = "App\\Models\\Sync\\Person";

            $personInstance = $personModel::on('useraccount')->where('uuid', $personData['uuid'])->first();

            if ($personInstance) {
                $personInstance->timestamps = false;
                $personInstance->fill($personData);
                if (isset($personData['created_at'])) {
                    $personInstance->created_at = $personData['created_at'];
                }
                if (isset($personData['updated_at'])) {
                    $personInstance->updated_at = $personData['updated_at'];
                }
                $personInstance->save();
            } else {
                $personInstance = $personModel::on('useraccount')->create($personData);
            }

            $record->p_id = $personInstance->id;
        }

        return $record;
    }

    public function checkLicenseActivaation(Request $request)
    {
        $validatedData = $request->validate([
            'license' => 'required|string',
            'username' => 'required|string',
        ]);

        $inputLicense = $validatedData['license'];
        $inputUsername = $validatedData['username'];
        $user = User::where('username', $inputUsername)->first();
        if (!$user) {
            return response()->json([
                'error' => 'کاربری با این نام کاربری یافت نشد.',
            ], 404);
        }
        try {
            $license = License::where('license', $inputLicense)->first();
            if ($license) {
                if ($license->user_active != $user->id) {
                    return response()->json([
                        'error' => 'مجوز عبور وارد شده برای این کاربر فعال نیست.',
                    ], 403);
                }
                if ($license->is_active == 1) {
                    return response()->json([
                        'message' => 'لایسنس وارد شده فعال است.',
                    ], 200);
                } else {
                    return response()->json([
                        'message' => 'لایسنس وارد شده غیرفعال است.',
                    ], 200);
                }
            } else {
                return response()->json([
                    'error' => 'لایسنس وارد شده معتبر نیست.',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'خطایی رخ داده است: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function deactiveLicense(Request $request)
    {
        $validatedData = $request->validate([
            'license' => 'required|string',
            'username' => 'required|string',
        ]);

        $inputLicense = $validatedData['license'];
        $inputUsername = $validatedData['username'];
        $user = User::where('username', $inputUsername)->first();
        if (!$user) {
            return response()->json([
                'error' => 'کاربری با این نام کاربری یافت نشد.',
            ], 404);
        }
        try {
            $license = License::where('license', $inputLicense)->first();
            if ($license) {
                if ($license->is_active == 0) {
                    return response()->json([
                        'error' => 'لایسنس وارد شده غیرفعال است.',
                    ], 404);
                }
                if ($license->user_active != $user->id) {
                    return response()->json([
                        'error' => 'مجوز عبور وارد شده برای این کاربر فعال نیست.',
                    ], 404);
                }
                $license->is_active = 0;
                $license->user_active = 0;
                $license->save();
                return response()->json([
                    'message' => 'لایسنس غیرفعال شد.',
                ], 200);
            } else {
                return response()->json([
                    'error' => 'لایسنس وارد شده معتبر نیست.',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'خطایی رخ داده است: ' . $e->getMessage(),
            ], 500);
        }
    }
    // public function storeSyncData(Request $request)
    // {
    //     $validated = $request->validate([
    //         'model_name' => 'required|string',
    //         'id' => 'required|integer',
    //         'data' => 'required|array',
    //         // 'accountId' => 'required|integer',
    //     ]);

    //     // Fetch account details
    //     $accountId = 3;
    //     $account = Account::find($accountId);

    //     if (!$account) {
    //         return response()->json(['error' => 'Account not found'], 404);
    //     }

    //     DB::purge('useraccount');
    //     Config::set('database.connections.useraccount', [
    //         'driver' => 'mysql',
    //         'host' => 'localhost',
    //         'database' => '3db',
    //         'username' => '3user',
    //         'password' => 'jVHRfOQnDQ3v',
    //         'charset' => 'utf8mb4',
    //         'collation' => 'utf8mb4_unicode_ci',
    //         'prefix' => '',
    //         'strict' => false,
    //         'engine' => null,
    //         'options' => extension_loaded('pdo_mysql') ? array_filter([
    //             PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
    //         ]) : [],
    //     ]);

    //     try {


    //         try {
    //             $model = $this->getTableNameFromModel($request['model_name']);
    //             $modelClass = $request['model_name'];
    //             $id = $request['id'];
    //             $data = $request['data'];

    //             $existingRecord = ($model === 'Person')
    //                 ? $modelClass::withTrashed()->where('uuid', $id)->first()
    //                 : $modelClass::withTrashed()->find($id);

    //             $createdAt = $data['created_at'] ?? null;
    //             $updatedAt = $data['updated_at'] ?? null;
    //             $modelClass::withoutSyncing(function () use ($existingRecord, $modelClass, $data, $createdAt, $updatedAt) {
    //                 if ($existingRecord) {
    //                     $existingRecord->timestamps = false;
    //                     unset($data['id']);

    //                     $existingRecord->fill($data);
    //                     if ($createdAt) $existingRecord->created_at = $createdAt;
    //                     if ($updatedAt) $existingRecord->updated_at = $updatedAt;
    //                     $existingRecord->save();
    //                 } else {
    //                     $newRecord = new $modelClass($data);
    //                     $newRecord->timestamps = false;

    //                     if ($createdAt) $newRecord->created_at = $createdAt;
    //                     if ($updatedAt) $newRecord->updated_at = $updatedAt;

    //                     $newRecord->save();
    //                 }
    //             });
    //         } catch (Exception $e) {
    //             return response()->json(['error' => 'Failed to store record: ' . $e->getMessage()], 500);
    //         }





    //         // Find or insert the data into the specific table in the account's database
    //         $modelClass = $validated['model_name'];

    //         if (!class_exists($modelClass)) {
    //             return response()->json(['error' => 'Invalid model name'], 400);
    //         }

    //         // Use the `useraccount` connection for this model
    //         $modelInstance = (new $modelClass)->setConnection('useraccount');

    //         $existingRecord = $modelInstance->find($validated['id']);

    //         if ($existingRecord) {
    //             // Update the existing record
    //             $existingRecord->update($validated['data']);
    //         } else {
    //             // Insert a new record
    //             $modelInstance->create(array_merge($validated['data'], ['id' => $validated['id']]));
    //         }

    //         return response()->json(['message' => 'Record synced successfully'], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'Failed to sync data: ' . $e->getMessage()], 500);
    //     }
    // }



    // public function storeSyncData(Request $request)
    // {
    //     $validated = $request->validate([
    //         'model_name' => 'required|string',
    //         'm_uuid' => 'nullable|string',
    //         'data' => 'required|array',
    //         'id' => 'required|integer',
    //     ]);

    //     try {
    //         $modelClass = $validated['model_name']; // Example: App\Models\YourModel
    //         if (!class_exists($modelClass)) {
    //             return response()->json(['error' => 'Invalid model name'], 400);
    //         }

    //         $data = $validated['data'];
    //         $id = $validated['id'];
    //         $uuid = $validated['m_uuid'] ?? null;

    //         // Fetch the existing record using 'id'.
    //         $existingRecord = $modelClass::find($id);

    //         if ($existingRecord) {
    //             // Update the existing record.
    //             $existingRecord->update($data);
    //         } else {
    //             // Create a new record and ensure the 'id' is set explicitly.
    //             $modelClass::create(array_merge($data, ['id' => $id]));
    //         }

    //         return response()->json(['message' => 'Record synced successfully'], 200);
    //     } catch (\Exception $e) {
    //         Log::error('Error syncing data: ' . $e->getMessage());
    //         return response()->json(['error' => 'Failed to sync data'], 500);
    //     }
    // }
}
