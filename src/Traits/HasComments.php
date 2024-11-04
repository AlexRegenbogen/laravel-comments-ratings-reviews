<?php

namespace AlexRegenbogen\CommentsRatingsReviews\Traits;

use AlexRegenbogen\CommentsRatingsReviews\Contracts\Commentator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasComments
{
    /**
     * Return all comments for this model.
     *
     * @return MorphMany
     */
    public function comments()
    {
        return $this->morphMany(config('comments_ratings_reviews.comment_class'), 'commentable');
    }

    public function getComments(): Collection
    {
        /** @var Collection<int, Comment> $res */
        $res = $this->getRelationValue('comments');

        return $res;
    }

    /**
     * Attach a comment to this model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function comment(string $comment)
    {
        return $this->commentAsUser(auth()->user(), $comment);
    }

    /**
     * Attach a comment to this model as a specific user.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function commentAsUser(?Model $user, string $comment)
    {
        $commentClass = config('comments_ratings_reviews.comment_class');

        $comment = new $commentClass([
            'comment' => $comment,
            'is_approved' => ($user instanceof Commentator) ? ! $user->needsCommentApproval($this) : false,
            'user_id' => is_null($user) ? null : $user->getKey(),
            'commentable_id' => $this->getKey(),
            'commentable_type' => get_class($this),
        ]);

        return $this->comments()->save($comment);
    }
}
