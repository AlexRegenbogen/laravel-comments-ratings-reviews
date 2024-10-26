<?php

namespace AlexRegenbogen\CommentsRatingsReviews\Traits;

use AlexRegenbogen\CommentsRatingsReviews\Contracts\Commentator;
use AlexRegenbogen\CommentsRatingsReviews\Review;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasReviews
{
    /**
     * Return all comments for this model.
     *
     * @return MorphMany
     */
    public function reviews()
    {
        return $this->morphMany(config('comments_ratings_reviews.review_class'), 'reviewable');
    }

    public function getReviews(): Collection
    {
        /** @var Collection<int, Review> $res */
        $res = $this->getRelationValue('reviews');

        return $res;
    }

    /**
     * Attach a comment to this model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function review(string $review)
    {
        return $this->reviewAsUser(auth()->user(), $review);
    }

    /**
     * Attach a comment to this model as a specific user.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function reviewAsUser(?Model $user, string $review)
    {
        $reviewClass = config('comments_ratings_reviews.review_class');

        $review = new $reviewClass([
            'review' => $review,
            'is_approved' => ($user instanceof Reviewer) ? ! $user->needsReviewApproval($this) : false,
            'user_id' => is_null($user) ? null : $user->getKey(),
            'reviewable_id' => $this->getKey(),
            'reviewable_type' => get_class($this),
        ]);

        return $this->comments()->save($review);
    }
}
