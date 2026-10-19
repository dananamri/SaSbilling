<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'action',
        'module',
        'description',
        'properties',
        'ip_address',
        'user_agent',
        'tenant_id',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function log(string $action, string $module, string $description, ?array $properties = null): self
    {
        $user = auth()->user();

        return self::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name ?? 'System',
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'tenant_id' => $user?->tenant_id,
        ]);
    }

    public static function record(string $action, string $description, mixed $subject = null): self
    {
        $properties = null;
        if ($subject instanceof Model) {
            $properties = ['id' => $subject->getKey(), 'class' => $subject::class];
        } elseif (is_array($subject)) {
            $properties = $subject;
        }

        return self::log($action, 'general', $description, $properties);
    }
}
