<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Tag extends Model
{
    use HasUuid;

    protected $fillable = ['name'];

    public function posts(): MorphToMany
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }

    public function products(): MorphToMany
    {
        return $this->morphedByMany(Product::class, 'taggable');
    }
} 