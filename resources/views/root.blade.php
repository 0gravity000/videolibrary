@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="/videos">全部</a>
            </li>
            @foreach ($categories as $category)
            <li class="nav-item">
                <a class="nav-link" href="/index/{{ $category->id }}">{{ $category->name }}</a>
            </li>
            @endforeach
        </ul>
    </div>
    <div class="row">
        <!-- pager -->
        {{ $videos->links() }}

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
                    {{ $category->name }}&nbsp;/&nbsp;
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

        <!-- pager -->
        {{ $videos->links() }}
        
    </div>
</div>
@endsection

