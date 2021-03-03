@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <a href="/root">戻る</a>
        </div>
        @for ($i = 0; $i < count($infomations); $i++)
        @foreach ($infomations[$i] as $infomation)
            {{ $infomation }}<br>
        @endforeach
        @endfor
    </div>
</div>
@endsection


