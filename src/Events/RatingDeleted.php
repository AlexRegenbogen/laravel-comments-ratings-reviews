<?php

namespace AlexRegenbogen\CommentsRatingsReviews\Events;

use AlexRegenbogen\CommentsRatingsReviews\Rating;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RatingDeleted implements ShouldDispatchAfterCommit
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Rating $rating,
    ) {
    }
}
