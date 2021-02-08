<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property string url
 * @property string name
 * @property string last_commit_message
 * @property int status_id
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Repository extends Model
{
    use HasFactory;

    const INITIALIZED = 1;
    const INVALID = 2;
    const CLONED = 3;

    protected $fillable = ['url', 'name', 'last_commit_message', 'status_id'];
}
