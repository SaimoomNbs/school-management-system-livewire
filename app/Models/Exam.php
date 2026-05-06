<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    protected $fillable = [
        'exam_group_id',
        'subject_id',
        'title',
        'exam_date',
        'total_marks',
        'pass_marks',
    ];

    protected function casts(): array
    {
        return [
            'exam_date' => 'date',
            'total_marks' => 'decimal:2',
            'pass_marks' => 'decimal:2',
        ];
    }

    /* ------------------------------------------------------------------ */
    /*  Relationships                                                      */
    /* ------------------------------------------------------------------ */

    public function examGroup(): BelongsTo
    {
        return $this->belongsTo(ExamGroup::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }
}
