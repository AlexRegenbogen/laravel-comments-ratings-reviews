<?php

namespace AlexRegenbogen\CommentsRatingsReviews\Contracts;

interface Commentator
{
    /**
     * Check if a comment for a specific model needs to be approved.
     *
     * @param  mixed  $model
     */
    public function needsCommentApproval($model): bool;
}
