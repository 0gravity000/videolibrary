@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <a href="/root">戻る</a><br>
        </div>
    </div>
    <div class="row">
        @for ($i = 0; $i < count($titles); $i++)
        <h1><a href="https://amazon.co.jp{{ $urls[$i][0] }}" target="_blank" rel="noopener noreferrer">
            {{ $titles[$i][0] }}
        </a></h1>
        {{ $seasons[$i][0] }}&nbsp;/&nbsp;
        {{ $years[$i][0] }}<br>
        {{ $discribes[$i][0] }}<br>
        @endfor
    </div>
</div>
@endsection


