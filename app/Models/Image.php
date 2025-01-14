<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    use HasUuid;

    protected $fillable = [
        'filename',
        'full_path',
        'mime_type',
        'size',
        'created_by_id'
    ];

    /**
     * Get the parent imageable model (post or product).
     */
    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user who created the image.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
} 