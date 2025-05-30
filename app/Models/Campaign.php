<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'payout_amount'];

    public function leads()
    {
        return $this->hasMany(\App\Models\Lead::class, 'campaign_name', 'name');
    }
}
