<?php

declare(strict_types=1);

namespace AlexRegenbogen\CommentsRatingsReviews;

use AlexRegenbogen\CommentsRatingsReviews\Events\ReviewAdded;
use AlexRegenbogen\CommentsRatingsReviews\Events\ReviewDeleted;
use AlexRegenbogen\CommentsRatingsReviews\Traits\HasReviews;
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

        static::deleting(function (self $model): void {
            if (config('comments.delete_replies_along_review')) {
                $model->comments()->delete();
            }
        });

        static::deleted(function (self $model): void {
            ReviewDeleted::dispatch($model);
        });

        static::created(function (self $model): void {
            ReviewAdded::dispatch($model);
        });
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function reviewable()
    {
        return $this->morphTo();
    }

    public function reviewer()
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

        if (null !== config('auth.providers.users.model')) {
            return config('auth.providers.users.model');
        }

        throw new \Exception('Could not determine the reviewer model name.');
    }
}
