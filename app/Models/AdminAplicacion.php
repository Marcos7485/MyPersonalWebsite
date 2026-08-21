<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminAplicacion extends Model
{
    protected $table = 'admin_aplicaciones';

    protected $fillable = [
        'email',
        'aplicacion',
        'empresas',
        'suscripcion',
        'valor',
        'inicio',
        'vencimiento',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'aplicacion' => 'array',
            'empresas' => 'array',
            'inicio' => 'date',
            'vencimiento' => 'date',
            'valor' => 'integer',
        ];
    }

    /**
     * Empresa asociada a una aplicación (1 iQ, 2 Ecommerce).
     *
     * @return array{id:int,nombre:string,email:?string,telefono:?string,direccion:?string}|null
     */
    public function empresaDeAplicacion(int $aplicacionId): ?array
    {
        $empresas = is_array($this->empresas) ? $this->empresas : [];
        $data = $empresas[(string) $aplicacionId] ?? $empresas[$aplicacionId] ?? null;

        return is_array($data) ? $data : null;
    }
}
