<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Catálogo local de planes (dragonweb.suscripciones).
 * id 1 = Progresivo, 2 = Mensual, 3 = Anual.
 *
 * Precios de referencia (ARS):
 * - Progresivo: desde 50.000 (niveles 50k / 150k / 250k según alumnos)
 * - Mensual: 250.000
 * - Anual: 2.500.000 (tachado 3.000.000)
 */
class PlanSuscripcion extends Model
{
    public const PROGRESIVO = 1;

    public const MENSUAL = 2;

    public const ANUAL = 3;

    public const DIAS_GRACIA = 3;

    protected $table = 'suscripciones';

    protected $fillable = [
        'nombre',
        'periodo',
        'duracion',
        'precio',
        'precio_tachado',
        'moneda',
        'niveles',
        'badge',
        'destacado',
        'descripcion',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'destacado' => 'boolean',
            'duracion' => 'integer',
            'precio' => 'integer',
            'precio_tachado' => 'integer',
            'niveles' => 'array',
        ];
    }

    public function isYearly(): bool
    {
        return $this->periodo === 'year';
    }

    public function isMonthly(): bool
    {
        return $this->periodo === 'month';
    }

    public function isProgresivo(): bool
    {
        return (int) $this->id === self::PROGRESIVO;
    }

    /**
     * Límite de alumnos activos según monto (misma regla que iQ Athletic).
     */
    public static function limiteAlumnosPorMonto(int $monto): ?string
    {
        if ($monto >= 250000) {
            return 'ilimitado';
        }

        if ($monto === 150000) {
            return 'hasta40';
        }

        if ($monto === 50000) {
            return 'hasta20';
        }

        return null;
    }

    /**
     * Detecta el plan catálogo a partir del tipo guardado en iqathletic.suscripciones.
     */
    public static function matchTipo(?string $tipo): ?int
    {
        $t = mb_strtolower((string) $tipo);

        if ($t === '') {
            return null;
        }

        if (str_contains($t, 'progresivo')) {
            return self::PROGRESIVO;
        }

        if (str_contains($t, 'anual')) {
            return self::ANUAL;
        }

        if (str_contains($t, 'mensual') || str_contains($t, 'total')) {
            return self::MENSUAL;
        }

        return null;
    }

    public function toDashboardArray(): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'periodo' => $this->periodo,
            'duracion' => $this->duracion,
            'precio' => $this->precio,
            'precio_tachado' => $this->precio_tachado,
            'moneda' => $this->moneda,
            'niveles' => $this->niveles ?? [],
            'badge' => $this->badge,
            'destacado' => (bool) $this->destacado,
            'descripcion' => $this->descripcion,
        ];
    }
}
