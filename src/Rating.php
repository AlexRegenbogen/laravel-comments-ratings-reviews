<?php

namespace AlexRegenbogen\CommentsRatingsReviews;

use AlexRegenbogen\CommentsRatingsReviews\Events\RatingAdded;
use AlexRegenbogen\CommentsRatingsReviews\Events\RatingDeleted;
use AlexRegenbogen\CommentsRatingsReviews\Traits\HasComments;
use AlexRegenbogen\CommentsRatingsReviews\Traits\HasRatings;
use Exception;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasRatings;

    protected $fillable = [
        'rating',
        'user_id',
        'is_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::deleting(function (self $model) {
            if (config('comments.delete_replies_along_rating')) {
                $model->ratings()->delete();
            }
        });

        static::deleted(function (self $model) {
            RatingDeleted::dispatch($model);
        });

        static::created(function (self $model) {
            RatingAdded::dispatch($model);
        });
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function rateable()
    {
        return $this->morphTo();
    }

    public function rater()
    {
        return $this->belongsTo($this->getAuthModelName(), 'user_id');
    }

    public function approve()
    {
        $this->update([
            'is_approved' => true,
        ]);

        return $this;
    }

    public function disapprove()
    {
        $this->update([
            'is_approved' => false,
        ]);

        return $this;
    }

    protected function getAuthModelName()
    {
        if (config('comments.user_model')) {
            return config('comments.user_model');
        }

        if (! is_null(config('auth.providers.users.model'))) {
            return config('auth.providers.users.model');
        }

        throw new Exception('Could not determine the rater model name.');
    }
}
