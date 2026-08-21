<?php

namespace App\Models\Iqathletic;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class IqUser extends Authenticatable
{
    use SoftDeletes;

    protected $connection = 'iqathletic';

    protected $table = 'users';

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'replica',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'active' => 'boolean',
        ];
    }

    public function getAuthPasswordName(): string
    {
        return 'password';
    }

    public function privilegios(): HasMany
    {
        return $this->hasMany(UserPrivilegio::class, 'user_id');
    }

    public function adminPrivilegio(): ?UserPrivilegio
    {
        return $this->privilegios()
            ->where('privilegio', 'Administrador')
            ->where('active', 1)
            ->whereNotNull('empresa_id')
            ->first();
    }
}
