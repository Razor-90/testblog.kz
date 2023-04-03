<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'content',
        'image',
    ];

    /**
     * Связь модели Category с моделью Post, позволяет получить все
     * посты, размещенные в текущей категори
     */
    public function posts() {
        return $this->hasMany(Post::class);
    }

    /**
     * Связь модели Category с моделью Category, позволяет получить все
     * дочерние категории текущей категории
     */
    public function children() {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Связь модели Category с моделью Category, позволяет получить
     * родителя текущей категории
     */
    public function parent() {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Возвращает список корневых категорий каталога товаров
     */
    public static function roots() {
        return self::where('parent_id', 0)->get();
    }
    /**
     * Связь модели Category с моделью Category, позволяет получить всех
     * потомков текущей категории
     */
    public function descendants() {
        return $this->hasMany(Category::class, 'parent_id')->with('descendants');
    }

    /**
     * Возвращает список всех категорий блога в виде дерева
     */
    public static function hierarchy() {
        return self::where('parent_id', 0)->with('descendants')->get();
    }
}
