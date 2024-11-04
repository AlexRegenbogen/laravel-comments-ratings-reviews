<?php

namespace AlexRegenbogen\CommentsRatingsReviews\Contracts;

interface Rater
{
    /**
     * Check if a comment for a specific model needs to be approved.
     *
     * @param  mixed  $model
     */
    public function needsRatingApproval($model): bool;
}
