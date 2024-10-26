<?php

use AlexRegenbogen\CommentsRatingsReviews\Comment;
use AlexRegenbogen\CommentsRatingsReviews\Rating;
use AlexRegenbogen\CommentsRatingsReviews\Review;

return [

    /*
     * The comment class that should be used to store and retrieve
     * the comments.
     */
    'comment_class' => Comment::class,
    'review_class' => Review::class,
    'rating_class' => Rating::class,

    /*
     * The user model that should be used when associating comments with
     * commentators/reviewers/raters. If null, the default user provider from your
     * Laravel authentication configuration will be used.
     */
    'user_model' => null,

    /**
     * Determines if replies will be deleted when comments are deleted
     */
    'delete_replies_along_comments' => false,
    'delete_replies_along_reviews' => false,
    'delete_replies_along_ratings' => false,
];
