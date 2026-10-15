<?php

namespace App\Http\Controllers\Api;

use App\Models\Contact;
use App\Models\ContactSubmission;
use App\Models\ForumCategory;
use App\Models\ForumReply;
use App\Models\ForumTopic;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\PollVote;
use App\Models\Subscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InteractiveApiController extends ApiController
{
    /**
     * Submit vote for a poll option.
     */
    public function votePoll(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'option_id' => 'required|integer|exists:poll_options,id',
        ]);

        $poll = Poll::where('id', $id)->where('is_active', true)->first();
        if (!$poll) {
            return $this->error('Poll is inactive or not found', 404);
        }

        $ip = $request->ip() ?: '127.0.0.1';

        // Check if IP already voted
        $existingVote = PollVote::where('poll_id', $id)->where('ip_address', $ip)->first();
        if ($existingVote) {
            return $this->error('You have already voted on this poll.', 429);
        }

        PollVote::create([
            'poll_id' => $id,
            'poll_option_id' => $request->option_id,
            'ip_address' => $ip,
        ]);

        return $this->success(null, 'Thank you! Your civic vote has been counted.');
    }

    /**
     * Get Citizen Forum categories and topics.
     */
    public function forumTopics(): JsonResponse
    {
        $locale = $this->locale();

        $categories = ForumCategory::where('is_active', true)
            ->with(['translations' => function ($q) use ($locale) {
                $q->where('locale', $locale);
            }, 'topics' => function ($q) use ($locale) {
                $q->latest('updated_at')
                  ->with(['user:id,name', 'translations' => function ($tq) use ($locale) {
                      $tq->where('locale', $locale);
                  }, 'replies']);
            }])
            ->get()
            ->map(function ($cat) use ($locale) {
                $t = $cat->translate($locale);
                return [
                    'id' => $cat->id,
                    'slug' => $cat->slug,
                    'name' => $t?->name ?? '',
                    'description' => $t?->description ?? '',
                    'topics' => $cat->topics->map(function ($topic) use ($locale) {
                        $tt = $topic->translate($locale);
                        return [
                            'id' => $topic->id,
                            'slug' => $topic->slug,
                            'title' => $tt?->title ?? '',
                            'content' => $tt?->content ?? '',
                            'author' => $topic->user?->name ?? 'Citizen',
                            'views_count' => $topic->views_count,
                            'replies_count' => $topic->replies->count(),
                            'is_pinned' => $topic->is_pinned,
                            'created_at' => $topic->created_at->diffForHumans(),
                        ];
                    }),
                ];
            });

        return $this->success($categories);
    }

    /**
     * Submit new Forum Topic.
     */
    public function createForumTopic(Request $request): JsonResponse
    {
        $request->validate([
            'category_id' => 'required|integer|exists:forum_categories,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:5000',
        ]);

        $locale = $this->locale();
        $slug = Str::slug($request->title) . '-' . Str::random(5);

        $topic = ForumTopic::create([
            'forum_category_id' => $request->category_id,
            'user_id' => 1, // default admin or current citizen
            'slug' => $slug,
            'is_pinned' => false,
            'is_locked' => false,
        ]);

        $topic->translations()->create([
            'locale' => $locale,
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return $this->success(['slug' => $slug], 'Discussion topic published successfully!', 201);
    }

    /**
     * Post a reply to forum topic.
     */
    public function replyForumTopic(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $topic = ForumTopic::findOrFail($id);

        $reply = ForumReply::create([
            'forum_topic_id' => $topic->id,
            'user_id' => 1,
            'content' => $request->content,
        ]);

        return $this->success($reply, 'Reply posted successfully!', 201);
    }

    /**
     * Get Central Contact Information.
     */
    public function contactInfo(): JsonResponse
    {
        $locale = $this->locale();

        $contact = Contact::with(['translations' => function ($q) use ($locale) {
            $q->where('locale', $locale);
        }])->first();

        if (!$contact) {
            return $this->error('Contact info not configured', 404);
        }

        $t = $contact->translate($locale);

        return $this->success([
            'phone' => $contact->phone,
            'email' => $contact->email,
            'fax' => $contact->fax,
            'location_lat' => $contact->location_lat,
            'location_lng' => $contact->location_lng,
            'map_embed_url' => $contact->map_embed_url,
            'address' => $t?->address ?? '',
            'office_name' => $t?->office_name ?? '',
            'working_hours' => $t?->working_hours ?? '',
        ]);
    }

    /**
     * Submit citizen feedback or inquiry.
     */
    public function submitContact(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $trackingNumber = 'CIT-' . strtoupper(Str::random(6));

        ContactSubmission::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'new',
            'ip_address' => $request->ip(),
        ]);

        return $this->success([
            'tracking_number' => $trackingNumber,
        ], 'Your message has been submitted. Tracking code: ' . $trackingNumber, 201);
    }

    /**
     * Subscribe to city newsletter.
     */
    public function subscribeNewsletter(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $subscriber = Subscriber::firstOrCreate(
            ['email' => strtolower(trim($request->email))],
            ['is_active' => true, 'subscribed_at' => now()]
        );

        return $this->success(null, 'Successfully subscribed to official city announcements.');
    }
}
