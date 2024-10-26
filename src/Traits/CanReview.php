<?php

namespace AlexRegenbogen\CommentsRatingsReviews\Traits;

trait CanReview
{
    /**
     * Check if a comment for a specific model needs to be approved.
     *
     * @param  mixed  $model
     */
    public function needsReviewApproval($model): bool
    {
        return true;
    }
}
