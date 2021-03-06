@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <a href="/admin">管理者メニュー</a> > 
            <a href="/admin/mastercategory">カテゴリを登録・編集する</a><br>
            <hr>
            <form method="POST" action="/admin/mastercategory/store">
                @csrf
                <div class="mb-3">
                    <label for="InputId" class="form-label">id</label>
                    <input type="text" name="InputId" value="" readonly class="form-control"><br>
                    <label for="name" class="form-label">カテゴリ名</label>
                    <input type="text" name="name" value="" class="form-control"><br>
                </div>
                <button type="submit" class="btn btn-primary">登録</button>
            </form>
        </div>
    </div>
</div>
@endsection