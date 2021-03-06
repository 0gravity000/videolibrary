@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="/root">全部</a>
            </li>
            @foreach ($mastercategories as $mastercategory)
            <li class="nav-item">
                <a class="nav-link" href="/index/{{ $mastercategory->id }}">{{ $mastercategory->name }}</a>
            </li>
            @endforeach
        </ul>
    </div>
    <div class="row">
        @foreach ($videos as $video)
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <a href="{{ $video->url }}" target="_blank" rel="noopener noreferrer">
                        {{ $video->title }}
                    </a>
                </h5>
                <h6 class="card-subtitle mb-2 text-muted">
                    @foreach ($video->categories as $category)
                    {{ $mastercategories->find($category->master_category_id)->name }}&nbsp;/&nbsp;
                    @endforeach
                </h6>
                <h6 class="card-subtitle mb-2 text-muted">
                    {{ $video->season }}&nbsp;/&nbsp;{{ $video->year }} 
                </h6>
                <p class="card-text">
                    {{ $video->description }}
                </p>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

