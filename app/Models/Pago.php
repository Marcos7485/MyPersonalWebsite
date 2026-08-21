<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    public const STATUS_PENDIENTE = 'pendiente';

    public const STATUS_APROBADO = 'aprobado';

    public const STATUS_RECHAZADO = 'rechazado';

    public const STATUS_REEMBOLSADO = 'reembolsado';

    protected $table = 'pagos';

    protected $fillable = [
        'email',
        'empresa_id',
        'aplicacion',
        'plan_id',
        'plan_nombre',
        'monto',
        'moneda',
        'metodo',
        'referencia',
        'status',
        'pagado_en',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'empresa_id' => 'integer',
            'aplicacion' => 'integer',
            'plan_id' => 'integer',
            'monto' => 'integer',
            'pagado_en' => 'datetime',
            'meta' => 'array',
        ];
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(PlanSuscripcion::class, 'plan_id');
    }

    public function toDashboardArray(): array
    {
        return [
            'id' => $this->id,
            'aplicacion' => $this->aplicacion,
            'plan_id' => $this->plan_id,
            'plan_nombre' => $this->plan_nombre,
            'monto' => $this->monto,
            'monto_formato' => '$'.number_format((int) $this->monto, 0, ',', '.'),
            'moneda' => $this->moneda,
            'metodo' => $this->metodo,
            'referencia' => $this->referencia,
            'status' => $this->status,
            'pagado_en' => optional($this->pagado_en)?->format('d/m/Y H:i'),
            'created_at' => optional($this->created_at)?->format('d/m/Y H:i'),
        ];
    }
}
