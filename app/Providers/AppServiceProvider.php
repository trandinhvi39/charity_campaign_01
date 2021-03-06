<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\Campaign\CampaignRepositoryInterface;
use App\Repositories\Campaign\CampaignRepository;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Contribution\ContributionRepository;
use App\Repositories\Contribution\ContributionRepositoryInterface;
use App\Repositories\Comment\CommentRepository;
use App\Repositories\Comment\CommentRepositoryInterface;
use App\Repositories\Rating\RatingRepository;
use App\Repositories\Rating\RatingRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Follow\FollowRepository;
use App\Repositories\Follow\FollowRepositoryInterface;
use App\Repositories\Action\ActionRepository;
use App\Repositories\Action\ActionRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extendImplicit('campaign', 'App\Validation\CampaignValidate@campaign');
        Validator::extendImplicit('amount', 'App\Validation\ContributionValidate@amount');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        App::bind(UserRepositoryInterface::class, UserRepository::class);
        App::bind(CampaignRepositoryInterface::class, CampaignRepository::class);
        App::bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        App::bind(ContributionRepositoryInterface::class, ContributionRepository::class);
        App::bind(CommentRepositoryInterface::class, CommentRepository::class);
        App::bind(RatingRepositoryInterface::class, RatingRepository::class);
        App::bind(FollowRepositoryInterface::class, FollowRepository::class);
        App::bind(ActionRepositoryInterface::class, ActionRepository::class);
    }
}
