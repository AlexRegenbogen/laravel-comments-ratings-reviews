<?php

declare(strict_types=1);

namespace AlexRegenbogen\CommentsRatingsReviews\Traits;

use AlexRegenbogen\CommentsRatingsReviews\Contracts\Reviewer;
use AlexRegenbogen\CommentsRatingsReviews\Review;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasReviews
{
    /**
     * Return all reviews for this model.
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
     * Attach a review to this model.
     *
     * @return Model
     */
    public function review(string $title, string $review)
    {
        return $this->reviewAsUser(auth()->user(), $title, $review);
    }

    /**
     * Attach a review to this model as a specific user.
     *
     * @return Model
     */
    public function reviewAsUser(?Model $user, string $title, string $review)
    {
        $reviewClass = config('comments_ratings_reviews.review_class');

        $review = new $reviewClass([
            'title' => $title,
            'review' => $review,
            'is_approved' => ($user instanceof Reviewer) ? ! $user->needsReviewApproval($this) : false,
            'user_id' => null === $user ? null : $user->getKey(),
            'reviewable_id' => $this->getKey(),
            'reviewable_type' => static::class,
        ]);

        return $this->reviews()->save($review);
    }
}
