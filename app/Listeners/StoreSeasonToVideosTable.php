<?php

namespace App\Listeners;

use App\Events\CheckVideosTableSeason;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Goutte\Client;
use App\Video;
use App\Category;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StoreSeasonToVideosTable
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
     * @param  CheckVideosTableSeason  $event
     * @return void
     */
    public function handle(CheckVideosTableSeason $event)
    {
        $client = new Client();

        $videos = Video::all();
        //$videos = Video::where('id', 3117)->get();
        foreach ($videos as $video) {
            //dd($video);
            $crawler = $client->request('GET', $video->url);
            $seasons_not_listbox = "";
            $seasons_listbox_prime = "";
            $seasons_listbox_not_prime = "";
            //シーズン
            try {
                //リストボックスで選択可能な場合
                //#a-page > div.av-page-desktop.avu-retail-page > div.DVWebNode-detail-atf-default-wrapper.DVWebNode > div > div > div._3KHiTg._3et9aS.av-dp-container._18hTpx._2hb1y6 > div.av-detail-section._2GUwRS._2OjN2t > div > div.dv-node-dp-seasons._2yk-LG.HaWow5._3w9pRe > div > label > span._36qUej
                //#a-page > div.av-page-desktop.avu-retail-page > div.DVWebNode-detail-atf-default-wrapper.DVWebNode > div > div > div._3KHiTg._3et9aS.av-dp-container._18hTpx._2hb1y6 > div.av-detail-section._2GUwRS._2OjN2t > div > div.dv-node-dp-seasons._2yk-LG.HaWow5._3w9pRe > div > label > span._36qUej
                //#a-page > div.av-page-desktop.avu-retail-page > div.DVWebNode-detail-atf-default-wrapper.DVWebNode > div > div > div._3KHiTg._3et9aS.av-dp-container._18hTpx._2hb1y6 > div.av-detail-section._2OjN2t >         div > div.dv-node-dp-seasons._2yk-LG.HaWow5._3w9pRe > div > label > span._36qUej
                //#a-page > div.av-page-desktop.avu-retail-page > div.DVWebNode-detail-atf-default-wrapper.DVWebNode > div > div > div._3KHiTg._3et9aS.av-dp-container._18hTpx._2hb1y6 > div.av-detail-section._2OjN2t >         div > div.dv-node-dp-seasons._2yk-LG.HaWow5._3w9pRe > div > label > span._36qUej
                //リストボックスで選択可能な場合 プライムビデオでない場合？
                //#a-page > div.av-page-desktop.avu-retail-page > div.DVWebNode-detail-atf-default-wrapper.DVWebNode > div > div > div._3KHiTg._3et9aS.av-dp-container._18hTpx._2hb1y6 > div.av-detail-section._2OjN2t >         div > div.dv-node-dp-seasons._2yk-LG.HaWow5._3w9pRe > div > label > span
                //リストボックスでない場合
                //#a-page > div.av-page-desktop.avu-retail-page > div.DVWebNode-detail-atf-default-wrapper.DVWebNode > div > div > div._3KHiTg._3et9aS.av-dp-container._18hTpx._2hb1y6 > div.av-detail-section._2OjN2t > div > span > span
                //#a-page > div.av-page-desktop.avu-retail-page > div.DVWebNode-detail-atf-default-wrapper.DVWebNode > div > div > div._3KHiTg._3et9aS.av-dp-container._18hTpx._2hb1y6 > div.av-detail-section._2OjN2t > div > span > span
                //#a-page > div.av-page-desktop.avu-retail-page > div.DVWebNode-detail-atf-default-wrapper.DVWebNode > div > div > div._3KHiTg._3et9aS.av-dp-container._18hTpx._2hb1y6 > div.av-detail-section._2OjN2t > div > span > span
                //dd($crawler);
                $seasons_not_listbox = $crawler->filter('div._3KHiTg._3et9aS.av-dp-container._18hTpx._2hb1y6')->each(function ($node) {
                    //dd($node);
                    //たぶんリストボックスでない場合だとこっち
                    $tmp = $node->filter('div.av-detail-section._2OjN2t > div > span > span')->text();
                    //dd($tmp);
                    //「シーズンx」でない場合があるため、「シーズン」文字列を含まない場合、空文字に時間する
                    if(mb_strpos($tmp, "シーズン") === false) {
                        $tmp = "";
                    }
                    //dd($tmp);
                    return $tmp;
                });
            } catch (\Exception $e) {
                $seasons_not_listbox = ""; //要検証
            }
            //dd("case1 end");

            try {
                $seasons_listbox_prime = $crawler->filter('div._3KHiTg._3et9aS.av-dp-container._18hTpx._2hb1y6')->each(function ($node) {
                    //dd($node);
                    //たぶんリストボックスで選択可能でプライムビデオな場合だとこっち
                    $tmp = $node->filter('div.dv-node-dp-seasons._2yk-LG.HaWow5._3w9pRe > div > label > span._36qUej')->text();
                    //dd($tmp);
                    //「シーズンx」でない場合があるため、「シーズン」文字列を含まない場合、空文字に時間する
                    //dd(mb_strpos($tmp, "シーズン"));
                    if(mb_strpos($tmp, "シーズン") === false) {
                        //dd('シーズンを含まない');
                        $tmp = "";
                    }
                    //dd($tmp);
                    return $tmp;
                });
            } catch (\Exception $e) {
                $seasons_listbox_prime = ""; //要検証
            }
            //dd("case2 end");
                
            try {
                $seasons_listbox_not_prime = $crawler->filter('div._3KHiTg._3et9aS.av-dp-container._18hTpx._2hb1y6')->each(function ($node) {
                    //dd($node);
                    //たぶんリストボックスで選択可能でプライムビデオでないな場合だとこっち
                    $tmp = $node->filter('div.dv-node-dp-seasons._2yk-LG.HaWow5._3w9pRe > div > label > span')->text();
                    //dd($tmp);
                    //「シーズンx」でない場合があるため、「シーズン」文字列を含まない場合、空文字に時間する
                    if(mb_strpos($tmp, "シーズン") === false) {
                        $tmp = "";
                    }
                    //dd($tmp);
                    return $tmp;
                });
            } catch (\Exception $e) {
                $seasons_listbox_not_prime = ""; //要検証
            }
            //dd("case3 end");

            //どのケースで取得した「シーズン」かを決定する
            $seasons = "";
            if ($seasons_not_listbox != "") {
                $seasons = $seasons_not_listbox;
            } elseif ($seasons_listbox_prime != "") {
                $seasons = $seasons_listbox_prime;
            } elseif ($seasons_listbox_not_prime != "") {
                $seasons = $seasons_listbox_not_prime;
            }
            //var_dump('$seasons:');
            //var_dump($seasons);

            //DB登録処理
            //配列かどうかチェック
            if (is_array($seasons)) {
                //配列でない
                if ($seasons == "") {
                    //何もしない
                } elseif ($video->season != $seasons) {    //要検証
                    //var_dump('$seasons]:');
                    //var_dump($seasons);
                    //元のシーズンが空白 または「シーズン」文字列を含まない場合、DBを更新
                    if (($video->season == "") || (mb_strpos($video->season, "シーズン") === false)) {
                        //DBを更新
                        $video->season = $seasons;   //要検証
                        $video->save();
                    }
                }
            } else {
                //配列の場合
                if ($seasons[0] == "") {
                    //何もしない
                } elseif ($video->season != $seasons[0]) {    //要検証
                    //var_dump('$seasons[0]:');
                    //var_dump($seasons[0]);
                    //元のシーズンが空白 または「シーズン」文字列を含まない場合、DBを更新
                    if (($video->season == "") || (mb_strpos($video->season, "シーズン") === false)) {
                        //DBを更新
                        $video->season = $seasons[0];   //要検証
                        $video->save();
                    }
                }
            }
        }   //$videosループend
        
        Log::debug('StoreSeasonToVideosTable.php 取り込み 完了!!');

    }
}
