@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <a href="/video/download">ダウンロードとDB登録</a><br>

            <a href="/video/index_toprated">高評価された作品</a><br>
            <a href="/video/index_recentlyadd">プライムビデオに最近追加された作品</a><br>
            <a href="/video/index_memberbenefits">プライム会員特典で見放題のプライムビデオ</a><br>
        </div>
    </div>
</div>
@endsection

