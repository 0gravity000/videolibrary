<?php

namespace App\Http\Controllers;

use App\Video;
use Illuminate\Http\Request;
use Goutte\Client;
use App\Category;
use App\Events\DailyCheckAmazonPrimeVideo01;
use App\Events\CheckVideosTableYear;
use App\Events\CheckVideosTableSeason;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VideoController extends Controller
{
    public function manual_download()
    {
        event(new DailyCheckAmazonPrimeVideo01());
        return redirect('/videos');

        // debug code はここに追加する /////////////////////
        // debug code end ///////////////////////
    }

    public function check_year()
    {
        event(new CheckVideosTableYear());
        return redirect('/videos');

        // debug code はここに追加する /////////////////////
        // debug code end ///////////////////////
    }

    public function check_season()
    {
        event(new CheckVideosTableSeason());
        return redirect('/videos');

        // debug code はここに追加する /////////////////////
        // debug code end ///////////////////////
    }

    public function check_url_duplication()
    {
        $videos = Video::orderBy('id')->get();
        //$videos = Video::orderBy('id')->where('id', 384)->get();

        foreach ($videos as $video) {
            //videoテーブルに現在チェック中のレコードと同一URLが存在するかチェック
            //自分自身以外に同一URLが存在するか
            $duplication_count = Video::orderByDesc('id')->whereIn('url', [$video->url])->count();
            if ($duplication_count > 1) {
                //videoテーブルに現在チェック中のレコードと同一URLが存在する場合
                //最低2レコード存在する
                $duplications = Video::orderByDesc('id')->whereIn('url', [$video->url])->get();
                //dd($duplications);
                //category_videoテーブルの削除するレコードのcategory_idを保持用コレクションの初期化
                $merged_category_ids = collect([]);
                $first_duplication_id = "";
                //$first_duplication = collect();
                $cnt = 0;
                //var_dump($merged_category_ids);
                //重複レコード数分削除する 最初のコレクションは保持しておく
                foreach ($duplications as $duplication) {
                    var_dump($duplication->id);
                    if ($cnt == 0) {
                        $first_duplication_id = $duplication->id;
                        //$first_duplication = $duplication->replicate();
                        //カウンタの加算
                        $cnt++;
                        continue;
                    } 
                    //videoテーブルの削除するレコードのtitle、season、yearをチェックする。
                    //保持しておくレコードといずれかが不一致の場合は、削除せず、continueする。
                    if (($duplication->title == Video::where('id', $first_duplication_id)->first()->title)
                        && ($duplication->season == Video::where('id', $first_duplication_id)->first()->season)
                        && ($duplication->year == Video::where('id', $first_duplication_id)->first()->year)) {
                        //videoテーブルの該当レコードを削除
                        var_dump('title、season、year一致');
                        //videoテーブルの削除するレコードのtitle、season、yearをチェックし、
                        //保持しておくレコードとすべてが一致の場合
                        var_dump($duplication->categories()->pluck('category_id'));
                        //category_videoテーブルの削除するレコードのcategory_idを保持用コレクションにマージする
                        $merged_category_ids = $merged_category_ids->merge($duplication->categories()->pluck('category_id'));
                        //dd($merged_category_ids);

                        //videoテーブルの該当レコードを削除
                        var_dump('videoテーブル 削除');
                        var_dump($duplication->id);
                        Video::where('id', $duplication->id)->delete();

                        //category_videoテーブルの該当レコード（video_idが一致するレコード）を削除
                        DB::table('category_video')->where('video_id', $duplication->id)->delete();
                    }
                    //カウンタの加算
                    $cnt++;
                }   //$duplicationsループ end

                //category_videoテーブルの保持しておくレコードのcategory_idを保持用コレクションにマージする
                
                $merged_category_ids = $merged_category_ids->merge(Video::where('id', $first_duplication_id)->first()->categories()->pluck('category_id'));
                //dd(Video::where('id', $first_duplication_id)->first());
                var_dump($merged_category_ids);
                $uniqued_category_ids = $merged_category_ids->unique();
                var_dump($uniqued_category_ids);
                //category_videoテーブルの該当レコード（video_idが一致するレコード）を削除
                DB::table('category_video')->where('video_id', $first_duplication_id)->delete();
                //category_videoテーブルにvideo_idと保持用コレクションに格納されたcategory_idのすべてを追加する
                foreach ($uniqued_category_ids as $uniqued_category_id) {
                    var_dump($uniqued_category_id);
                    DB::table('category_video')->insert(
                        [
                            'category_id' => $uniqued_category_id,
                            'video_id' => $first_duplication_id,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ]
                    );
                }

            }   //if $duplication_count-1 > 1 end
            //dd('次のvideo');
        }   //videos ループend

        return redirect('/admin');
    }   
    
    public function trim_url()
    {
        $videos = Video::orderBy('id')->get();

        foreach ($videos as $video) {
            $pos = mb_strpos($video->url, "/ref=");
            if ($pos !== false) {
                //dd($pos);
                $trim = substr($video->url, $pos);
                //dd($trim);
                $trimed_url = str_replace($trim, '', $video->url);
                //dd($trimed_url);
                $video->url = $trimed_url;
                $video->save();
            }
        }
        return redirect('/admin');

    }

    public function index()
    {
        $videos = Video::orderBy('updated_at', 'desc')->paginate(200);
        //$videos = Video::paginate(250)->shuffle();
        $categories = Category::all();

        return view('root' ,compact('videos','categories') );
    }

    public function index_categoryid($id)
    {
        $categories = Category::all();
        $videoids = DB::table('category_video')->where('category_id', $id)->pluck('video_id');
        //dd($videoids);
        //$categoryvideos = DB::table('category_video')->where('category_id', $id)->get();
        $videos = Video::orderBy('updated_at', 'desc')->whereIn('id', $videoids)->paginate(100);
        //dd($videos);
        //$categories = Category::where('master_category_id', $id)->get()->shuffle();

        return view('video_category' ,compact('videos','categories','id') );
    }

    public function index_search(Request $request)
    {
        //dd($request->input('search'));
        $videos = Video::where('title', 'like', '%'.$request->input('search').'%')->
                    orwhere('description', 'like', '%'.$request->input('search').'%')->
                    orderBy('updated_at', 'desc')->paginate(200);
        $categories = Category::all();

        return view('video_search' ,compact('videos','categories') );
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
