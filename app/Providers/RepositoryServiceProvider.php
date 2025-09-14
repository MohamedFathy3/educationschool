<?php

namespace App\Providers;


use App\Interfaces\CountryRepositoryInterface;
use App\Interfaces\CourseDetailRepositoryInterface;
use App\Interfaces\CourseRepositoryInterface;
use App\Interfaces\StageRepositoryInterface;
use App\Interfaces\SubjectRepositoryInterface;
use App\Interfaces\TeacherRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\CountryRepository;
use App\Repositories\CourseDetailRepository;
use App\Repositories\CourseRepository;
use App\Repositories\StageRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\TeacherRepository;
use App\Repositories\UserRepository;
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(StageRepositoryInterface::class, StageRepository::class);
        $this->app->bind(SubjectRepositoryInterface::class, SubjectRepository::class);
        $this->app->bind(CountryRepositoryInterface::class, CountryRepository::class);
        $this->app->bind(TeacherRepositoryInterface::class, TeacherRepository::class);
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(CourseDetailRepositoryInterface::class, CourseDetailRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}


