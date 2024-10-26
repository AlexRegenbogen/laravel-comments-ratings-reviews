<?php

namespace AlexRegenbogen\CommentsRatingsReviews\Contracts;

interface Reviewer
{
    /**
     * Check if a comment for a specific model needs to be approved.
     *
     * @param  mixed  $model
     */
    public function needsReviewApproval($model): bool;
}
