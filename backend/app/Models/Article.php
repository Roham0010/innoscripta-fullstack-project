<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    public $fillable = [
        'from_api',
        'title',
        'author',
        'category',
        'source',
        'description',
        'body',
        'published_at',
    ];

    public function keywords()
    {
        return $this->belongsToMany(Keyword::class, 'article_keywords', 'article_id', 'keyword_id');
    }
}
