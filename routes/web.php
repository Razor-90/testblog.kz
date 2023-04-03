<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// маршрут для главной страницы без указания метода
Route::get('/', 'IndexController@index');
/*
 * Блог: все посты, посты категории, посты тега, страница поста
 */
Route::group([
    'as' => 'blog.', // имя маршрута, например blog.index
    'prefix' => 'blog', // префикс маршрута, например blog/index
], function () {
    // главная страница (все посты)
    Route::get('index', [BlogController::class, 'index'])
        ->name('index');
    // категория блога (посты категории)
    Route::get('category/{category:slug}', [BlogController::class, 'category'])
        ->name('category');
    // тег блога (посты с этим тегом)
    Route::get('tag/{tag:slug}', [BlogController::class, 'tag'])
        ->name('tag');
    // автор блога (посты этого автора)
    Route::get('author/{user}', [BlogController::class, 'author'])
        ->name('author');
    // страница просмотра поста блога
    Route::get('post/{post:slug}', [BlogController::class, 'post'])
        ->name('post');
    // добавление комментария к посту
    Route::post('post/{post}/comment', [BlogController::class, 'comment'])
        ->name('comment');
    Route::get('search', [BlogController::class, 'search'])
        ->name('search');
});
/*
 * Регистрация, вход в ЛК, восстановление пароля
 */
Route::group([
    'as' => 'auth.', // имя маршрута, например auth.index
    'prefix' => 'auth', // префикс маршрута, например auth/index
], function () {
    // форма регистрации
    Route::get('register', 'Auth\RegisterController@register')
        ->name('register');
    // создание пользователя
    Route::post('register', 'Auth\RegisterController@create')
        ->name('create');
    // форма входа
    Route::get('login', 'Auth\LoginController@login')
        ->name('login');
    // аутентификация
    Route::post('login', 'Auth\LoginController@authenticate')
        ->name('auth');
    // выход
    Route::get('logout', 'Auth\LoginController@logout')
        ->name('logout');
    // форма ввода адреса почты
    Route::get('forgot-password', 'Auth\ForgotPasswordController@form')
        ->name('forgot-form');
    // письмо на почту
    Route::post('forgot-password', 'Auth\ForgotPasswordController@mail')
        ->name('forgot-mail');
    // форма восстановления пароля
    Route::get(
        'reset-password/token/{token}/email/{email}',
        'Auth\ResetPasswordController@form'
    )->name('reset-form');
    // восстановление пароля
    Route::post('reset-password', 'Auth\ResetPasswordController@reset')
        ->name('reset-password');
    // сообщение о необходимости проверки адреса почты
    Route::get('verify-message', 'Auth\VerifyEmailController@message')
        ->name('verify-message');
    // подтверждение адреса почты нового пользователя
    Route::get('verify-email/token/{token}/id/{id}', 'Auth\VerifyEmailController@verify')
        ->where('token', '[a-f0-9]{32}')
        ->where('id', '[0-9]+')
        ->name('verify-email');
});
/*
 * Личный кабинет пользователя
 */
Route::group([
    'as' => 'user.', // имя маршрута, например user.index
    'prefix' => 'user', // префикс маршрута, например user/index
    'namespace' => 'User', // пространство имен контроллеров
    'middleware' => ['auth'] // один или несколько посредников
], function () {
    /*
     * Главная страница личного кабинета
     */
    Route::get('index', 'IndexController')
        ->name('index');
});
Route::group(['middleware' => 'role:admin'], function() {
    Route::get('/admin/index', function() {
        return 'Это панель управления сайта';
    });
});
/*
 * Панель управления: CRUD-операции над постами, категориями, тегами
 */
Route::group([
    'as' => 'admin.', // имя маршрута, например admin.index
    'prefix' => 'admin', // префикс маршрута, например admin/index
    'namespace' => 'Admin', // пространство имен контроллеров
 'middleware' => ['auth']    // один или несколько посредников
], function () {
    /*
     * CRUD-операции над постами блога
     */
    Route::resource('post', 'PostController', ['except' => ['create', 'store']]);
    // доп.маршрут для показа постов категории
    Route::get('post/category/{category}', 'PostController@category')
        ->name('post.category');
    // доп.маршрут, чтобы разрешить публикацию поста
    Route::get('post/enable/{post}', 'PostController@enable')
        ->name('post.enable');
    // доп.маршрут, чтобы запретить публикацию поста
    Route::get('post/disable/{post}', 'PostController@disable')
        ->name('post.disable');
    /*
    * CRUD-операции над категориями блога
    */
    Route::resource('category', 'CategoryController', ['except' => 'show']);
    /*
     * CRUD-операции над тегами блога
     */
    Route::resource('tag', 'TagController', ['except' => 'show']);
    /*
     * Просмотр и редактирование пользователей
     */
    Route::resource('user', 'UserController', ['except' => [
        'create', 'store', 'show', 'destroy'
    ]]);

    /*
     * CRUD-операции над комментариями
     */
    Route::resource('comment', 'CommentController', ['except' => ['create', 'store']]);
    // доп.маршрут, чтобы разрешить публикацию комментария
    Route::get('comment/enable/{comment}', 'CommentController@enable')
        ->name('comment.enable');
    // доп.маршрут, чтобы запретить публикацию комментария
    Route::get('comment/disable/{comment}', 'CommentController@disable')
        ->name('comment.disable');
    /*
    * Главная страница панели управления
    */
    Route::get('index', 'IndexController')->name('index');
});
