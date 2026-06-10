<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    // Jika menggunakan custom schema, bisa ditambahkan di sini (opsional karena sudah di config)
    // protected $connection = 'pgsql';
    
    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'image',
        'content',
        'author',
        'published_at',
    ];

    protected $dates = ['published_at'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_post');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title) . '-' . time();
            }
        });
    }
}
