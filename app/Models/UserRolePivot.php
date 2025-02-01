<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserRolePivot extends Model
{
    //UserRolePivot
    protected $table = 'user_role_pivot';

    protected $fillable = [
        'user_id',
        'role_id'
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(UserRolePivot::class);
    }
}
