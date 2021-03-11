@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <a href="/admin">管理者メニュー</a> > 
            <a href="/admin/video">手動で作品を登録・編集する</a><br>
            <hr>
            <a href="/admin/video/create">新規作成</a>
            <div>
                @foreach($videos as $video)
                <a href="/admin/video/{{ $video->id }}">{{ $video->id }}：{{ $video->title }}：{{ $video->season }}</a><br>
                @endforeach
            </div>
    </div>
    </div>
</div>
@endsection