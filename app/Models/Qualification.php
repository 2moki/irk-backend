<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Qualification extends Model
{
    protected $fillable = [
        'name',
        'qualification_category_id'
    ];

    public function qualificationCategory(): BelongsTo
    {
        return $this->belongsTo(QualificationCategory::class, 'qualification_category_id');
    }
}
