<?php

namespace App\Http\Controllers;

use App\Subcategory;
use Illuminate\Http\Request;
use App\Http\Resources\SubcategoryResource;

class SubcategoryController extends Controller
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
        $subcategories = Subcategory::all();

        return response()->json([
            'status' => 'ok',
            'totalresults' => count($subcategories),
            'subcategories' => SubcategoryResource::collection($subcategories)
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
            'category' => 'required',
        ]);

        $subcategory = new Subcategory;
        $subcategory->name = $request->name;
        $subcategory->category_id = $request->category;
        $subcategory->save();    // use ORM

        //redirect
        return new SubcategoryResource($subcategory);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Subcategory  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function show(Subcategory $subcategory)
    {
        return response()->json([
            'status' => 'ok',
            'subcategory' => new SubcategoryResource($subcategory)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Subcategory  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subcategory $subcategory)
    {
        $request->validate([
            'name' => 'required|max:50',
            'category' => 'required',
        ]);

        $subcategory->name = $request->name;
        $subcategory->category_id = $request->category;
        $subcategory->save();    // use ORM

        //redirect
        return new SubcategoryResource($subcategory);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subcategory  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subcategory $subcategory)
    {
        $subcategory->delete();
        return new SubcategoryResource($subcategory);
    }
}
