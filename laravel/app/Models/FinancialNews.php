<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialNews extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'summary', 'url', 'published_at'];

    protected $casts = [
        'published_at' => 'datetime',
    ];
}
