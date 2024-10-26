<?php

namespace AlexRegenbogen\CommentsRatingsReviews\Traits;

trait CanRate
{
    /**
     * Check if a comment for a specific model needs to be approved.
     *
     * @param  mixed  $model
     */
    public function needsRatingApproval($model): bool
    {
        return true;
    }
}
