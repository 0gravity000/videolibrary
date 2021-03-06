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
    public function index_category()
    {
        //
        $mastercategories = MasterCategory::all();

        return view('admin_category' ,compact('mastercategories') );
    }

    public function index_video()
    {
        //
        $videos = Video::all();

        return view('admin_video' ,compact('videos') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_category()
    {
        //新しいカテゴリは自動スクレイピングの対象外 必要なら見直す
        $category = new MasterCategory;
        $category->name = "new category";
        $category->save();
        $category = MasterCategory::orderBy('id', 'desc')->first();

        return view('admin_category_update', compact('category'));
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
    public function store_video(Request $request)
    {
        //
        //dd($request);
        //バリデーションチェック
        /*
        $rules = [
            'title' => ['unique:videos']
        ];
        $this->validate($request, $rules);
        */

        $validatedData = $request->validate([
            'title' => ['unique:videos,title']
        ]);
        //dd($validatedData);

        //videoテーブルに登録
        $video = new Video;
        $video->title = $request->title;
        $video->season = $request->InputSeason; //作品によっては IMDb 6.5 とか 年 とかが入る
        $video->year = $request->InputYear; //作品によっては IMDb 6.8 とか R15+ とかが入る
        $video->url = $request->InputUrl;
        $video->description = $request->InputDescription;
        $video->save();

        /*
        //categoryテーブルに登録
        if (Category::where('video_id', $video->id)->doesntExist()) {
            //新規作成 videoidなし
            $category = new Category;
            $category->master_category_id = $request->InputCategoryid;
            $category->video_id = $video->id;
            $category->save();
        } else {
            //新規作成 videoidあり categoryidなし
            if (Category::where('video_id', $video->id)
                ->where('master_category_id', $request->InputCategoryid)
                ->doesntExist()) {
                $category = new Category;
                $category->master_category_id = $request->InputCategoryid;
                $category->video_id = $video->id;
                $category->save();
            }
        }
        */

        return redirect('/admin/video');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_category($id)
    {
        $category = MasterCategory::where('id', $id)->first();
 
        return view('admin_category_update', compact('category'));
    }

    public function show_video($id)
    {
        $video = Video::where('id', $id)->first();
        $mastercategories = MasterCategory::all();
 
        return view('admin_video_update', compact('video','mastercategories'));
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
    public function update_category(Request $request)
    {
        //
        //dd($request);
        $category = MasterCategory::where('id', $request->InputId)->first();
        $category->name = $request->InputName;
        $category->save();

        return redirect('/admin/category');
    }

    public function update_video(Request $request)
    {
        //
        //dd($request);
        //バリデーションチェック
        /*
        $rules = [
            'title' => ['unique:videos']
        ];
        $this->validate($request, $rules);
        */

        $validatedData = $request->validate([
            'title' => ['unique:videos,title']
        ]);
        //dd($validatedData);

        //videoテーブルに登録
        $video = Video::where('id', $request->InputId)->first();
        $video->title = $request->title;
        $video->season = $request->InputSeason; //作品によっては IMDb 6.5 とか 年 とかが入る
        $video->year = $request->InputYear; //作品によっては IMDb 6.8 とか R15+ とかが入る
        $video->url = $request->InputUrl;
        $video->description = $request->InputDescription;
        $video->save();

        /*
        //categoryテーブルに登録
        if (Category::where('video_id', $video->id)->doesntExist()) {
            //新規作成 videoidなし
            $category = new Category;
            $category->master_category_id = $request->InputCategoryid;
            $category->video_id = $video->id;
            $category->save();
        } else {
            //新規作成 videoidあり categoryidなし
            if (Category::where('video_id', $video->id)
                ->where('master_category_id', $request->InputCategoryid)
                ->doesntExist()) {
                $category = new Category;
                $category->master_category_id = $request->InputCategoryid;
                $category->video_id = $video->id;
                $category->save();
            }
        }
        */

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
