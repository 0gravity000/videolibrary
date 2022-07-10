<?php

namespace App\Listeners;

use App\Events\DailyCheckAmazonPrimeVideo01;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Goutte\Client;
use App\Video;
use App\Category;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StoreInfoInDB01
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  DailyCheckAmazonPrimeVideo01  $event
     * @return void
     */
    public function handle(DailyCheckAmazonPrimeVideo01 $event)
    {
        //
        $client = new Client();

        //20件分のデータしか取得できない
        //高評価の作品
        $url[1] = 'https://www.amazon.co.jp/gp/video/search/ref=atv_cat_auto_v2_top_rated_quest?ie=UTF8&phrase=%E9%AB%98%E8%A9%95%E4%BE%A1%E3%81%95%E3%82%8C%E3%81%9F%E4%BD%9C%E5%93%81&pageId=default&queryToken=eyJ0eXBlIjoicXVlcnkiLCJuYXYiOmZhbHNlLCJwdCI6ImJyb3dzZSIsInBpIjoiZGVmYXVsdCIsInNlYyI6ImNlbnRlciIsInN0eXBlIjoic2VhcmNoIiwicXJ5Ijoibm9kZT0yMzUxNjQ5MDUxJmJibj0yMzUxNjQ5MDUxJmZpZWxkLXJldmlldy1yYXRpbmc9Mjc2MTYyNzA1MSZwX25fd2F5c190b193YXRjaD0zNzQ2MzMwMDUxJnNlYXJjaC1hbGlhcz1pbnN0YW50LXZpZGVvIiwidHh0Ijoi6auY6KmV5L6h44GV44KM44Gf5L2c5ZOBIiwib2Zmc2V0IjowLCJucHNpIjozMH0%3D&queryPageType=browse';
        //プライムに最近追加された作品
        $url[2] = 'https://www.amazon.co.jp/gp/video/search/ref=atv_cat_jp_recently_added_to_prime_quest?ie=UTF8&pageId=default&queryToken=eyJ0eXBlIjoicXVlcnkiLCJuYXYiOmZhbHNlLCJwdCI6ImJyb3dzZSIsInBpIjoiZGVmYXVsdCIsInNlYyI6ImNlbnRlciIsInN0eXBlIjoic2VhcmNoIiwicXJ5IjoiYmJuPTM5ODUyODkwNTEmc2VhcmNoLWFsaWFzPWluc3RhbnQtdmlkZW8mbm9kZT0zOTg1Mjg5MDUxJnBfbl93YXlzX3RvX3dhdGNoPTM3NDYzMzAwNTEiLCJ0eHQiOiLjg5fjg6njgqTjg6DjgavmnIDov5Hov73liqDjgZXjgozjgZ%2FkvZzlk4EiLCJvZmZzZXQiOjAsIm5wc2kiOjMwfQ%3D%3D&phrase=%E3%83%97%E3%83%A9%E3%82%A4%E3%83%A0%E3%81%AB%E6%9C%80%E8%BF%91%E8%BF%BD%E5%8A%A0%E3%81%95%E3%82%8C%E3%81%9F%E4%BD%9C%E5%93%81&queryPageType=browse';
        //プライム会員特典
        $url[3] = 'https://www.amazon.co.jp/gp/video/search/ref=atv_cat_facettext_quest?ie=UTF8&pageId=default&phrase=%E3%83%97%E3%83%A9%E3%82%A4%E3%83%A0%E4%BC%9A%E5%93%A1%E7%89%B9%E5%85%B8&queryToken=eyJ0eXBlIjoicXVlcnkiLCJuYXYiOmZhbHNlLCJwdCI6ImJyb3dzZSIsInBpIjoiZGVmYXVsdCIsInNlYyI6ImNlbnRlciIsInN0eXBlIjoic2VhcmNoIiwicXJ5Ijoic2VhcmNoLWFsaWFzPWluc3RhbnQtdmlkZW8mbm9kZT0yMzUxNjQ5MDUxJmJxPSUyOG5vdCUyMGdlbnJlOiUyN2F2X2dlbnJlX2tpZHMlMjclMjkmcF9uX3dheXNfdG9fd2F0Y2g9Mzc0NjMzMDA1MSIsInR4dCI6IuODl%2BODqeOCpOODoOS8muWToeeJueWFuCIsIm9mZnNldCI6MCwibnBzaSI6MzB9&queryPageType=browse';
        //世界の映画
        $url[4] = 'https://www.amazon.co.jp/gp/video/search/ref=atv_cat_genre_worldcinema_quest?ie=UTF8&phrase=%E4%B8%96%E7%95%8C%E3%81%AE%E6%98%A0%E7%94%BB&pageId=default&queryToken=eyJ0eXBlIjoicXVlcnkiLCJuYXYiOmZhbHNlLCJwdCI6ImJyb3dzZSIsInBpIjoiZGVmYXVsdCIsInNlYyI6ImNlbnRlciIsInN0eXBlIjoic2VhcmNoIiwicXJ5Ijoibm9kZT0yMzUxNjQ5MDUxJmJibj0yMzUxNjQ5MDUxJmZpZWxkLXRoZW1lX2Jyb3dzZS1iaW49NDQzNTU1NjA1MSZwX25fd2F5c190b193YXRjaD0zNzQ2MzMwMDUxJnNlYXJjaC1hbGlhcz1pbnN0YW50LXZpZGVvIiwidHh0Ijoi5LiW55WM44Gu5pig55S7Iiwib2Zmc2V0IjowLCJucHNpIjozMH0%3D&queryPageType=browse';
        //日本の映画
        $url[5] = 'https://www.amazon.co.jp/gp/video/search/ref=atv_cat_avd_dg_genre_japanese_movies_quest?phrase=%E6%97%A5%E6%9C%AC%E6%98%A0%E7%94%BB&ie=UTF8&queryToken=eyJ0eXBlIjoicXVlcnkiLCJuYXYiOmZhbHNlLCJwdCI6ImJyb3dzZSIsInBpIjoiZGVmYXVsdCIsInNlYyI6ImNlbnRlciIsInN0eXBlIjoic2VhcmNoIiwicXJ5Ijoibm9kZT01ODc4ODQ4MDUxJmZpZWxkLWZlYXR1cmVfc2l4X2Jyb3dzZS1iaW49NTg3MTQ3MjA1MSZiYm49NTg3ODg0ODA1MSZwX25fd2F5c190b193YXRjaD0zNzQ2MzMwMDUxJmJxPSUyOGFuZCUyMCUyOG5vdCUyMGdlbnJlOiUyN2F2X2dlbnJlX2FuaW1lJTI3JTI5JTIwJTI4bm90JTIwZ2VucmU6JTI3YXZfZ2VucmVfZXJvdGljJTI3JTI5JTIwJTI4bm90JTIwZ2VucmU6JTI3YXZfc3ViZ2VucmVfYWN0aW9uX3Rva3VzYXRzdSUyNyUyOSUyMCUyOG5vdCUyMGdlbnJlOiUyN2F2X3N1YmdlbnJlX2ludGVybmF0aW9uYWxfamFwYW4lMjclMjklMjkmc2VhcmNoLWFsaWFzPWluc3RhbnQtdmlkZW8iLCJ0eHQiOiLml6XmnKzmmKDnlLsiLCJvZmZzZXQiOjAsIm5wc2kiOjMwfQ%3D%3D&pageId=default&queryPageType=browse';
        //海外ドラマ
        $url[6] = 'https://www.amazon.co.jp/gp/video/search/ref=atv_cat_foreign_drama_quest?phrase=%E6%B5%B7%E5%A4%96%E3%83%89%E3%83%A9%E3%83%9E&ie=UTF8&pageId=default&queryToken=eyJ0eXBlIjoicXVlcnkiLCJuYXYiOmZhbHNlLCJwdCI6ImJyb3dzZSIsInBpIjoiZGVmYXVsdCIsInNlYyI6ImNlbnRlciIsInN0eXBlIjoic2VhcmNoIiwicXJ5IjoiYnE9Z2VucmU6JTI3YXZfZ2VucmVfZHJhbWElMjcmZmllbGQtZmVhdHVyZV9zaXhfYnJvd3NlLWJpbj01ODcxNDczMDUxJm5vZGU9NTg3ODg0OTA1MSZiYm49NTg3ODg0OTA1MSZwX25fd2F5c190b193YXRjaD0zNzQ2MzMwMDUxIiwidHh0Ijoi5rW35aSW44OJ44Op44OeIiwib2Zmc2V0IjowLCJucHNpIjozMH0%3D&queryPageType=browse';
        //日本ドラマ
        $url[7] = 'https://www.amazon.co.jp/gp/video/search/ref=atv_cat_auto_v2_japan_drama_quest?ie=UTF8&pageId=default&queryToken=eyJ0eXBlIjoicXVlcnkiLCJuYXYiOmZhbHNlLCJwdCI6ImJyb3dzZSIsInBpIjoiZGVmYXVsdCIsInNlYyI6ImNlbnRlciIsInN0eXBlIjoic2VhcmNoIiwicXJ5IjoiYnE9JTI4YW5kJTIwZ2VucmU6JTI3YXZfZ2VucmVfZHJhbWElMjclMjAlMjhub3QlMjB0aXRsZTolMjclRTUlOTAlQjklRTYlOUIlQkYlMjclMjklMjkmZmllbGQtZmVhdHVyZV9zaXhfYnJvd3NlLWJpbj01ODcxNDcyMDUxJm5vZGU9NTg3ODg0OTA1MSZiYm49NTg3ODg0OTA1MSZwX25fd2F5c190b193YXRjaD0zNzQ2MzMwMDUxJnNlYXJjaC1hbGlhcz1pbnN0YW50LXZpZGVvIiwidHh0Ijoi5pel5pys44Gu44OJ44Op44OeIiwib2Zmc2V0IjowLCJucHNpIjozMH0%3D&queryPageType=browse&phrase=%E6%97%A5%E6%9C%AC%E3%81%AE%E3%83%89%E3%83%A9%E3%83%9E';
        //韓国ドラマ
        $url[8] = 'https://www.amazon.co.jp/gp/video/search/ref=atv_cat_auto_v2_korea_drama_quest?ie=UTF8&phrase=%E9%9F%93%E5%9B%BD%E3%81%AE%E3%83%89%E3%83%A9%E3%83%9E&pageId=default&queryToken=eyJ0eXBlIjoicXVlcnkiLCJuYXYiOmZhbHNlLCJwdCI6ImJyb3dzZSIsInBpIjoiZGVmYXVsdCIsInNlYyI6ImNlbnRlciIsInN0eXBlIjoic2VhcmNoIiwicXJ5Ijoibm9kZT0yMzUxNjQ5MDUxJmJxPWdlbnJlOiUyN2F2X2dlbnJlX2RyYW1hJTI3JmJibj0yMzUxNjQ5MDUxJmZpZWxkLWZlYXR1cmVfc2l4X2Jyb3dzZS1iaW49NTg3MTUzODA1MSZwX25fd2F5c190b193YXRjaD0zNzQ2MzMwMDUxJnNlYXJjaC1hbGlhcz1pbnN0YW50LXZpZGVvIiwidHh0Ijoi6Z%2BT5Zu944Gu44OJ44Op44OeIiwib2Zmc2V0IjowLCJucHNpIjozMH0%3D&queryPageType=browse';
        //アクション・アドベンチャー
        $url[9] = 'https://www.amazon.co.jp/gp/video/search/ref=atv_cat_genre_actionadventure_quest?ie=UTF8&pageId=default&phrase=%E3%82%A2%E3%82%AF%E3%82%B7%E3%83%A7%E3%83%B3%E3%83%BB%E3%82%A2%E3%83%89%E3%83%99%E3%83%B3%E3%83%81%E3%83%A3%E3%83%BC&queryToken=eyJ0eXBlIjoicXVlcnkiLCJuYXYiOmZhbHNlLCJwdCI6ImJyb3dzZSIsInBpIjoiZGVmYXVsdCIsInNlYyI6ImNlbnRlciIsInN0eXBlIjoic2VhcmNoIiwicXJ5Ijoibm9kZT0yMzUxNjQ5MDUxJmJibj0yMzUxNjQ5MDUxJmZpZWxkLXRoZW1lX2Jyb3dzZS1iaW49NDQzNTUyMzA1MSZwX25fd2F5c190b193YXRjaD0zNzQ2MzMwMDUxJnNlYXJjaC1hbGlhcz1pbnN0YW50LXZpZGVvIiwidHh0Ijoi44Ki44Kv44K344On44Oz44O744Ki44OJ44OZ44Oz44OB44Oj44O8Iiwib2Zmc2V0IjowLCJucHNpIjozMH0%3D&queryPageType=browse';
        //アニメ
        $url[10] = 'https://www.amazon.co.jp/gp/video/search/ref=atv_cat_genre_anime_quest?ie=UTF8&pageId=default&queryToken=eyJ0eXBlIjoicXVlcnkiLCJuYXYiOmZhbHNlLCJwdCI6ImJyb3dzZSIsInBpIjoiZGVmYXVsdCIsInNlYyI6ImNlbnRlciIsInN0eXBlIjoic2VhcmNoIiwicXJ5Ijoibm9kZT0yMzUxNjQ5MDUxJmJibj0yMzUxNjQ5MDUxJmZpZWxkLXRoZW1lX2Jyb3dzZS1iaW49NDQzNTUyNDA1MSZwX25fd2F5c190b193YXRjaD0zNzQ2MzMwMDUxJnNlYXJjaC1hbGlhcz1pbnN0YW50LXZpZGVvIiwidHh0Ijoi44Ki44OL44OhIiwib2Zmc2V0IjowLCJucHNpIjozMH0%3D&phrase=%E3%82%A2%E3%83%8B%E3%83%A1&queryPageType=browse';
        //コメディ
        $url[11] = 'https://www.amazon.co.jp/gp/video/search/ref=atv_cat_genre_comedy_quest?ie=UTF8&phrase=%E3%82%B3%E3%83%A1%E3%83%87%E3%82%A3&pageId=default&queryToken=eyJ0eXBlIjoicXVlcnkiLCJuYXYiOmZhbHNlLCJwdCI6ImJyb3dzZSIsInBpIjoiZGVmYXVsdCIsInNlYyI6ImNlbnRlciIsInN0eXBlIjoic2VhcmNoIiwicXJ5Ijoibm9kZT0yMzUxNjQ5MDUxJmJibj0yMzUxNjQ5MDUxJnBfbl93YXlzX3RvX3dhdGNoPTM3NDYzMzAwNTEmZmllbGQtdGhlbWVfYnJvd3NlLWJpbj00NDM1NTI3MDUxJnNlYXJjaC1hbGlhcz1pbnN0YW50LXZpZGVvIiwidHh0Ijoi44Kz44Oh44OH44KjIiwib2Zmc2V0IjowLCJucHNpIjozMH0%3D&queryPageType=browse';
        //ドキュメンタリー
        $url[12] = 'https://www.amazon.co.jp/gp/video/search/ref=atv_cat_documentaries_quest?ie=UTF8&phrase=%E3%83%89%E3%82%AD%E3%83%A5%E3%83%A1%E3%83%B3%E3%82%BF%E3%83%AA%E3%83%BC&pageId=default&queryToken=eyJ0eXBlIjoicXVlcnkiLCJuYXYiOmZhbHNlLCJwdCI6ImJyb3dzZSIsInBpIjoiZGVmYXVsdCIsInNlYyI6ImNlbnRlciIsInN0eXBlIjoic2VhcmNoIiwicXJ5Ijoibm9kZT0yMzUxNjQ5MDUxJmJibj0yMzUxNjQ5MDUxJmZpZWxkLXRoZW1lX2Jyb3dzZS1iaW49NDQzNTUyODA1MSZwX25fd2F5c190b193YXRjaD0zNzQ2MzMwMDUxJnNlYXJjaC1hbGlhcz1pbnN0YW50LXZpZGVvIiwidHh0Ijoi44OJ44Kt44Ol44Oh44Oz44K%2F44Oq44O8Iiwib2Zmc2V0IjowLCJucHNpIjozMH0%3D&queryPageType=browse';
        //ファンタジー
        $url[13] = 'https://www.amazon.co.jp/gp/video/search/ref=atv_cat_genre_fantasy_quest?ie=UTF8&pageId=default&phrase=%E3%83%95%E3%82%A1%E3%83%B3%E3%82%BF%E3%82%B8%E3%83%BC&queryPageType=browse&queryToken=eyJ0eXBlIjoicXVlcnkiLCJuYXYiOmZhbHNlLCJwdCI6ImJyb3dzZSIsInBpIjoiZGVmYXVsdCIsInNlYyI6ImNlbnRlciIsInN0eXBlIjoic2VhcmNoIiwicXJ5Ijoibm9kZT0yMzUxNjQ5MDUxJmJibj0yMzUxNjQ5MDUxJmZpZWxkLXRoZW1lX2Jyb3dzZS1iaW49NDQzNTUzMzA1MSZwX25fd2F5c190b193YXRjaD0zNzQ2MzMwMDUxJnNlYXJjaC1hbGlhcz1pbnN0YW50LXZpZGVvIiwidHh0Ijoi44OV44Kh44Oz44K%2F44K444O8Iiwib2Zmc2V0IjowLCJucHNpIjozMH0%3D';
        //ホラー
        $url[14] = 'https://www.amazon.co.jp/gp/video/search/ref=atv_cat_genre_horror_quest?ie=UTF8&queryToken=eyJ0eXBlIjoicXVlcnkiLCJuYXYiOmZhbHNlLCJwdCI6ImJyb3dzZSIsInBpIjoiZGVmYXVsdCIsInNlYyI6ImNlbnRlciIsInN0eXBlIjoic2VhcmNoIiwicXJ5Ijoibm9kZT0yMzUxNjQ5MDUxJmJibj0yMzUxNjQ5MDUxJmZpZWxkLXRoZW1lX2Jyb3dzZS1iaW49NDQzNTUzODA1MSZwX25fd2F5c190b193YXRjaD0zNzQ2MzMwMDUxJnNlYXJjaC1hbGlhcz1pbnN0YW50LXZpZGVvIiwidHh0Ijoi44Ob44Op44O8Iiwib2Zmc2V0IjowLCJucHNpIjozMH0%3D&pageId=default&phrase=%E3%83%9B%E3%83%A9%E3%83%BC&queryPageType=browse';
        //ミリタリー・戦争
        $url[15] = 'https://www.amazon.co.jp/gp/video/search/ref=atv_cat_genre_military_and_war_quest?ie=UTF8&pageId=default&queryToken=eyJ0eXBlIjoicXVlcnkiLCJuYXYiOmZhbHNlLCJwdCI6ImJyb3dzZSIsInBpIjoiZGVmYXVsdCIsInNlYyI6ImNlbnRlciIsInN0eXBlIjoic2VhcmNoIiwicXJ5Ijoibm9kZT0yMzUxNjQ5MDUxJmZpZWxkLXRoZW1lX2Jyb3dzZS1iaW49NDQzNTU0NDA1MSZiYm49MjM1MTY0OTA1MSZwX25fd2F5c190b193YXRjaD0zNzQ2MzMwMDUxJnNlYXJjaC1hbGlhcz1pbnN0YW50LXZpZGVvIiwidHh0Ijoi44Of44Oq44K%2F44Oq44O844O75oim5LqJIiwib2Zmc2V0IjowLCJucHNpIjozMH0%3D&phrase=%E3%83%9F%E3%83%AA%E3%82%BF%E3%83%AA%E3%83%BC%E3%83%BB%E6%88%A6%E4%BA%89&queryPageType=browse';
        //ミステリー・スリラー
        $url[16] = 'https://www.amazon.co.jp/gp/video/search/ref=atv_cat_genre_thriller_quest?ie=UTF8&pageId=default&queryToken=eyJ0eXBlIjoicXVlcnkiLCJuYXYiOmZhbHNlLCJwdCI6ImJyb3dzZSIsInBpIjoiZGVmYXVsdCIsInNlYyI6ImNlbnRlciIsInN0eXBlIjoic2VhcmNoIiwicXJ5Ijoibm9kZT0yMzUxNjQ5MDUxJmJibj0yMzUxNjQ5MDUxJmZpZWxkLXRoZW1lX2Jyb3dzZS1iaW49NDQzNTU0NzA1MSZwX25fd2F5c190b193YXRjaD0zNzQ2MzMwMDUxJnNlYXJjaC1hbGlhcz1pbnN0YW50LXZpZGVvIiwidHh0Ijoi44Of44K544OG44Oq44O844O744K544Oq44Op44O8Iiwib2Zmc2V0IjowLCJucHNpIjozMH0%3D&phrase=%E3%83%9F%E3%82%B9%E3%83%86%E3%83%AA%E3%83%BC%E3%83%BB%E3%82%B9%E3%83%AA%E3%83%A9%E3%83%BC&queryPageType=browse';
        //ロマンス
        $url[17] = 'https://www.amazon.co.jp/gp/video/search/ref=atv_cat_genre_romance_quest?ie=UTF8&queryToken=eyJ0eXBlIjoicXVlcnkiLCJuYXYiOmZhbHNlLCJwdCI6ImJyb3dzZSIsInBpIjoiZGVmYXVsdCIsInNlYyI6ImNlbnRlciIsInN0eXBlIjoic2VhcmNoIiwicXJ5Ijoibm9kZT0yMzUxNjQ5MDUxJmJibj0yMzUxNjQ5MDUxJmZpZWxkLXRoZW1lX2Jyb3dzZS1iaW49NDQzNTU1MDA1MSZwX25fd2F5c190b193YXRjaD0zNzQ2MzMwMDUxJnNlYXJjaC1hbGlhcz1pbnN0YW50LXZpZGVvIiwidHh0Ijoi44Ot44Oe44Oz44K5Iiwib2Zmc2V0IjowLCJucHNpIjozMH0%3D&phrase=%E3%83%AD%E3%83%9E%E3%83%B3%E3%82%B9&pageId=default&queryPageType=browse';
        //SF
        $url[18] = 'https://www.amazon.co.jp/gp/video/search/ref=atv_cat_genre_science_fiction_quest?phrase=SF&ie=UTF8&queryToken=eyJ0eXBlIjoicXVlcnkiLCJuYXYiOmZhbHNlLCJwdCI6ImJyb3dzZSIsInBpIjoiZGVmYXVsdCIsInNlYyI6ImNlbnRlciIsInN0eXBlIjoic2VhcmNoIiwicXJ5Ijoibm9kZT0yMzUxNjQ5MDUxJmJibj0yMzUxNjQ5MDUxJmZpZWxkLXRoZW1lX2Jyb3dzZS1iaW49NDQzNTU1MjA1MSZwX25fd2F5c190b193YXRjaD0zNzQ2MzMwMDUxJnNlYXJjaC1hbGlhcz1pbnN0YW50LXZpZGVvIiwidHh0IjoiU0YiLCJvZmZzZXQiOjAsIm5wc2kiOjMwfQ%3D%3D&pageId=default&queryPageType=browse';
        
        for ($urlidx=1; $urlidx < 19 ; $urlidx++) { 
            $crawler = $client->request('GET', $url[$urlidx]);
            //dd($crawler);

            //1-20件分のデータがある
            for ($idx=1; $idx < 21 ; $idx++) { 
                //タイトル
                try {
                    //#av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child(1) > div > div._38SAO3.tst-hover-container._1pYuE7._1aBOAx > div._1y15Fl.dvui-beardContainer.D0Lu_p.av-grid-beard > div._1N2P-J.mustache._2mxudr > div._2hMXwV > div.vRplU5 > span > a
                    //#av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child(1) > div > div._38SAO3.tst-hover-container._1pYuE7._1aBOAx > div.lAtJLC > div > div:nth-child(3) > div > h3
                    $titles[$idx-1] = $crawler->filter('#av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child('.$idx.')')->each(function ($node) {
                        $tmp = $node->filter('span > a')->text();
                        return $tmp;
                    });
                } catch (\Exception $e) {
                    $titles[$idx-1][0] = "";
                }
                //url
                try {
                    $urls[$idx-1] = $crawler->filter('#av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child('.$idx.')')->each(function ($node) {
                    $tmp = $node->filter('span > a')->attr('href');
                    return $tmp;
                    });
                } catch (\Exception $e) {
                    $urls[$idx-1][0] = "";
                }
                //シーズン
                try {
                    //#av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child(1) > div > div._38SAO3.tst-hover-container._1pYuE7._1aBOAx > div._1y15Fl.dvui-beardContainer.D0Lu_p.av-grid-beard > div._1N2P-J.mustache._2mxudr > div._2hMXwV > div._27-0OW.dvui-beard-second-line > div > div > div:nth-child(1)
                    //#av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child(1) > div > div._38SAO3.tst-hover-container._1pYuE7._1aBOAx > div._1y15Fl.dvui-beardContainer.D0Lu_p.av-grid-beard > div._1N2P-J.mustache._2mxudr > div._2hMXwV > div._27-0OW.dvui-beard-second-line > div > div > div:nth-child(1)
                    //#av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child(2) > div > div._38SAO3.tst-hover-container._1pYuE7._1aBOAx > div._1y15Fl.dvui-beardContainer.D0Lu_p.av-grid-beard > div._1N2P-J.mustache._2mxudr > div._2hMXwV > div._27-0OW.dvui-beard-second-line > div > div > div:nth-child(1)
                    //#av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child(9) > div > div._38SAO3.tst-hover-container._1pYuE7._1aBOAx > div._1y15Fl.dvui-beardContainer.D0Lu_p.av-grid-beard > div._1N2P-J.mustache._2mxudr > div._2hMXwV > div._27-0OW.dvui-beard-second-line > div > div > div:nth-child(1) > div.imdb-logo
                    //#av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child(9) > div > div._38SAO3.tst-hover-container._1pYuE7._1aBOAx > div._1y15Fl.dvui-beardContainer.D0Lu_p.av-grid-beard > div._1N2P-J.mustache._2mxudr > div._2hMXwV > div._27-0OW.dvui-beard-second-line > div > div > div:nth-child(1) > div:nth-child(2)
                    $seasons[$idx-1] = $crawler->filter('#av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child('.$idx.')')->each(function ($node) {
                        $tmp = $node->filter('div._27-0OW.dvui-beard-second-line > div > div > div:nth-child(1)')->text();
                        //dd($tmp);
                        return $tmp;
                    });
                    //「シーズンx」でない場合があるため、「シーズン」文字列を含まない場合、空文字に時間する
                    //dd($seasons[$idx-1][0]);
                    if(mb_strpos($seasons[$idx-1][0], "シーズン") == false) {
                        $seasons[$idx-1][0] = "";
                    }
                    //dd($seasons[$idx-1][0]);
                } catch (\Exception $e) {
                    $seasons[$idx-1][0] = "";
                }
                //年
                try {
                    //#av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child(1) > div > div._38SAO3.tst-hover-container._1pYuE7._1aBOAx > div._1y15Fl.dvui-beardContainer.D0Lu_p.av-grid-beard > div._1N2P-J.mustache._2mxudr > div._2hMXwV > div._27-0OW.dvui-beard-second-line > div > div > div:nth-child(2)
                    //#av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child(1) > div > div._38SAO3.tst-hover-container._1pYuE7._1aBOAx > div.lAtJLC > div > div:nth-child(4) > div > div.nPUwP8._2Z_R2D
                    //#av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child(2) > div > div._38SAO3.tst-hover-container._1pYuE7._1aBOAx > div.lAtJLC > div > div:nth-child(4) > div > div:nth-child(2)
                    //#av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child(4) > div > div._38SAO3.tst-hover-container._1pYuE7._1aBOAx > div.lAtJLC > div > div:nth-child(4) > div > div:nth-child(2)
                    //#av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child(8) > div > div._38SAO3.tst-hover-container._1pYuE7._1aBOAx > div.lAtJLC > div > div:nth-child(4) > div > div.nPUwP8._2Z_R2D
                    //#av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child(9) > div > div._38SAO3.tst-hover-container._1pYuE7._1aBOAx > div.lAtJLC > div > div:nth-child(4) > div > div:nth-child(3)
                    //#av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child(1) > div > div._38SAO3.tst-hover-container._1pYuE7._1aBOAx > div.lAtJLC > div > div:nth-child(4) > div > div:nth-child(2)
                    //#av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child(12) > div > div._38SAO3.tst-hover-container._1pYuE7._1aBOAx > div.lAtJLC > div > div:nth-child(4) > div > div:nth-child(3)
                    //#av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child(13) > div > div._38SAO3.tst-hover-container._1pYuE7._1aBOAx > div.lAtJLC > div > div:nth-child(4) > div > div:nth-child(2)
                    $years[$idx-1] = $crawler->filter('#av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child('.$idx.')')->each(function ($node) {
                        $tmp = "";
                        //div:nth-child(1)~(4)分ループ どこかに「年」が格納される確率が高い
                        for ($i=1; $i < 5; $i++) { 
                            $isyear = $node->filter('div.lAtJLC > div > div:nth-child(4) > div > div:nth-child('.$i.')')->text();
                            //「年」のチェック処理 4桁の数値なら年とみなす
                            if (preg_match('/\d{4}/', $isyear)) {
                                $tmp = $isyear;
                                //dd($isyear);
                                break;
                            }
                        }
                        dd($tmp);
                        return $tmp;
                    });
                } catch (\Exception $e) {
                    $years[$idx-1][0] = "";
                }
                //説明
                try {
                    $discribes[$idx-1] = $crawler->filter('#av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child('.$idx.')')->each(function ($node) {
                    $tmp = $node->filter('p')->text();
                    return $tmp;
                    });
                } catch (\Exception $e) {
                    $discribes[$idx-1][0] = "";
                }

                //DBに登録
                foreach ($titles[$idx-1] as $title) {
                    //videoテーブルに登録
                    //同一タイトルも同一URLなし
                    if (Video::where('title', $title)->where('url', $urls[$idx-1][0])->doesntExist()) {
                        //dd($title);
                        //レコード新規作成 
                        $video = new Video;
                    } else {
                        //同一タイトルまたは同一URLが存在する場合
                        //同一タイトルまたは同一URLが存在し、かつ同一シーズンがない
                        if (Video::where('title', $title)->orwhere('url', $urls[$idx-1][0])->where('season', $seasons[$idx-1][0])->doesntExist()) {
                            //レコード新規作成 
                            $video = new Video;
                            //dd($video);
                        } else {
                            //同一タイトルまたは同一URLが存在し、かつ同一シーズンがない
                            //レコード更新
                            $video = Video::where('title', $title)->orwhere('url', $urls[$idx-1][0])->where('season', $seasons[$idx-1][0])->first();
                        }
                    }
                    $video->title = $title;
                    $video->url = "https://amazon.co.jp".$urls[$idx-1][0];
                    $video->season = $seasons[$idx-1][0];
                    $video->year = $years[$idx-1][0];
                    $video->description = $discribes[$idx-1][0];
                    $video->save();

                    //category_videoテーブルに登録
                    //video_idなし
                    if (DB::table('category_video')->where('video_id', $video->id)->doesntExist()) {
                        //レコード新規作成
                        DB::table('category_video')->insert(
                            [
                                'category_id' => $urlidx,
                                'video_id' => $video->id,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now()
                            ]
                        );
                    } else {
                        //video_idありの場合
                        //category_idなし
                        if (DB::table('category_video')->where('category_id', $urlidx)->doesntExist()) {
                            //レコード新規作成
                            DB::table('category_video')->insert(
                                [
                                    'category_id' => $urlidx,
                                    'video_id' => $video->id,
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now()
                                ]
                            );
                        }
                    }

                }
            }
        }   //urlループend

        //dd($titles);
        //dd($seasons);
        //dd($years);
        //dd($discribes);

        /*
        プライムに最近追加された作品　20件?
        https://www.amazon.co.jp/gp/video/search/ref=atv_cat_jp_recently_added_to_prime_quest?ie=UTF8&pageId=default&queryToken=eyJ0eXBlIjoicXVlcnkiLCJuYXYiOmZhbHNlLCJwdCI6ImJyb3dzZSIsInBpIjoiZGVmYXVsdCIsInNlYyI6ImNlbnRlciIsInN0eXBlIjoic2VhcmNoIiwicXJ5IjoiYmJuPTM5ODUyODkwNTEmc2VhcmNoLWFsaWFzPWluc3RhbnQtdmlkZW8mbm9kZT0zOTg1Mjg5MDUxJnBfbl93YXlzX3RvX3dhdGNoPTM3NDYzMzAwNTEiLCJ0eHQiOiLjg5fjg6njgqTjg6DjgavmnIDov5Hov73liqDjgZXjgozjgZ%2FkvZzlk4EiLCJvZmZzZXQiOjAsIm5wc2kiOjMwfQ%3D%3D&phrase=%E3%83%97%E3%83%A9%E3%82%A4%E3%83%A0%E3%81%AB%E6%9C%80%E8%BF%91%E8%BF%BD%E5%8A%A0%E3%81%95%E3%82%8C%E3%81%9F%E4%BD%9C%E5%93%81&queryPageType=browse
        タイトル：はたらく細胞!!
        #av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child(1) > div > div._38SAO3.tst-hover-container._1pYuE7._1aBOAx > div._1y15Fl.dvui-beardContainer.D0Lu_p.av-grid-beard > div._1N2P-J.mustache._2mxudr > div._2hMXwV > div.vRplU5 > span > a
        タイトル：転生したらスライム
        #av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child(2) > div > div._38SAO3.tst-hover-container._1pYuE7._1aBOAx > div._1y15Fl.dvui-beardContainer.D0Lu_p.av-grid-beard > div._1N2P-J.mustache._2mxudr > div._2hMXwV > div.vRplU5 > span > a
        
        シーズン：シーズン2
        #av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child(1) > div > div._38SAO3.tst-hover-container._1pYuE7._1aBOAx > div._1y15Fl.dvui-beardContainer.D0Lu_p.av-grid-beard > div._1N2P-J.mustache._2mxudr > div._2hMXwV > div._27-0OW.dvui-beard-second-line > span > span > span:nth-child(1)
        年：2021
        #av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child(1) > div > div._38SAO3.tst-hover-container._1pYuE7._1aBOAx > div._1y15Fl.dvui-beardContainer.D0Lu_p.av-grid-beard > div._1N2P-J.mustache._2mxudr > div._2hMXwV > div._27-0OW.dvui-beard-second-line > span > span > span:nth-child(2)
        
        もうすぐ配信終了のプライムビデオ
        https://www.amazon.co.jp/gp/video/search/ref=atv_cat_leaving_soon_quest?phrase=%E3%82%82%E3%81%86%E3%81%99%E3%81%90%E9%85%8D%E4%BF%A1%E7%B5%82%E4%BA%86&queryToken=eyJ0eXBlIjoicXVlcnkiLCJuYXYiOmZhbHNlLCJwdCI6ImJyb3dzZSIsInBpIjoiZGVmYXVsdCIsInNlYyI6ImNlbnRlciIsInN0eXBlIjoic2VhcmNoIiwicXJ5IjoiYmJuPTQyMTc1MjAwNTEmc2VhcmNoLWFsaWFzPWluc3RhbnQtdmlkZW8mbm9kZT00MjE3NTIwMDUxJnBfbl93YXlzX3RvX3dhdGNoPTM3NDYzMzAwNTEiLCJ0eHQiOiLjgoLjgYbjgZnjgZDphY3kv6HntYLkuoYiLCJvZmZzZXQiOjAsIm5wc2kiOjMwfQ%3D%3D&ie=UTF8&pageId=default&queryPageType=browse
        
        プライム会員特典で見放題のプライムビデオ
        タイトル：はたらく細胞!!
        #av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child(1) > div > div._38SAO3.tst-hover-container._1pYuE7._1aBOAx > div._1y15Fl.dvui-beardContainer.D0Lu_p.av-grid-beard > div._1N2P-J.mustache._2mxudr > div._2hMXwV > div.vRplU5 > span > a
        シーズン：シーズン2
        #av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child(1) > div > div._38SAO3.tst-hover-container._1pYuE7._1aBOAx > div._1y15Fl.dvui-beardContainer.D0Lu_p.av-grid-beard > div._1N2P-J.mustache._2mxudr > div._2hMXwV > div._27-0OW.dvui-beard-second-line > span > span > span:nth-child(1)
        年：2021
        #av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child(1) > div > div._38SAO3.tst-hover-container._1pYuE7._1aBOAx > div._1y15Fl.dvui-beardContainer.D0Lu_p.av-grid-beard > div._1N2P-J.mustache._2mxudr > div._2hMXwV > div._27-0OW.dvui-beard-second-line > span > span > span:nth-child(2)

        説明
        #av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child(1) > div > div._38SAO3.tst-hover-container._1pYuE7._1aBOAx > div.lAtJLC > div > div:nth-child(3) > div > p
        */

        Log::debug('StoreInfoInDB01.php 取り込み 完了!!');
    }
}
