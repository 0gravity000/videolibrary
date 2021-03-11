<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MasterCategory;
use App\Video;
use App\Category;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_mastercategory()
    {
        //
        $mastercategories = MasterCategory::all();

        return view('admin_mastercategory' ,compact('mastercategories') );
    }

    public function index_video()
    {
        //
        $videos = Video::OrderByDesc('created_at')->get();

        return view('admin_video' ,compact('videos') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_mastercategory()
    {
        /*
        $category = new MasterCategory;
        $category->name = "new category";
        $category->save();
        $category = MasterCategory::orderBy('id', 'desc')->first();
        */

        return view('admin_mastercategory_create', compact('category'));
    }

    public function create_video()
    {
        //
        /*
        $video = new Video;
        $video->title = "new video";
        $video->save();
        $video = Video::orderBy('id', 'desc')->first();
        return view('admin_video_update', compact('video'));
        */
        $mastercategories = MasterCategory::all();

        return view('admin_video_create', compact('mastercategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_mastercategory(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['unique:master_categories,name']
        ]);
        //dd($validatedData);

        //master_categoriesテーブルに登録
        //新しいカテゴリは自動スクレイピングの対象外 必要なら見直す
        $category = new MasterCategory();
        $category->name = $request->name;
        $category->save();

        return redirect('/admin/mastercategory');
    }

     public function store_video(Request $request)
    {
        //
        //dd($request);
        if(Video::where('title', $request->title)->
            where('season', $request->InputSeason)->exists()) {

            $request->session()->flash('status', '作品は既に登録されています');
            return redirect('/admin/video');
        }

        /*
        //バリデーションチェック
        $validatedData = $request->validate([
            'title' => ['unique:videos,title']
        ]);
        //dd($validatedData);
        */

        //videoテーブルに登録
        $video = new Video;
        $video->title = $request->title;
        $video->season = $request->InputSeason; //作品によっては IMDb 6.5 とか 年 とかが入る
        $video->year = $request->InputYear; //作品によっては IMDb 6.8 とか R15+ とかが入る
        $video->url = $request->InputUrl;
        $video->description = $request->InputDescription;
        $video->save();
        //dd($video);

        //categoryテーブルに登録
        if ($request->categories[0] != "0") {
            for ($idx=0; $idx < count($request->categories) ; $idx++) { 
                $category = new Category;
                $category->master_category_id = (int)($request->categories[$idx]);
                $category->video_id = $video->id;
                $category->save();
            }
        }

        return redirect('/admin/video');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_mastercategory($id)
    {
        $category = MasterCategory::where('id', $id)->first();
 
        return view('admin_mastercategory_update', compact('category'));
    }

    public function show_video($id)
    {
        $video = Video::where('id', $id)->first();
        //$categories = Category::where('video_id', $id)->get();
        $mastercategories = MasterCategory::all();
        //dd($categories);
        return view('admin_video_update', compact('video','mastercategories','categories'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_mastercategory(Request $request)
    {
        //
        //dd($request);
        $category = MasterCategory::where('id', $request->InputId)->first();
        $category->name = $request->name;
        $category->save();

        return redirect('/admin/mastercategory');
    }

    public function update_video(Request $request)
    {

        //videoテーブルに登録
        $video = Video::where('id', $request->InputId)->first();
        $video->title = $request->title;
        $video->season = $request->InputSeason; //作品によっては IMDb 6.5 とか 年 とかが入る
        $video->year = $request->InputYear; //作品によっては IMDb 6.8 とか R15+ とかが入る
        $video->url = $request->InputUrl;
        $video->description = $request->InputDescription;
        $video->save();

        //categoryテーブルに登録前に現在のカテゴリのレコードをすべてクリア
        //dd(Category::where('video_id', $video->id)->get());
        if (Category::where('video_id', $video->id)->exists()) {
            //videoidありの場合
            Category::where('video_id', $video->id)->delete();
        }

        //categoryテーブルに登録
        if ($request->categories[0] != "0") {
            //カテゴリ指定ありの場合
            for ($idx=0; $idx < count($request->categories) ; $idx++) { 
                $category = new Category;
                $category->master_category_id = (int)($request->categories[$idx]);
                $category->video_id = $video->id;
                $category->save();
            }
        }

        return redirect('/admin/video');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
