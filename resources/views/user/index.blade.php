@extends('layout.site', ['title' => 'Личный кабинет'])

@section('content')
    <h1>Личный кабинет</h1>
    <p>Добрый день {{ auth()->user()->name }}!</p>
    <p>Это личный кабинет пользователя сайта.</p>
@endsection
