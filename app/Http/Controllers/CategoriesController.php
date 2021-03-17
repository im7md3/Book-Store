<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories=Category::all();
        return view('admin.categories.index',compact('categories'));
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
    public function store(CategoryRequest $request)
    {
        try{
            Category::create($request->all());
            return redirect()->route('categories.index')->with(['success'=>'لقد تم إضافة القسم بنجاح']);
        }catch(\Exception $e){
            return redirect()->back()->with(['fails'=>'لقد حدث خطأ ما الرجاء المحاولة لاحقا']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, Category $category)
    {
        try{
            $category->update($request->all());
            return redirect()->route('categories.index')->with(['success'=>'لقد تم تعديل القسم بنجاح']);
        }catch(\Exception $e){
            return $e;
            return redirect()->back()->with(['fails'=>'لقد حدث خطأ ما الرجاء المحاولة لاحقا']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        try{
            $category->delete();
            return redirect()->route('categories.index')->with(['success'=>'لقد تم حذف القسم بنجاح']);
        }catch(\Exception $e){
            return $e;
            return redirect()->back()->with(['fails'=>'لقد حدث خطأ ما الرجاء المحاولة لاحقا']);
        }
    }

    public function result(Category $category)
    {
        $books = $category->books()->paginate(12);
        $title = 'الكتب التابعة لتصنيف: ' . $category->name;
        return view('gallery', compact('books','title'));
    }   

    public function list(){
        $categories=Category::all()->sortBy('name');
        $title ='التصنيفات';
        return view('categories.index',compact('categories','title'));
    }

    public function search(Request $request){
        $categories=Category::where('name','like',"%{$request->term}%")->get()->sortBy('name');
        $title ='عرض نتائج البحث عن: ' . $request->term;
        return view('categories.index',compact('categories','title'));
    }
}
