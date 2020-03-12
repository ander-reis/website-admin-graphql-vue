<?php

namespace WebsiteAdmin\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'Account';

    protected $fillable = ['user_id', 'description'];

    public function user()
    {
        return $this->belongsTo(\WebsiteAdmin\Models\User::class);
    }
}
