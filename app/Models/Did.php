<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Did extends Model
{
    use HasFactory;
    protected $fillable = ['did_number', 'payout_amount', 'owner_campaign', 'campaign_payout'];
}
