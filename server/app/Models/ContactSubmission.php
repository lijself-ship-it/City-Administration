<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSubmission extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'subject',
        'message', 'status', 'ip_address',
    ];

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Mark submission as read.
     */
    public function markAsRead(): void
    {
        $this->update(['status' => 'read']);
    }

    /**
     * Mark submission as replied.
     */
    public function markAsReplied(): void
    {
        $this->update(['status' => 'replied']);
    }

    /**
     * Archive the submission.
     */
    public function archive(): void
    {
        $this->update(['status' => 'archived']);
    }
}
