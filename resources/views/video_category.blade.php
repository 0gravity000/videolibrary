@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="/videos">全部</a>
            </li>
            @foreach ($categories as $category)
            <li class="nav-item">
                <a href="/index/{{ $category->id }}" class="nav-link
                    @if ($category->id == $id)
                        active
                    @endif
                    ">
                    {{ $category->name }}
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    <div class="row">
        <!-- pager -->
        {{ $videos->links() }}

        @php
            $category = $categories->where('id', $id)->first();
        @endphp      
        @foreach ($videos as $video)
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <a href="{{ $video->url }}" target="_blank" rel="noopener noreferrer">
                        {{ $video->title }}
                    </a>
                </h5>
                <h6 class="card-subtitle mb-2 text-muted">
                    {{ $category->name }}
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

        <!-- 
        @foreach ($videos as $video)
        @foreach ($video->categories as $tmp_category)
        @if($tmp_category->pivot->category_id == $id)
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <a href="{{ $video->url }}" target="_blank" rel="noopener noreferrer">
                        {{ $video->title }}
                    </a>
                </h5>
                <h6 class="card-subtitle mb-2 text-muted">
                    {{ $tmp_category->name }}
                </h6>
                <h6 class="card-subtitle mb-2 text-muted">
                    {{ $video->season }}&nbsp;/&nbsp;{{ $video->year }} 
                </h6>
                <p class="card-text">
                    {{ $video->description }}
                </p>
            </div>
        </div>
        @endif
        @endforeach
        @endforeach

        @foreach ($categories as $category)
        @if($category->id == $id)
        @foreach ($category->videos as $video)
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <a href="{{ $video->url }}" target="_blank" rel="noopener noreferrer">
                        {{ $video->title }}
                    </a>
                </h5>
                <h6 class="card-subtitle mb-2 text-muted">
                    {{ $category->name }}
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
        @endif
        @endforeach
        -->

        <!-- pager -->
        {{ $videos->links() }}

    </div>
</div>
@endsection


