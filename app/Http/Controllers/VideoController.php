<?php

namespace App\Http\Controllers;

use App\Video;
use Illuminate\Http\Request;
use Goutte\Client;
use App\Category;
use App\MasterCategory;
use App\Events\DailyCheckAmazonPrimeVideo01;

class VideoController extends Controller
{
    public function manual_download()
    {
        event(new DailyCheckAmazonPrimeVideo01());
        return redirect('/root');
    }

    public function index()
    {
        $videos = Video::orderBy('title')->paginate(200);
        //$videos = Video::paginate(250)->shuffle();
        $mastercategories = MasterCategory::all();

        return view('root' ,compact('videos','mastercategories') );
    }

    public function index_id($id)
    {
        $categories = Category::where('master_category_id', $id)->paginate(100);
        //$categories = Category::where('master_category_id', $id)->get()->shuffle();
        $mastercategories = MasterCategory::all();

        return view('video_category' ,compact('categories','mastercategories','id') );
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
