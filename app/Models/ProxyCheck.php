<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProxyCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip',
        'port',
        'type',
        'country',
        'city',
        'is_working',
        'speed',
        'external_ip',
        'proxy_check_result_id',
    ];

    public function proxyCheckResult(): BelongsTo
    {
        return $this->belongsTo(ProxyCheckResult::class);
    }
}
