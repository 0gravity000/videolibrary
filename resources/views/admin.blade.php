@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <p>管理者メニュー</p>
            <hr>
            <a href="/videos/manual_download">手動ダウンロード（DB登録）</a><br>
            <a href="/videos/check_year">DB修正（年）</a><br>
            <a href="/admin/video">手動で作品を登録・編集する</a><br>
            <a href="/admin/mastercategory">マスターカテゴリを登録・編集する</a><br>
        </div>
    </div>
</div>
@endsection