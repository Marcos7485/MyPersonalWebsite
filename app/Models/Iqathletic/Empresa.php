<?php

namespace App\Models\Iqathletic;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
    use SoftDeletes;

    protected $connection = 'iqathletic';

    protected $table = 'empresas';

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'email',
        'imagen',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    public function suscripciones(): HasMany
    {
        return $this->hasMany(Suscripcion::class, 'empresa_id');
    }
}
