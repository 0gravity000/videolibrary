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
        @foreach ($titles[$i] as $title)
            <h1>{{ $title }}</h1>
        @endforeach
        @foreach ($seasons[$i] as $season)
            {{ $season }}&nbsp;/&nbsp;
        @endforeach
        @foreach ($years[$i] as $year)
            {{ $year }}<br>
        @endforeach
        @foreach ($discribes[$i] as $discribe)
            {{ $discribe }}<br>
        @endforeach
        @endfor
    </div>
</div>
@endsection


