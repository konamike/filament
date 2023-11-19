<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Category;
use App\Models\Contractor;
use App\Models\File;
use App\Models\Filetreat;
use App\Models\Letter;
use App\Models\Lettertreat;
use App\Models\Memo;
use App\Models\Memotreat;
use App\Policies\CategoryPolicy;
use App\Policies\ContractorPolicy;
use App\Policies\FilePolicy;
use App\Policies\FiletreatPolicy;
use App\Policies\LetterPolicy;
use App\Policies\LettertreatPolicy;
use App\Policies\MemoPolicy;
use App\Policies\MemotreatPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Category::class => CategoryPolicy::class,
        Contractor::class => ContractorPolicy::class,
        File::class => FilePolicy::class,
        Letter::class => LetterPolicy::class,
        Memo::class => MemoPolicy::class,
        Filetreat::class => FiletreatPolicy::class,
        Lettertreat::class => LettertreatPolicy::class,
        Memotreat::class => MemotreatPolicy::class,
//        Contractor::class => ContractorPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
