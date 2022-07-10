<?php

namespace App\Http\Controllers;

use App\Video;
use Illuminate\Http\Request;
use Goutte\Client;
use App\Category;
use App\Events\DailyCheckAmazonPrimeVideo01;
use App\Events\CheckVideosTableYear;
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
