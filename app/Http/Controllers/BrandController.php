<?php

namespace App\Http\Controllers;

use App\Brand;
use Illuminate\Http\Request;
use App\Http\Resources\BrandResource;

class BrandController extends Controller
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
        $brands = Brand::all();

        return response()->json([
            'status' => 'ok',
            'totalresults' => count($brands),
            'brands' => BrandResource::collection($brands)
        ]);
        // return BrandResource::collection($brands);
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
                $filepath = $request->file('photo')->storeAS('brandimg', $filename, 'public');

                $path = '/storage/'.$filepath;
            }

        //store
            $brand = new Brand;
            $brand->name = $request->name;
            $brand->photo = $path;
            $brand->save();   

            return new BrandResource($brand);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        return response()->json([
            'status' => 'ok',
            'brand' =>new BrandResource($brand)
        ]); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {
        // 
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
                $filepath = $request->file('photo')->storeAS('brandimg', $filename, 'public');

                $path = '/storage/'.$filepath;
            }else{
                $path = $request->oldphoto;
            }

        //store
            $brand->name = $request->name;
            $brand->photo = $path;
            $brand->save();    // use ORM

        //redirect
            return new BrandResource($brand);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        unlink(public_path($brand->photo));
        $brand->delete();
        return new BrandResource($brand);
    }
}
