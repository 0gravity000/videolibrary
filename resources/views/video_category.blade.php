@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="/root">全部</a>
            </li>
            @foreach ($mastercategories as $mastercategory)
            <li class="nav-item">
                <a href="/index/{{ $mastercategory->id }}" class="nav-link
                    @if ($mastercategory->id == $id)
                        active
                    @endif
                    ">
                    {{ $mastercategory->name }}
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    <div class="row">
        <!-- pager -->
        {{ $categories->links() }}

        @foreach ($categories as $category)
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <a href="{{ $category->video->url }}" target="_blank" rel="noopener noreferrer">
                        {{ $category->video->title }}
                    </a>
                </h5>
                <h6 class="card-subtitle mb-2 text-muted">
                    {{ $category->master_category->name }}
                </h6>
                <h6 class="card-subtitle mb-2 text-muted">
                    {{ $category->video->season }}&nbsp;/&nbsp;{{ $category->video->year }} 
                </h6>
                <p class="card-text">
                    {{ $category->video->description }}
                </p>
            </div>
        </div>
        @endforeach

        <!-- pager -->
        {{ $categories->links() }}

    </div>
</div>
@endsection


