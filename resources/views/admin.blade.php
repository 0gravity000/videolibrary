@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <p>管理者メニュー</p>
            <hr>
            <!--
            <a href="/admin/xxx">手動で作品を登録・編集する</a><br>
            -->
            <a href="/admin/category">カテゴリを登録・編集する</a><br>
        </div>
    </div>
</div>
@endsection