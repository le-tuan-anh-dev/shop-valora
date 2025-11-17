<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class BannedWord extends Model
{
    protected $table = 'banned_words';
    protected $fillable = ['word'];
}
