<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\CategoryStoreRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Image;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $categories = Category::all();
        return view('category.index',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): view
    {
        return view('category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function imageUpload(Request $request)
    {
        $image = $request->file('file');
        $categoryImage = Image::make($image)->resize(1600,1086)->save($image);
        Storage::disk('public')->put('category/'.$image,$categoryImage);

    }

    public function store(CategoryStoreRequest $request)
    {
        $data  = $request->validated();
        if ($data){
            return $this->imageUpload($request);
            Category::create($data);
            return redirect()->back();
        }




//        if ($validated){
//            $category = new Category();
//            $category->category_name = $request->category_name;
//            $category->category_description = $request->category_description;
//            $category->file = $this->imageUpload();
//            $category->save();
//
//            return redirect()->back();
//        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id) : View
    {
        $category = Category::find($id);
        return view('category.edit',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $request->validate ([
            'category_name' => 'required',
            'category_description' => 'required',
            'file' => 'required'
        ]);

        $category = Category::find($id);
        $category->category_name = $request->category_name;
        $category->category_description = $request->category_description;
        $category->file = $this->imageUpload->imageName;
        $category->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        $category->delete();
        return redirect()->back();
    }
}
