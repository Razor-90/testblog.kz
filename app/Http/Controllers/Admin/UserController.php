<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller {

    public function __construct() {
        $this->middleware('perm:manage-users')->only('index');
        $this->middleware('perm:edit-user')->only(['edit', 'update']);
    }

    /**
     * Показывает список всех пользователей
     */
    public function index() {
        $users = User::paginate(8);
        return view('admin.user.index', compact('users'));
    }

    /**
     * Показывает форму для редактирования пользователя
     */
    public function edit(User $user) {
        return view('admin.user.edit', compact('user'));
    }

    /**
     * Обновляет данные пользователя в базе данных
     */
    public function update(Request $request, User $user) {
        /*
         * Проверяем данные формы
         */
        $this->validator($request->all(), $user->id)->validate();
        /*
         * Обновляем пользователя
         */
        if ($request->change_password) { // если надо изменить пароль
            $request->merge(['password' => Hash::make($request->password)]);
            $user->update($request->all());
        } else {
            $user->update($request->except('password'));
        }
        /*
         * Возвращаемся к списку
         */
        return redirect()
            ->route('admin.user.index')
            ->with('success', 'Данные пользователя успешно обновлены');
    }

    /**
     * Возвращает объект валидатора с нужными нам правилами
     */
    private function validator(array $data, int $id) {
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                // проверка на уникальность email, исключая
                // этого пользователя по идентифкатору
                'unique:users,email,'.$id.',id',
            ],
        ];
        if (isset($data['change_password'])) {
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        }
        return Validator::make($data, $rules);
    }
}
