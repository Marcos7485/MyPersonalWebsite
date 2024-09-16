<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    use HasFactory;

    protected $table = 'projects';

    protected $fillable = [
        'cliente',
        'pago_entrada',
        'pago_final',
        'valor_total',
        'plazo',
        'progreso',
        'finalizado',
        'hash'
    ];
}
