<?php

namespace App\Models\Iqathletic;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Suscripcion extends Model
{
    protected $connection = 'iqathletic';

    protected $table = 'suscripciones';

    protected $fillable = [
        'user_id',
        'empresa_id',
        'monto',
        'tipo',
        'status',
        'observaciones',
        'inicio',
        'vencimiento',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'inicio' => 'date',
            'vencimiento' => 'date',
            'active' => 'boolean',
            'monto' => 'integer',
        ];
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function isExpired(): bool
    {
        return $this->vencimiento !== null && $this->vencimiento->isPast();
    }
}
