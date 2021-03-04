@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <a href="/root">戻る</a><br>
        </div>
    </div>
    <div class="row">
        @foreach ($videos as $video)
        <h1><a href="{{ $video->url }}" target="_blank" rel="noopener noreferrer">
            {{ $video->title }}
        </a></h1>
            @foreach ($video->categories as $category)
            {{ $category->master_category_id }},
            @endforeach
        {{ $video->season }}&nbsp;/&nbsp;
        {{ $video->year }}<br>
        {{ $video->description }}<br>
        @endforeach
    </div>
</div>
@endsection


