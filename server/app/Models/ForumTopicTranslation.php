<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ForumTopicTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['forum_topic_id', 'locale', 'title', 'content'];

    public function forumTopic(): BelongsTo
    {
        return $this->belongsTo(ForumTopic::class);
    }
}
