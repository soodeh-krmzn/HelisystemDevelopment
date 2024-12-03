<?php

namespace App\Models\MyModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\Database;

class Sync extends Model
{
    use HasFactory;

    protected $connection = 'useraccount';
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $db = new Database();
        $db->connect();
    }
}
