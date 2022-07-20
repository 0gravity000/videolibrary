<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Video;
use App\Category;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        $categories = Category::all();

        return view('admin_mastercategory' ,compact('categories') );
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
        $categories = Category::all();

        return view('admin_video_create', compact('categories'));
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
            'name' => ['unique:categories,name']
        ]);
        //dd($validatedData);

        //categoriesテーブルに登録
        //新しいカテゴリは自動スクレイピングの対象外 必要なら見直す
        $category = new Category();
        $category->name = $request->name;
        $category->save();

        return redirect('/admin/mastercategory');
    }

     public function store_video(Request $request)
    {
        //
        //dd($request);
        if(Video::where('url', $request->InputUrl)->exists()) {
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

        //category_videoテーブルに登録
        if ($request->categories[0] != "0") {
            for ($idx=0; $idx < count($request->categories) ; $idx++) { 
                DB::table('category_video')->insert(
                    [
                        'category_id' => (int)($request->categories[$idx]),
                        'video_id' => $video->id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]
                );
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
        $category = Category::where('id', $id)->first();
 
        return view('admin_mastercategory_update', compact('category'));
    }

    public function show_video($id)
    {
        $video = Video::where('id', $id)->first();
        //$categories = Category::where('video_id', $id)->get();
        $categories = Category::all();
        //dd($categories);
        return view('admin_video_update', compact('video','categories'));
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
        $category = Category::where('id', $request->InputId)->first();
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

        //category_videoテーブルに登録前に現在のvideo_idのレコードをすべてクリア
        if (DB::table('category_video')->where('video_id', $video->id)->exists()) {
            //video_idありの場合
            DB::table('category_video')->where('video_id', $video->id)->delete();
        }

        //category_videoテーブルに登録
        if ($request->categories[0] != "0") {
            //カテゴリ指定ありの場合
            for ($idx=0; $idx < count($request->categories) ; $idx++) { 
                DB::table('category_video')->insert(
                    [
                        'category_id' => (int)($request->categories[$idx]),
                        'video_id' => $video->id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]
                );
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
    public function destroy_video($id)
    {
        //videoテーブル 対象idのレコードを削除
        $video = Video::where('id', $id)->delete();
        //category_videoテーブル 対象video_idのレコードを削除
        $videos = DB::table('category_video')->where('video_id', $id)->delete();

        return redirect('/admin/video');
    }
}
