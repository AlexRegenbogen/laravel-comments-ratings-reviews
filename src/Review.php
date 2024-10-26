<?php

namespace AlexRegenbogen\CommentsRatingsReviews;

use AlexRegenbogen\CommentsRatingsReviews\Events\ReviewAdded;
use AlexRegenbogen\CommentsRatingsReviews\Events\ReviewDeleted;
use AlexRegenbogen\CommentsRatingsReviews\Traits\HasReviews;
use Exception;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasReviews;

    protected $fillable = [
        'review',
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
            if (config('comments.delete_replies_along_review')) {
                $model->comments()->delete();
            }
        });

        static::deleted(function (self $model) {
            ReviewDeleted::dispatch($model);
        });

        static::created(function (self $model) {
            ReviewAdded::dispatch($model);
        });
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function commentator()
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

        if (!is_null(config('auth.providers.users.model'))) {
            return config('auth.providers.users.model');
        }

        throw new Exception('Could not determine the reviewer model name.');
    }
}
