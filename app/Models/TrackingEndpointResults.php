<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackingEndpointResults extends Model
{
    use HasFactory;

    protected $table = 'tracking_endpoint_results';
    protected $fillable = ['endpoint', 'status', 'tag', 'attribute', 'target'];

    public function target()
    {
        return $this->belongsTo(TrackingEndpointTarget::class, 'target', 'target');
    }

}
