@extends('layout.admin', ['title' => 'Редактирование поста'])

@section('content')
    <h1>Редактирование поста</h1>
    <form method="post" enctype="multipart/form-data"
          action="{{ route('admin.post.update', ['post' => $post->id]) }}">
        @method('PUT')
        @include('admin.post.part.form')
        @include('admin.part.all-tags')
    </form>
@endsection

