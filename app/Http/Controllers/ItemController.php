<?php

namespace App\Http\Controllers;

use App\Item;
use Illuminate\Http\Request;
use App\Http\Resources\ItemResource;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Item::all();

        return response()->json([
            'status' => 'ok',
            'totalresults' => count($items),
            'items' => ItemResource::collection($items)
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
            'price' => 'required',
            'brand' => 'required',
            'subcategory' =>'required'
        ]);

        //if file include, file uploade
            if($request->file()){

                $filename = time().'_'.$request->photo->getClientOriginalName();
                $filepath = $request->file('photo')->storeAS('itemimg', $filename, 'public');

                $path = '/storage/'.$filepath;
            }

        //store
            // $random = mt_rand(10000, 99999);

            $item = new Item;
            $item->codeno = uniqid();
            $item->name = $request->name;
            $item->photo = $path;
            $item->price = $request->price;
            $item->discount = $request->discount;
            $item->description = $request->description;
            $item->brand_id = $request->brand;
            $item->subcategory_id = $request->subcategory;
            $item->save();    // use ORM

        //redirect
            return new ItemResource($item);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        return response()->json([
            'status' => 'ok',
            'item' => new ItemResource($item)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        // 
        $request->validate([
            'name' => 'required|max:50',
            'photo' => 'sometimes|required|mimes:jpeg,jpg,png',
            'oldphoto' => 'required',
            'price' => 'required',
            'brand' => 'required',
            'subcategory' =>'required'
        ]);

        //if file include, file uploade
            if($request->file()){

                // unlink(public_path($request->oldphoto));

                $filename = time().'_'.$request->photo->getClientOriginalName();
                $filepath = $request->file('photo')->storeAS('itemimg', $filename, 'public');

                $path = '/storage/'.$filepath;
            }else{
                $path = $request->oldphoto;
            }

        //store
            $item->codeno = $request->codeno;
            $item->name = $request->name;
            $item->photo = $path;
            $item->price = $request->price;
            $item->discount = $request->discount;
            $item->description = $request->description;
            $item->brand_id = $request->brand;
            $item->subcategory_id = $request->subcategory;
            $item->save();    // use ORM

        //redirect
            return new ItemResource($item);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        unlink(public_path($item->photo));
        $item->delete();
        return new ItemResource($item);
    }
}
