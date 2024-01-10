<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinnedArticle extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'url',
        'date_published',
        'article_id',
    ];
}
