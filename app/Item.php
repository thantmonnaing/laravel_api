<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Category;
use App\Item;

class Item extends Model
{
    protected $fillable = ['codeno', 'name', 'photo', 'price', 'discount', 'description', 'brand_id','subcategory_id'];

    public function brand(){
    	return $this->belongsTo('App\Brand');
    }

    public function subcategory(){
    	return $this->belongsTo('App\Subcategory');
    }

    public function order(){
    	return $this->belongsToMany('App\Order','Orderdetails')
    			    ->withPivot('quantity')
    			    ->withTimestamps();

    }
}
