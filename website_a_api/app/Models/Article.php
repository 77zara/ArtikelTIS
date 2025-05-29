<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Jika Anda menggunakan factory

class Article extends Model
{
    // use HasFactory; // Aktifkan jika Anda membuat factory

    protected $fillable = [
        'title', 'content',
    ];
}