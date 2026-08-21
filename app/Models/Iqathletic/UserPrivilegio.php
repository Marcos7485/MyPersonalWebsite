<?php

namespace App\Models\Iqathletic;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPrivilegio extends Model
{
    use SoftDeletes;

    protected $connection = 'iqathletic';

    protected $table = 'userprivilegio';

    protected $fillable = [
        'user_id',
        'empresa_id',
        'plan_id',
        'inicio',
        'privilegio',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'inicio' => 'date',
            'active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(IqUser::class, 'user_id');
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}
