<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reports extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'amount',
        'buyer',
        'receipt_id',
        'items',
        'buyer_email',
        'buyer_ip',
        'note',
        'city',
        'phone',
        'hash_key',
        'entry_at',
        'entry_by',
    ];
}
