<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cards extends Model
{
    use HasFactory;

    protected $table = 'cards';

    protected $fillable = [
        'project',
        'card',
        'image',
        'hover_text',
        'component',
        'descripcion',
        'active',
    ];  

    protected $casts = [
        'card' => 'integer',
        'active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function getImageUrlAttribute(): string
    {
        return '/images/' . $this->project . '/' . ltrim($this->image, '/');
    }

    public function getProjectIconUrlAttribute(): string
    {
        return '/images/' . $this->project . '/icon.png';
    }
}
