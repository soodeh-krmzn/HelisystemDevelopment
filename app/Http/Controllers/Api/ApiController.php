<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\License;
use App\Models\Sync\Setting;
use App\Models\User;
use Exception;
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
use DateTime;
use Carbon\Carbon;


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

    private function checkAccountCharge(Account $account)
    {
        $expire_charge = new DateTime(Carbon::parse($account->charge_date)->addDays($account->days)->format('Y-m-d'));
        $today = new DateTime();
        $diff = $today->diff($expire_charge, $absolute = false)->format('%R%a');
        if ($diff <= 0)
            return false;
        else
            return true;
    }

    public function checkSetupIsDone(Request $request)
    {
        $checkResponse = $this->checkRequest($request);

        if ($checkResponse->getStatusCode() !== 200) {
            return $checkResponse;
        }

        $account = Account::findOrFail($request['accountId']);

        if (!$account) {
            return response()->json(['error' => 'Account not found'], 404);
        }
        $this->account($account);
        try {
            $databaseName = DB::connection('useraccount')->getDatabaseName();

            $tableCount = DB::connection('useraccount')->selectOne("
                SELECT COUNT(*) AS table_count
                FROM information_schema.tables
                WHERE table_schema = ?
            ", [$databaseName])->table_count;

            if ($tableCount === 0) {
                return response()->json([
                    'error' => 'راه اندازی نرم افزار آنلاین انجام نشده است، از طریق سایت اقدام کنید.'
                ], 403);
            }

            return response()->json(['success' => 'Setup is complete']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Database connection failed: ' . $e->getMessage()], 500);
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

            if ($account && $user) {
                if ($user->account_id === $account->id) {
                    $license = License::where('account_id', $account->id)->first();

                    if ($license) {
                        $licenseData = json_decode(Crypt::decryptString($license->license), true);
                        if ($licenseData['system_code'] !== $systemCode) {
                            return response()->json([
                                'error' => 'سیستم شما مطابق با مجوز ثبت شده نیست.',
                            ], 403);
                        }
                        if ($user->status != 'active') {
                            $desc = $user->description;
                            return response()->json([
                                'error' => $desc ? "حساب کاربری شما غیرفعال می باشد! لطفا با پشتیبانی تماس بگیرید." .  PHP_EOL . "علت: " . $desc
                                    : "حساب کاربری شما غیرفعال می باشد! لطفا با پشتیبانی تماس بگیرید."
                            ], 403);
                        }
                        $account = $user->account;
                        if ($account->status != 'active') {
                            $desc = $account->status_detail;
                            return response()->json([
                                'error' => $desc ? "اشتراک شما غیرفعال می باشد! لطفا با پشتیبانی تماس بگیرید." .  PHP_EOL . "علت: " . $desc
                                    : "اشتراک شما غیرفعال می باشد! لطفا با پشتیبانی تماس بگیرید."
                            ], 403);
                        }

                        if ($license->user_active != $user->id && $license->user_active != 0) {
                            // License is active and used by another user
                            $user_active = User::where('id', $license->user_active)->first();
                            return response()->json([
                                'error' => 'مجوز عبور توسط کاربر ' . $user_active->username . ' در حال استفاده است.',
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
                        'error' => 'کلید وارد شده برای این کاربر معتبر نیست.',
                    ], 404);
                }
            } else {
                if ($user) {
                    $userAccount = Account::where('id', $user->account_id)->first();
                    if ($userAccount->pc_token == null) {
                        return response()->json([
                            'error' => 'کلید عبور برای حساب شما تعریف نشده است، با پشتیبانی تماس بگیرید.',
                        ], 404);
                    }
                }
                if (!$account) {
                    return response()->json([
                        'error' => 'کلید وارد شده در لیست مشترکین یافت نشد .',
                    ], 404);
                }
                if (!$user) {
                    return response()->json([
                        'error' => 'نام کاربری وارد شده معتبر نیست.',
                    ], 404);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'خطایی رخ داده است: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function loginCheck(Request $request)
    {
        $validatedData = $request->validate([
            'pc_token' => 'nullable|string',
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
            if (!$inputPcToken) {
                return response()->json([
                    'error' => 'کاربر یافت نشد.',
                ], 404);
            }
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

            if ($user->access == 2) {
                return response()->json([
                    'error' => __('عدم دسترسی! لطفا با پشتیبانی مجموعه تماس بگیرید.'),
                ], 403);
            }

            if ($account && $user) {
                if ($user->account_id === $account->id) {
                    if ($user->status != 'active') {
                        $desc = $user->description;
                        return response()->json([
                            'error' => $desc ? "حساب کاربری شما غیرفعال می باشد! لطفا با پشتیبانی تماس بگیرید." .  PHP_EOL . "علت: " . $desc
                                : "حساب کاربری شما غیرفعال می باشد! لطفا با پشتیبانی تماس بگیرید."
                        ], 403);
                    }

                    if ($account->status != 'active') {
                        $desc = $account->description;
                        return response()->json([
                            'error' => $desc
                                ? "حساب کاربری شما غیرفعال می باشد! لطفا با پشتیبانی تماس بگیرید." . PHP_EOL . "علت: " . $desc
                                : "حساب کاربری شما غیرفعال می باشد! لطفا با پشتیبانی تماس بگیرید."
                        ], 403);
                    }

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

    public function checkRequest(Request $request)
    {

        try {
            $validatedData = $request->validate([
                'license' => 'required|string',
                'systemCode' => 'required|string',
                'accountId' => 'required',
                'username' => 'required'
            ]);

            $user = User::where('username', $validatedData['username'])->first();
            $account = Account::findOrFail($validatedData['accountId']);
            $license = License::where('license', $validatedData['license'])->first();

            if (!$user) {
                return response()->json([
                    'error' => 'کاربر یافت نشد.',
                ], 404);
            }
            if (!$license) {
                return response()->json([
                    'error' => 'لایسنس معتبر نیست.',
                ], 404);
            }

            $licenseAccount = $license->account;
            if ($licenseAccount->id != $account->id) {
                return response()->json([
                    'error' => 'حساب کاربری مطابق با مجوز عبور نیست.',
                ], 404);
            }

            if ($licenseAccount && $user) {
                if ($user->account_id === $licenseAccount->id) {
                    if ($license->user_active != $user->id) {
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

            $licenseData = json_decode(Crypt::decryptString($validatedData['license']), true);
            if ($licenseData['system_code'] !== $validatedData['systemCode']) {
                return response()->json([
                    'error' => 'سیستم شما مطابق با مجوز ثبت شده نیست.',
                ], 403);
            }

            if ($account->status != 'active') {
                $desc = $account->description;
                return response()->json([
                    'error' => $desc
                        ? "حساب کاربری شما غیرفعال می باشد! لطفا با پشتیبانی تماس بگیرید." . PHP_EOL . "علت: " . $desc
                        : "حساب کاربری شما غیرفعال می باشد! لطفا با پشتیبانی تماس بگیرید."
                ], 403);
            }

            return response()->json(200);
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
        $accountId = $request->input('accountId');

        $account = Account::find($accountId);
        if (!$account) {
            return response()->json(['error' => 'Account not found'], 404);
        }

        if (!$this->checkAccountCharge($account)) {
            return response()->json([
                'error' => __('کاربر گرامی شارژ اشتراک شما به پایان رسیده است.'),
            ], 403);
        }

        $checkResponse = $this->checkRequest($request);

        if ($checkResponse->getStatusCode() !== 200) {
            return $checkResponse;
        }

        try {
            $this->account($account);

            $query = "SELECT * FROM {$tableName} WHERE status = 0 ";
            $data = DB::connection('useraccount')->select($query);

            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Database connection failed: ' . $e->getMessage()], 500);
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
            if ($tableName === 'users') {
                $query = "SELECT * FROM {$tableName} WHERE id = ? AND status = 0 AND deleted_at IS NULL";
                $data = DB::connection('mysql')->select($query, [$id]);
            } else {
                $query = "SELECT * FROM {$tableName} WHERE id = ?";
                $data = DB::connection('useraccount')->select($query, [$id]);
            }
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
        try {
            $account = $this->getValidAccount($request['accountId']);
            if (!$this->checkAccountCharge($account)) {
                return response()->json([
                    'error' => __('کاربر گرامی شارژ اشتراک شما به پایان رسیده است.'),
                ], 403);
            }

            $modelClass = $this->resolveModelClass($request['model_name']);
            $modelInstance = $this->initializeModelInstance($modelClass, $account);

            $data = $request->input('data', []);
            $existingRecord = $this->findExistingRecord($modelInstance, $request);

            if ($existingRecord) {
                $updatedRecord = $this->handleSync($modelClass, $existingRecord, $request, $data);
                return $this->respondWithSuccess('Record synced updated', $updatedRecord);
            } else {
                $newRecord = $this->handleSync($modelClass, new $modelClass($data), $request, $data);
                return $this->respondWithSuccess('Record synced successfully', $newRecord);
            }
        } catch (\Exception $e) {
            Log::error("Sync failed: " . $e->getMessage());
            return response()->json(['error' => 'Failed to sync data: ' . $e->getMessage()], 500);
        }
    }

    private function getValidAccount($accountId)
    {
        $account = Account::findOrFail($accountId);
        if (!$account) {
            throw new \Exception('Account not found');
        }
        return $account;
    }

    private function resolveModelClass($modelName)
    {
        $modelClass = "App\\Models\\Sync\\" . basename(str_replace('\\', '/', $modelName));
        if (!class_exists($modelClass)) {
            throw new \Exception('Invalid model name');
        }
        return $modelClass;
    }

    private function initializeModelInstance($modelClass, $account)
    {
        $decrypted = $this->decrypt($account->db_name, $account->db_user, $account->db_pass);
        (new Database())->connect([
            'name' => $decrypted['name'],
            'user' => $decrypted['user'],
            'pass' => $decrypted['pass'],
        ]);

        $modelInstance = new $modelClass;
        $modelInstance->setConnection('useraccount');
        return $modelInstance;
    }

    private function findExistingRecord($modelInstance, $request)
    {
        if (!empty($request['m_uuid'])) {
            return $modelInstance->where('uuid', $request['m_uuid'])->first();
        }
        return $modelInstance->find($request['m_id']);
    }

    private function handleSync($modelClass, $record, $request, $data)
    {
        $record = match ($modelClass) {
            'App\\Models\\Sync\\Factor' => $this->syncFactor($record, $request),
            'App\\Models\\Sync\\FactorBody' => $this->syncFactorBody($record, $request),
            'App\\Models\\Sync\\Game' => $this->syncGame($record, $request),
            'App\\Models\\Sync\\GameMeta' => $this->syncGameMeta($record, $request),
            'App\\Models\\Sync\\Payment' => $this->syncPayment($record, $request),
            'App\\Models\\Sync\\PersonMeta' => $this->syncPersonMeta($record, $request),
            'App\\Models\\Sync\\Offer' => $this->syncOffer($record, $request['m_id']),
            'App\\Models\\Sync\\Product' => $this->syncProduct($record, $request['m_id']),
            'App\\Models\\Sync\\Wallet' => $this->syncWallet($record, $request['m_id']),
            default => $record,
        };

        $record->timestamps = false;
        $record->fill($data);
        $record->created_at = $data['created_at'] ?? null;
        $record->updated_at = $data['updated_at'] ?? null;
        $record->save();

        return $record;
    }

    private function respondWithSuccess($message, $record)
    {
        return response()->json([
            'message' => $message,
            'data' => $record->toArray(),
        ], 200);
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
                throw new Exception($gameInstance);
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
                $record->factor_id = $factorInstance->id;
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

    public function syncOffer($offerData, $id)
    {
        $offerModel = "App\\Models\\Sync\\Offer";

        if (!class_exists($offerModel)) {
            throw new \Exception("Offer model not found");
        }

        $offerInstance = $offerModel::find($id);

        if (!$offerInstance) {
            $offerInstance = $offerModel::create($offerData);
        }

        return $offerInstance;
    }

    public function syncProduct($productData, $id)
    {
        $productModel = "App\\Models\\Sync\\Product";

        if (!class_exists($productModel)) {
            throw new \Exception("Product model not found");
        }

        $productInstance = $productModel::find($id);

        if (!$productInstance) {
            $productInstance = $productModel::create($productData);
        }

        return $productInstance;
    }
    public function syncWallet($walletData, $id)
    {
        $walletModel = "App\\Models\\Sync\\Wallet";

        if (!class_exists($walletModel)) {
            throw new \Exception("Wallet model not found");
        }

        $walletInstance = $walletModel::find($id);

        if (!$walletInstance) {
            $walletInstance = $walletModel::create($walletData);
        }

        return $walletInstance;
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
}
