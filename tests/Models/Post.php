<?php

namespace AlexRegenbogen\CommentsRatingsReviews\Tests\Models;

use AlexRegenbogen\CommentsRatingsReviews\Traits\HasComments;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasComments;

    protected $guarded = [];

    public static function boot(): void
    {
        parent::boot();

        static::deleting(function (self $model) {
            $model->comments()->delete();
        });
    }
}
