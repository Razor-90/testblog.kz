@extends('layout.admin', ['title' => 'Все пользователи'])

@section('content')
    <h1 class="mb-4">Все пользователи</h1>

    <table class="table table-bordered">
        <tr>
            <th>#</th>
            <th width="20%">Дата регистрации</th>
            <th width="25%">Имя, фамилия</th>
            <th width="20%">Адрес почты</th>
            <th width="15%">Публикаций</th>
            <th width="15%">Комментариев</th>
            <th><i class="fas fa-edit"></i></th>
        </tr>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->created_at }}</td>
                <td>{{ $user->name }}</td>
                <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>

                <td>{{$user->posts->count()}}</td>

                <td>{{ $user->comments ? $user->comments->count() : 0 }}</td>
                <td>
                    @perm('edit-user')
                    <a href="{{ route('admin.user.edit', ['user' => $user->id]) }}">
                        <i class="far fa-edit"></i>
                    </a>
                    @endperm
                </td>
            </tr>
        @endforeach
    </table>
    {{ $users->links() }}
@endsection
