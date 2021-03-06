@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <a href="/admin">管理者メニュー</a> > 
            <a href="/admin/video">手動で作品を登録・編集する</a><br>
            <hr>
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        
            <form method="POST" action="/admin/video/update">
                @csrf
                <div class="mb-3">
                    <label for="InputId" class="form-label">id</label>
                    <input type="text" name="InputId" value="{{ $video->id }}" class="form-control" readonly ><br>
                    <label for="title" class="form-label">タイトル</label>
                    <input type="text" name="title" value="{{ $video->title }}" class="form-control"><br>
                    <p>
                    現在のカテゴリ：
                    @foreach ($video->categories as $category)
                    {{ $mastercategories->find($category->master_category_id)->name }}&nbsp;/&nbsp;
                    @endforeach
                    </p>
                    <label for="categories" class="form-label">変更後のカテゴリ※複数指定できます</label>
                    <select name="categories[]" class="form-select form-control" size="7" multiple aria-label="multiple category">
                        <option value="0" selected>指定なし</option>
                        @foreach ($mastercategories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <br>
                    <label for="InputSeason" class="form-label">シーズン</label>
                    <input type="text" name="InputSeason" value="{{ $video->season }}" class="form-control"><br>
                    <label for="InputYear" class="form-label">年</label>
                    <input type="text" name="InputYear" value="{{ $video->year }}" class="form-control"><br>
                    <label for="InputUrl" class="form-label">URL</label>
                    <input type="text" name="InputUrl" value="{{ $video->url }}" class="form-control"><br>
                    <label for="InputDescription" class="form-label">説明</label>
                    <textarea type="text" name="InputDescription" class="form-control" rows="5">
                        {{ $video->description }}
                    </textarea><br>
        
                </div>
                <button type="submit" class="btn btn-primary">更新</button>
            </form>
        </div>
    </div>
</div>
@endsection