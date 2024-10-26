<?php

namespace AlexRegenbogen\CommentsRatingsReviews;

use Illuminate\Support\ServiceProvider;

class CommentsRatingsReviewsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('comments_ratings_reviews.php'),
            ], 'config');

            if (! class_exists('CreateCommentsTable')) {
                $this->publishes([
                    __DIR__.'/../database/migrations/create_comments_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_comments_table.php'),
                    __DIR__.'/../database/migrations/create_reviews_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_reviews_table.php'),
                    __DIR__.'/../database/migrations/create_ratings_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_ratings_table.php'),
                ], 'migrations');
            }
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'comments_ratings_reviews');
    }
}
