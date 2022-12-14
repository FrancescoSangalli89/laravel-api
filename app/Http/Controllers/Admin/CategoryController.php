<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|max:255|min:3'
            ]
        );

        $data = $request->all();

        $category = new Category();
        $category->fill($data);

        $slug = $this->getSlug($category->name);

        $category->slug = $slug;
        $category->save();

        return redirect()->route('admin.categories.index')->with('status', 'Category successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $request->validate(
            [
                'name' => 'required|max:255|min:3'
            ]
        );

        $data = $request->all();

        if ($category->name != $data['name']) {
            $data['slug'] = $this->getSlug($data['name']);
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('status', 'Category successfully update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('status', 'Category successfully deleted');
    }

    protected function getSlug($name) {

        $slug = Str::slug($name, '-');

        $checkPost = Category::where('slug', $slug)->first();

        $counter = 1;

        while($checkPost) {
            $slug = Str::slug($name . '-' . $counter, '-');
            $counter++;
            $checkPost = Category::where('slug', $slug)->first();
        }

        return $slug;

    }
}
