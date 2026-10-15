<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ForumReply extends Model
{
    use SoftDeletes;

    protected $fillable = ['forum_topic_id', 'user_id', 'parent_id', 'content'];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(ForumTopic::class, 'forum_topic_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ForumReply::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(ForumReply::class, 'parent_id')->oldest();
    }
}
