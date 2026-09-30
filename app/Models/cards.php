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
        'icon',
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
        $image = trim((string) $this->image);
        if ($image === '') {
            return '';
        }

        if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://') || str_starts_with($image, '/')) {
            return $image;
        }

        return '/images/'.$this->project.'/'.ltrim($image, '/');
    }

    public function getProjectIconUrlAttribute(): string
    {
        $icon = trim((string) ($this->icon ?? ''));
        if ($icon !== '') {
            if (str_starts_with($icon, 'http://') || str_starts_with($icon, 'https://') || str_starts_with($icon, '/')) {
                return $icon;
            }

            return '/images/'.ltrim($icon, '/');
        }

        return '/images/'.$this->project.'/icon.png';
    }
}
