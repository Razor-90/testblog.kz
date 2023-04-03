<?php

namespace App\Providers;

use App\Tag;
use App\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;


class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $views = [
            'layout.part.categories', // меню в левой колонке в публичной части
            'admin.part.categories', // выбор категории поста при редактировании
            'admin.part.parents', // выбор родителя категории при редактировании
            'admin.part.all-ctgs', // все категории в административной части
        ];
        View::composer('layout.part.categories', function($view) {
            $view->with(['items' => Category::all()]);
        });
        View::composer('layout.part.popular-tags', function($view) {
            $view->with(['items' => Tag::popular()]);
        });
        View::composer('admin.part.all-tags', function($view) {
            $view->with(['items' => Tag::all()]);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('layout.part.categories', function($view) {
            static $first = true;
            if ($first) {
                $view->with(['items' => Category::hierarchy()]);
            }
            $first = false;
        });
        View::composer('layout.part.popular-tags', function($view) {
            $view->with(['items' => Tag::popular()]);
        });
    }

}
