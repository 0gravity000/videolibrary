<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MasterCategory;

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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_category()
    {
        //
        $category = new MasterCategory;
        $category->name = "new category";
        $category->save();
        $category = MasterCategory::orderBy('id', 'desc')->first();

        return view('admin_category_update', compact('category'));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_category($id)
    {
        //新しいカテゴリは自動スクレイピングの対象外 必要なら見直す
        $category = MasterCategory::where('id', $id)->first();
 
        return view('admin_category_update', compact('category'));
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

        return redirect('/admin/category');    }

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
