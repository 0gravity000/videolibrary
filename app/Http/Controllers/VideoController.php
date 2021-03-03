<?php

namespace App\Http\Controllers;

use App\Video;
use Illuminate\Http\Request;
use Goutte\Client;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_recentlyadd()
    {
        //
        $client = new Client();
        $url = 'https://www.amazon.co.jp/gp/video/search/ref=atv_cat_jp_recently_added_to_prime_quest?ie=UTF8&pageId=default&queryToken=eyJ0eXBlIjoicXVlcnkiLCJuYXYiOmZhbHNlLCJwdCI6ImJyb3dzZSIsInBpIjoiZGVmYXVsdCIsInNlYyI6ImNlbnRlciIsInN0eXBlIjoic2VhcmNoIiwicXJ5IjoiYmJuPTM5ODUyODkwNTEmc2VhcmNoLWFsaWFzPWluc3RhbnQtdmlkZW8mbm9kZT0zOTg1Mjg5MDUxJnBfbl93YXlzX3RvX3dhdGNoPTM3NDYzMzAwNTEiLCJ0eHQiOiLjg5fjg6njgqTjg6DjgavmnIDov5Hov73liqDjgZXjgozjgZ%2FkvZzlk4EiLCJvZmZzZXQiOjAsIm5wc2kiOjMwfQ%3D%3D&phrase=%E3%83%97%E3%83%A9%E3%82%A4%E3%83%A0%E3%81%AB%E6%9C%80%E8%BF%91%E8%BF%BD%E5%8A%A0%E3%81%95%E3%82%8C%E3%81%9F%E4%BD%9C%E5%93%81&queryPageType=browse';
        $crawler = $client->request('GET', $url);
        //dd($crawler);
        //1-20件分のデータはある
        for ($idx=1; $idx < 21 ; $idx++) { 
            //タイトル
            $titles[$idx-1] = $crawler->filter('#av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child('.$idx.')')->each(function ($node) {
                $tmp = $node->filter('span > a')->text();
                return $tmp;
            });
            //シーズン
            $seasons[$idx-1] = $crawler->filter('#av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child('.$idx.')')->each(function ($node) {
                $tmp = $node->filter('span > span > span:nth-child(1)')->text();
                return $tmp;
            });
            //年
            $years[$idx-1] = $crawler->filter('#av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child('.$idx.')')->each(function ($node) {
                $tmp = $node->filter('span > span > span:nth-child(2)')->text();
                return $tmp;
            });
            //説明
            $discribes[$idx-1] = $crawler->filter('#av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child('.$idx.')')->each(function ($node) {
                $tmp = $node->filter('p')->text();
                return $tmp;
            });

            //DBに登録
            foreach ($titles[$idx-1] as $title) {
                if (Video::where('title', $title)->doesntExist()) {
                    $video = new Video;
                    $video->title = $title;
                    $video->season = $seasons[$idx-1][0];
                    $video->year = $years[$idx-1][0];
                    $video->description = $discribes[$idx-1][0];
                    $video->save();
                }
            }
        }
        //dd(Video::all());

        /*
        $tag_htmls = $crawler->filter('#av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child(1)')->each(function ($node) {
            return $node->text();
        });
        */
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
        //dd($titles);
        //dd($seasons);
        //dd($years);
        //dd($discribes);

        return view('video_recentlyadd' ,compact('titles','seasons','years','discribes') );
    }

    public function index_toprated()
    {
        //
        $client = new Client();
        $url = 'https://www.amazon.co.jp/gp/video/search/ref=atv_cat_auto_v2_top_rated_quest?ie=UTF8&phrase=%E9%AB%98%E8%A9%95%E4%BE%A1%E3%81%95%E3%82%8C%E3%81%9F%E4%BD%9C%E5%93%81&pageId=default&queryToken=eyJ0eXBlIjoicXVlcnkiLCJuYXYiOmZhbHNlLCJwdCI6ImJyb3dzZSIsInBpIjoiZGVmYXVsdCIsInNlYyI6ImNlbnRlciIsInN0eXBlIjoic2VhcmNoIiwicXJ5Ijoibm9kZT0yMzUxNjQ5MDUxJmJibj0yMzUxNjQ5MDUxJmZpZWxkLXJldmlldy1yYXRpbmc9Mjc2MTYyNzA1MSZwX25fd2F5c190b193YXRjaD0zNzQ2MzMwMDUxJnNlYXJjaC1hbGlhcz1pbnN0YW50LXZpZGVvIiwidHh0Ijoi6auY6KmV5L6h44GV44KM44Gf5L2c5ZOBIiwib2Zmc2V0IjowLCJucHNpIjozMH0%3D&queryPageType=browse';
        $crawler = $client->request('GET', $url);
        //dd($crawler);
        //1-20件分のデータはある
        for ($idx=1; $idx < 21 ; $idx++) { 
            //タイトル
            #av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child(1) > div > div._38SAO3.tst-hover-container._1pYuE7._1aBOAx > div._1y15Fl.dvui-beardContainer.D0Lu_p.av-grid-beard > div._1N2P-J.mustache._2mxudr > div._2hMXwV > div.vRplU5 > span > a

            $titles[$idx-1] = $crawler->filter('#av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child('.$idx.')')->each(function ($node) {
                $tmp = $node->filter('span > a')->text();
                return $tmp;
            });
            //シーズン
            $seasons[$idx-1] = $crawler->filter('#av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child('.$idx.')')->each(function ($node) {
                $tmp = $node->filter('span > span > span:nth-child(1)')->text();
                return $tmp;
            });
            //年
            $years[$idx-1] = $crawler->filter('#av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child('.$idx.')')->each(function ($node) {
                $tmp = $node->filter('span > span > span:nth-child(2)')->text();
                return $tmp;
            });
            //説明
            $discribes[$idx-1] = $crawler->filter('#av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child('.$idx.')')->each(function ($node) {
                $tmp = $node->filter('p')->text();
                return $tmp;
            });

            //DBに登録
            foreach ($titles[$idx-1] as $title) {
                if (Video::where('title', $title)->doesntExist()) {
                    $video = new Video;
                    $video->title = $title;
                    $video->season = $seasons[$idx-1][0];
                    $video->year = $years[$idx-1][0];
                    $video->description = $discribes[$idx-1][0];
                    $video->save();
                }
            }
        }

        //dd($titles);
        //dd($seasons);
        //dd($years);
        //dd($discribes);

        //高評価の作品　20件?

        return view('video_recentlyadd' ,compact('titles','seasons','years','discribes') );
    }

    public function index_memberbenefits()
    {
        //
        $client = new Client();
        $url = 'https://www.amazon.co.jp/gp/video/search/ref=atv_cat_facettext_quest?ie=UTF8&pageId=default&phrase=%E3%83%97%E3%83%A9%E3%82%A4%E3%83%A0%E4%BC%9A%E5%93%A1%E7%89%B9%E5%85%B8&queryToken=eyJ0eXBlIjoicXVlcnkiLCJuYXYiOmZhbHNlLCJwdCI6ImJyb3dzZSIsInBpIjoiZGVmYXVsdCIsInNlYyI6ImNlbnRlciIsInN0eXBlIjoic2VhcmNoIiwicXJ5Ijoic2VhcmNoLWFsaWFzPWluc3RhbnQtdmlkZW8mbm9kZT0yMzUxNjQ5MDUxJmJxPSUyOG5vdCUyMGdlbnJlOiUyN2F2X2dlbnJlX2tpZHMlMjclMjkmcF9uX3dheXNfdG9fd2F0Y2g9Mzc0NjMzMDA1MSIsInR4dCI6IuODl%2BODqeOCpOODoOS8muWToeeJueWFuCIsIm9mZnNldCI6MCwibnBzaSI6MzB9&queryPageType=browse';
        $crawler = $client->request('GET', $url);
        //dd($crawler);
        //1-20件分のデータはある
        for ($idx=1; $idx < 21 ; $idx++) { 
            //タイトル
            $titles[$idx-1] = $crawler->filter('#av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child('.$idx.')')->each(function ($node) {
                $tmp = $node->filter('span > a')->text();
                return $tmp;
            });
            //シーズン
            $seasons[$idx-1] = $crawler->filter('#av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child('.$idx.')')->each(function ($node) {
                $tmp = $node->filter('span > span > span:nth-child(1)')->text();
                return $tmp;
            });
            //年
            $years[$idx-1] = $crawler->filter('#av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child('.$idx.')')->each(function ($node) {
                $tmp = $node->filter('span > span > span:nth-child(2)')->text();
                return $tmp;
            });
            //説明
            $discribes[$idx-1] = $crawler->filter('#av-search > div > div.X8aBJ_.av-search-grid.av-s-g-clear > div:nth-child('.$idx.')')->each(function ($node) {
                $tmp = $node->filter('p')->text();
                return $tmp;
            });

            //DBに登録
            foreach ($titles[$idx-1] as $title) {
                if (Video::where('title', $title)->doesntExist()) {
                    $video = new Video;
                    $video->title = $title;
                    $video->season = $seasons[$idx-1][0];
                    $video->year = $years[$idx-1][0];
                    $video->description = $discribes[$idx-1][0];
                    $video->save();
                }
            }
        }
        //dd($titles);
        //dd($seasons);
        //dd($years);
        //dd($discribes);

        return view('video_recentlyadd' ,compact('titles','seasons','years','discribes') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function show(Video $video)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function edit(Video $video)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Video $video)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function destroy(Video $video)
    {
        //
    }
}
