<?php
namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;

class CategoryController extends Controller {

    public function __construct() {
        $this->middleware('perm:manage-categories')->only('index');
        $this->middleware('perm:create-category')->only(['create', 'store']);
        $this->middleware('perm:edit-category')->only(['edit', 'update']);
        $this->middleware('perm:delete-category')->only('destroy');
    }

    /**
     * Показывает список всех категорий
     */
    public function index() {
        $items = Category::all();
        return view('admin.category.index', compact('items'));
    }

    /**
     * Показывает форму для создания категории
     */
    public function create() {
        $items = Category::all();
        return view('admin.category.create', compact('items'));
    }

    /**
     * Сохраняет новую категорию в базу данных
     */
    public function store(CategoryRequest $request) {
        $category = Category::create($request->all());
        return redirect()
            ->route('admin.category.index', ['category' => $category->id])
            ->with('success', 'Новая категория успешно создана');
    }

    /**
     * Показывает форму для редактирования категории
     */
    public function edit(Category $category) {
        $items = Category::all();
        return view('admin.category.edit', compact('category', 'items'));
    }

    /**
     * Обновляет категорию блога в базе данных
     */
    public function update(CategoryRequest $request, Category $category) {
        $category->update($request->all());
        return redirect()
            ->route('admin.category.index')
            ->with('success', 'Категория была успешно исправлена');
    }

    /**
     * Удаляет категорию блога
     */
    public function destroy(Category $category) {
        if ($category->children->count()) {
            $errors[] = 'Нельзя удалить категорию с дочерними категориями';
        }
        if ($category->posts->count()) {
            $errors[] = 'Нельзя удалить категорию, которая содержит посты';
        }
        if (!empty($errors)) {
            return back()->withErrors($errors);
        }
        $category->delete();
        return redirect()
            ->route('admin.category.index')
            ->with('success', 'Категория блога успешно удалена');
    }

}
