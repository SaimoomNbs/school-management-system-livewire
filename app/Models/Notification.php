<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    protected $fillable = [
        'type',
        'title',
        'message',
        'notifiable_type',
        'notifiable_id',
        'channel',
        'is_read',
        'reference_type',
        'reference_id',
        'sent_at',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'sent_at' => 'datetime',
            'read_at' => 'datetime',
        ];
    }

    /* ------------------------------------------------------------------ */
    /*  Relationships                                                      */
    /* ------------------------------------------------------------------ */

    /**
     * The entity that this notification belongs to (User or Student).
     */
    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }
}
