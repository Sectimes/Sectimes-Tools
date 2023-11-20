<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackingEndpointTarget extends Model
{
    use HasFactory;

    protected $table = 'tracking_endpoint_target';
    // protected $primaryKey = 'target'; // Set the primary key explicitly
    // public $incrementing = false; // Disable auto-incrementing for the primary key
    protected $fillable = ['target', 'num_of_results'];

    public function results()
    {
        return $this->hasMany(TrackingEndpointResults::class, 'target', 'target');
    }
}
