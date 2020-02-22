<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Category;
use Auth;

class CategoryController extends Controller
{
    //
    public function category()
    {
        return view('categories.category');
    }
    
    public function addCategory(Request $request)
    {
       // return $request->input('category');
       $this->validate($request, [
           'category' => 'required'
       ]);
       //return 'validation passed';
       $category =  new Category;
       $category->category = $request->input('category');
       $category->save();
       //return 'Category added successfully';
    
       return redirect('/category')->with('response', 'Category added successfully!');
       //return Redirect::route('category.index');
    }
}
