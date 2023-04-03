<?php

namespace App;

use App\Post;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'user_id',
        'post_id',
        'published_by',
        'content',
    ];
    /**
     * Количество комментриев на странице при пагинации
     */
    protected $perPage = 5;

    /**
     * Выбирать из БД только опубликованные комментарии
     */
    public function scopePublished($builder) {
        return $builder->whereNotNull('published_by');
    }
    /**
     * Связь модели Comment с моделью Auth, позволяет получить
     * пользователя (админа), который разрешил комментарий
     */
    public function editor() {
        return $this->belongsTo(User::class, 'published_by');
    }
    /**
     * Связь модели Comment с моделью Auth, позволяет получить
     * пользователя, который оставил комментарий
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }
    /**
     * Возвращает true, если комментарий разрешен
     */
    public function isVisible() {
        return ! is_null($this->published_by);
    }
    /**
     * Номер страницы пагинации, на которой расположен комментарий;
     * учитываются все комментарии, в том числе не опубликованные
     */
    /**
     * Номер страницы пагинации, на которой расположен комментарий;
     * учитываются все комментарии, в том числе не опубликованные
     */
    public function adminPageNumber() {

        if ($this->post) {
            $comments = $this->post->comments()->orderBy('created_at')->get();
        }
        $comments = [];
        if (count($comments) == 0) {
            return 1;
        }
        if ($comments->count() <= $this->getPerPage()) {
            return 1;
        }
        foreach ($comments as $i => $comment) {
            if ($this->id == $comment->id) {
                break;
            }
        }
        return (int) ceil(($i+1) / $this->getPerPage());
    }

}
