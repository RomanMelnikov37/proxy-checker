<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProxyCheckResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'checked_at',
        'duration',
        'total_proxies',
        'working_proxies',
    ];

    public function proxyChecks(): HasMany
    {
        return $this->hasMany(ProxyCheck::class);
    }
}
