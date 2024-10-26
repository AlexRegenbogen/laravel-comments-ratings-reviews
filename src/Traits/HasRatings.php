<?php

namespace AlexRegenbogen\CommentsRatingsReviews\Traits;

use AlexRegenbogen\CommentsRatingsReviews\Contracts\Rater;
use AlexRegenbogen\CommentsRatingsReviews\Rating;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasRatings
{
    /**
     * Return all ratings for this model.
     *
     * @return MorphMany
     */
    public function ratings()
    {
        return $this->morphMany(config('comments_ratings_reviews.rating_class'), 'rateable');
    }

    public function getRatings(): Collection
    {
        /** @var Collection<int, Rating> $res */
        $res = $this->getRelationValue('ratings');

        return $res;
    }

    /**
     * Attach a rating to this model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function rating(string $rating)
    {
        return $this->ratingAsUser(auth()->user(), $rating);
    }

    /**
     * Attach a rating to this model as a specific user.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function ratingAsUser(?Model $user, string $rating)
    {
        $ratingClass = config('comments_ratings_reviews.rating_class');

        $rating = new $ratingClass([
            'rating' => $rating,
            'is_approved' => $user instanceof Rater && !$user->needsRatingApproval($this),
            'user_id' => is_null($user) ? null : $user->getKey(),
            'rateable_id' => $this->getKey(),
            'rateable_type' => get_class($this),
        ]);

        return $this->ratings()->save($rating);
    }
}
