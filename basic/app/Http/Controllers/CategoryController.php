<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Auth;
use Illuminate\Support\Carbon;

class CategoryController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }
    public function AllCat(){
       
        $categories=Category::all();
        $trashCat=Category::onlyTrashed()->latest()->paginate(3);
        return view('admin.category.index',compact('categories','trashCat'));

    }

 
    public function AddCat(Request $request){
        $validatedData = $request->validate([
            'category_name' => 'required|unique:categories|max:255',
            
        ],
        [
            'category_name.required' => 'Please Input Category Name',
            'category_name.max' => 'Category Less Then 255Chars', 
        ]);
        Category::insert([
            'category_name' => $request->category_name,
            'user_id' => Auth::user()->id,
            'created_at' => Carbon::now()
        ]);
        return Redirect()->back()->with('success','Category Inserted Successfull');
    }

    public function Edit($id){
        $categories=Category::find($id);
        return view('admin.category.edit',compact('categories'));

    }
    public function Update(Request $request, $id){
        $update=Category::find($id)->update([
          'category_name' => $request->category_name,
          'user_id' =>Auth::user()->id
        ]);
        return Redirect()->route('all.category')->with('success','Category Updated Successfull');

    }

    public function SoftDelete($id){
        $delete=Category::find($id)->delete();
        return Redirect()->back()->with('success','Category Deleted Successfull');

    }

    public function Restore($id){
        $delete = Category::withTrashed()->find($id)->restore();
        return Redirect()->back()->with('success','Category Restore Successfully');
    }

    public function Pdelete($id){
        $delete = Category::onlyTrashed()->find($id)->forceDelete();
        return Redirect()->back()->with('success','Category Deleted  Parmanently');
    }

}