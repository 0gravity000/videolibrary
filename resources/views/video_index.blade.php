@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <a href="/videos">戻る</a><br>
        </div>
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
                    {{ $mastercategories->find($category->category_id)->name }}&nbsp;/&nbsp;
                    @endforeach
                </h6>
                <h6 class="card-subtitle mb-2 text-muted">
                    {{ $video->season }}&nbsp;/&nbsp;{{ $video->year }} 
                </h6>
                <p class="card-text">
                    {{ $video->description }}
                </p>
              <a href="#" class="card-link">Card link</a>
              <a href="#" class="card-link">Another link</a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection


