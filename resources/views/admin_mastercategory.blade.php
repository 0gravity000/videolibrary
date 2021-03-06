@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <a href="/admin">管理者メニュー</a> > 
            <a href="/admin/mastercategory">カテゴリを登録・編集する</a><br>
            <hr>
            <a href="/admin/mastercategory/create">新規作成</a>
            <div>
                @foreach($mastercategories as $category)
                <a href="/admin/mastercategory/{{ $category->id }}">{{ $category->id }}：{{ $category->name }}</a><br>
                @endforeach
            </div>
    </div>
    </div>
</div>
@endsection