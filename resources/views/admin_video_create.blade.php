@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <a href="/admin">管理者メニュー</a> > 
            <a href="/admin/video">手動で作品を登録・編集する</a><br>
            <hr>
            @if (session('status'))
            <div class="alert alert-danger">
                {{ session('status') }}
            </div>
            @endif
                  
            <form method="POST" action="/admin/video/store">
                @csrf
                <div class="mb-3">
                    <label for="InputId" class="form-label">id</label>
                    <input type="text" name="InputId" value="" class="form-control" readonly ><br>
                    <label for="title" class="form-label">タイトル</label>
                    <input type="text" name="title" value="" class="form-control"><br>
                    <label for="categories" class="form-label">カテゴリ※複数指定できます</label>
                    <select name="categories[]" class="form-select form-control" size="7" multiple aria-label="multiple category">
                        <option value="0" selected>指定なし</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <br>
                    <label for="InputSeason" class="form-label">シーズン</label>
                    <input type="text" name="InputSeason" value="" class="form-control"><br>
                    <label for="InputYear" class="form-label">年</label>
                    <input type="text" name="InputYear" value="" class="form-control"><br>
                    <label for="InputUrl" class="form-label">URL</label>
                    <input type="text" name="InputUrl" value="" class="form-control"><br>
                    <label for="InputDescription" class="form-label">説明</label>
                    <textarea type="text" name="InputDescription" class="form-control" rows="5">
                    </textarea><br>
        
                </div>
                <button type="submit" class="btn btn-primary">登録</button>
            </form>
        </div>
    </div>
</div>
@endsection