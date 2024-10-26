<?php

namespace AlexRegenbogen\CommentsRatingsReviews\Tests\Models;

use AlexRegenbogen\CommentsRatingsReviews\Contracts\Commentator;
use Illuminate\Foundation\Auth\User;

class ApprovedUser extends User implements Commentator
{
    protected $table = 'users';

    /**
     * Check if a comment for a specific model needs to be approved.
     *
     * @param  mixed  $model
     */
    public function needsCommentApproval($model): bool
    {
        return false;
    }
}
