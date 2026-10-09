<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

// === IMPORT MODEL ===
use App\Models\Article;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\BrandFeature;
use App\Models\ProductReview;
use App\Models\AboutPage;
use App\Models\HeroSetting;

// === IMPORT POLICY ===
use App\Policies\ArticlePolicy;
use App\Policies\CategoryPolicy;
use App\Policies\OrderPolicy;
use App\Policies\ProductPolicy;
use App\Policies\UserPolicy;
use App\Policies\BrandFeaturePolicy;
use App\Policies\ProductReviewPolicy;
use App\Policies\AboutPagePolicy;
use App\Policies\HeroSettingPolicy;

use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Article::class       => ArticlePolicy::class,
        Category::class      => CategoryPolicy::class,
        Order::class         => OrderPolicy::class,
        Product::class       => ProductPolicy::class,
        User::class          => UserPolicy::class,
        BrandFeature::class  => BrandFeaturePolicy::class,
        ProductReview::class => ProductReviewPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {   
        Gate::policy(AboutPage::class, AboutPagePolicy::class);
        Gate::policy(HeroSetting::class, HeroSettingPolicy::class);
        $this->registerPolicies();
    }
}
