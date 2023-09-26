<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Terapis extends Model
{
    use HasFactory;

    protected $table = 'terapis';
    protected $primaryKey = 'id_terapis';
    protected $guarded = [];
}

