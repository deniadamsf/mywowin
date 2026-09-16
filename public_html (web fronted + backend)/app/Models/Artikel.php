<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Artikel extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
            if (empty($model->slug) && !empty($model->judul)) {
                $model->slug = static::generateUniqueSlug($model->judul);
            }
        });

        static::updating(function ($model) {
            if (empty($model->slug) && !empty($model->judul)) {
                $model->slug = static::generateUniqueSlug($model->judul, $model->id);
            }
        });
    }

    public static function generateUniqueSlug($title, $ignoreId = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
    }

    protected $fillable = [
        'user_id', 'judul', 'slug', 'isi', 'foto_artikel'
    ];

    protected $casts = [
        'foto_artikel' => 'array',
        'isi' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
