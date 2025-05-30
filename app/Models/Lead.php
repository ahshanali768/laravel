<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name', 'last_name', 'phone', 'address', 'city', 'state', 'zip', 'email', 'notes',
        'status', 'agent_name', 'did_number', 'campaign_name', 'verifier_name'
    ];

    protected $appends = ['date'];

    public function getDateAttribute()
    {
        // Return only the date part of created_at
        return $this->created_at ? $this->created_at->format('Y-m-d') : null;
    }

    // Optionally, hide id and updated_at from array/json
    protected $hidden = ['id', 'updated_at'];
}
