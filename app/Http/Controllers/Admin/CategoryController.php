<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        $pageName = 'Categories';
        return view('admin.category.index', compact('pageName','categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::get()->pluck('itemname_eng', 'id')->prepend('No Parent','0');
        $pageName = 'Create Category';
        return view('admin.category.create', compact('categories', 'pageName'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'itemname_eng' => 'required',
            'itemname_mal' => 'required',
            'parent_id' => 'required',
        ]);

        $data = new Category;
        $data->itemname_eng = request('itemname_eng');
        $data->itemname_mal = request('itemname_mal');
        $data->parent_id = request('parent_id');
        $data->save();

        $parent = Category::find(request('parent_id'));
        $parent->has_child = '1';
        $parent->has_profilecount = $parent->has_profilecount + 1;
        $parent->save();

        return redirect()->route('admin::categories.index')->with(['success' => 'Category created successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::all();
        $pageName = 'Edit Categories';
        return view('admin.category.index', compact('pageName','categories', 'category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
