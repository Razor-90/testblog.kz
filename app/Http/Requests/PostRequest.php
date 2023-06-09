<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest {
    /**
     * Определяет, есть ли права у пользователя на этот запрос
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Возвращает массив правил для проверки полей формы
     *
     * @return array
     */
    public function rules() {
        $unique = 'unique:posts,slug';
        if ('admin.post.update' == $this->route()->getName()) {
            // получаем модель Post через маршрут admin/post/{post}
            $model = $this->route('post');
            /*
             * Проверка на уникальность slug, исключая этот пост по идентифкатору:
             * 1. posts — таблица базы данных, где проверяется уникальность
             * 2. slug — имя колонки, уникальность значения которой проверяется
             * 3. значение, по которому из проверки исключается запись таблицы БД
             * 4. поле, по которому из проверки исключается запись таблицы БД
             * Для проверки будет использован такой SQL-запрос к базе данных:
             * SELECT COUNT(*) FROM `posts` WHERE `slug` = '...' AND `id` <> 17
             */
            $unique = 'unique:posts,slug,'.$model->id.',id';
        }
        return [
            /* ... */
            'category_id' => [
                'required',
                'integer',
                'min:1'
            ],
            /* ... */
        ];
    }

    /**
     * Возвращает массив сообщений об ошибках для заданных правил
     *
     * @return array
     */
    public function messages() {
        return [
            'required' => 'Поле «:attribute» обязательно для заполнения',
            'unique' => 'Такое значение поля «:attribute» уже используется',
            'min' => [
                'string' => 'Поле «:attribute» должно быть не меньше :min символов',
                'integer' => 'Поле «:attribute» должно быть :min или больше',
                'file' => 'Файл «:attribute» должен быть не меньше :min Кбайт'
            ],
            'max' => [
                'string' => 'Поле «:attribute» должно быть не больше :max символов',
                'file' => 'Файл «:attribute» должен быть не больше :max Кбайт'
            ],
            'mimes' => 'Файл «:attribute» должен иметь формат :values',
        ];
    }

    /**
     * Возвращает массив дружественных пользователю названий полей
     *
     * @return array
     */
    public function attributes() {
        return [
            'name' => 'Наименование',
            'slug' => 'ЧПУ (англ.)',
            'category_id' => 'Категория',
            'excerpt' => 'Анонс поста',
            'content' => 'Текст поста',
            'image' => 'Изображение',
        ];
    }

}
