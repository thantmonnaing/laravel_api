<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    public function __construct($value=''){
        $this->middleware('auth:api')->except('index','show');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();

        return response()->json([
            'status' => 'ok',
            'totalresults' => count($categories),
            'categories' => CategoryResource::collection($categories)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $request->validate([
            'name' => 'required|max:50',
            'photo' => 'required|mimes:jpeg,jpg,png',
        ]);

       //if file include, file uploade
            if($request->file()){

                $filename = time().'_'.$request->photo->getClientOriginalName();
                $filepath = $request->file('photo')->storeAS('categoryimg', $filename, 'public');

                $path = '/storage/'.$filepath;
            }

        //store
            $category = new Category;
            $category->name = $request->name;
            $category->photo = $path;
            $category->save();    // use ORM

        //redirect
            return new CategoryResource($category);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return response()->json([
            'status' => 'ok',
            'category' =>new CategoryResource($category)
        ]); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|max:50',
            'photo' => 'sometimes|required|mimes:jpeg,jpg,png',
            'oldphoto' => 'required'
        ]);

        //if file include, file uploade
            if($request->file()){

                //delete old photo

                unlink(public_path($request->oldphoto));

                $filename = time().'_'.$request->photo->getClientOriginalName();
                $filepath = $request->file('photo')->storeAS('categoryimg', $filename, 'public');

                $path = '/storage/'.$filepath;
            }else{
                $path = $request->oldphoto;
            }

        //store
            $category->name = $request->name;
            $category->photo = $path;
            $category->save();    // use ORM

        //redirect
            return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        unlink(public_path($category->photo));
        $category->delete();
        return new CategoryResource($category);
    }
}
