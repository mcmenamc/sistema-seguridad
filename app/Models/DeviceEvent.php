<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceEvent extends Model
{
    protected $fillable = [
        'device_id',
        'type',
        'timestamp',
        'processed',
        'incident_id'
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'processed' => 'boolean',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function incident(): BelongsTo
    {
        return $this->belongsTo(Incident::class);
    }
}
