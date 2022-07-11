<?php

namespace App\Listeners;

use App\Events\CheckVideosTableYear;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Goutte\Client;
use App\Video;
use App\Category;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StoreYearToVideosTable
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
     * @param  CheckVideosTableYear  $event
     * @return void
     */
    public function handle(CheckVideosTableYear $event)
    {
        //
        $client = new Client();

        $videos = Video::all();
        foreach ($videos as $video) {
            $crawler = $client->request('GET', $video->url);
            //年
            try {
                //#a-page > div.av-page-desktop.avu-retail-page > div.DVWebNode-detail-atf-default-wrapper.DVWebNode > div > div > div._3KHiTg._3et9aS.av-dp-container._18hTpx._2hb1y6 > div.av-detail-section._2GUwRS._2OjN2t > div > div._3QwtCH._16AW_S._2LF_6p.dv-node-dp-badges._1Yhs0c.HaWow5 > span:nth-child(4) > span
                //#a-page > div.av-page-desktop.avu-retail-page > div.DVWebNode-detail-atf-default-wrapper.DVWebNode > div > div > div._3KHiTg._3et9aS.av-dp-container._18hTpx._2hb1y6 > div.av-detail-section._2OjN2t > div >         div._3QwtCH._16AW_S._2LF_6p.dv-node-dp-badges._1Yhs0c.HaWow5 > span:nth-child(4) > span
                //#a-page > div.av-page-desktop.avu-retail-page > div.DVWebNode-detail-atf-default-wrapper.DVWebNode > div > div > div._3KHiTg._3et9aS.av-dp-container._18hTpx._2hb1y6 > div.av-detail-section._2GUwRS._2OjN2t > div > div._3QwtCH._16AW_S._2LF_6p.dv-node-dp-badges._1Yhs0c.HaWow5 > span:nth-child(4) > span
                //#a-page > div.av-page-desktop.avu-retail-page > div.DVWebNode-detail-atf-default-wrapper.DVWebNode > div > div > div._3KHiTg._3et9aS.av-dp-container._18hTpx._2hb1y6 > div.av-detail-section._2GUwRS._2OjN2t > div > div._3QwtCH._16AW_S._2LF_6p.dv-node-dp-badges._1Yhs0c.HaWow5 > span.XqYSS8 > span
                //#a-page > div.av-page-desktop.avu-retail-page > div.DVWebNode-detail-atf-default-wrapper.DVWebNode > div > div > div._3KHiTg._3et9aS.av-dp-container._18hTpx._2hb1y6 > div.av-detail-section._2GUwRS._2OjN2t > div > div._3QwtCH._16AW_S._2LF_6p.dv-node-dp-badges._1Yhs0c.HaWow5 > span.XqYSS8 > span
                //#a-page > div.av-page-desktop.avu-retail-page > div.DVWebNode-detail-atf-default-wrapper.DVWebNode > div > div > div._3KHiTg._3et9aS.av-dp-container._18hTpx._2hb1y6 > div.av-detail-section._2OjN2t > div > 　　　　 div._3QwtCH._16AW_S._2LF_6p.dv-node-dp-badges._1Yhs0c.HaWow5 > span.XqYSS8 > span
                $years = $crawler->filter('div._3QwtCH._16AW_S._2LF_6p.dv-node-dp-badges._1Yhs0c.HaWow5')->each(function ($node) {
                    $tmp = "";
                    //dd($node);
                    //たぶんドラマだとこっち
                    $tmp_drama = $node->filter('span.XqYSS8 > span')->text();
                    //dd($tmp_drama);
                    //「年」のチェック処理 4桁の数値なら年とみなす
                    if (preg_match('/\d{4}/', $tmp_drama)) {
                        //何もしない
                    } else {
                        //「年」を空白にする
                        $tmp_drama = "";
                    }

                    //たぶん映画だとこっち
                    $tmp_movie = $node->filter('span:nth-child(4) > span')->text();
                    //dd($tmp_movie);
                    //「年」のチェック処理 4桁の数値なら年とみなす
                    if (preg_match('/\d{4}/', $tmp_movie)) {
                        //何もしない
                    } else {
                        //「年」を空白にする
                        $tmp_movie = "";
                    }

                    //映画の「年」かドラマの「年」か
                    if ($tmp_movie != "") {
                        $tmp = $tmp_movie;
                    } elseif ($tmp_drama != "") {
                        $tmp = $tmp_drama;
                    }
                    //dd($tmp);
                    return $tmp;
                });
            } catch (\Exception $e) {
                $years[0] = ""; //要検証
            }

            //DB登録処理
            //年がDBと異なる場合
            if ($video->year != $years[0]) {    //要検証
                //元の「年」が4桁の数値ならそのまま
                if (preg_match('/\d{4}/', $video->year)) {
                    //何もしない
                } else {
                    //DBを更新
                    $video->year = $years[0];   //要検証
                    $video->save();
                }
            }
        }   //$videosループend

        Log::debug('StoreYearToVideosTable.php 取り込み 完了!!');
    }
}
