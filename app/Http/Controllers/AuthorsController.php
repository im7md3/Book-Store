<?php

namespace App\Http\Controllers;

use App\Author;
use App\Http\Requests\AuthorRequest;
use Illuminate\Http\Request;

class AuthorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authors=Author::all()->sortBy('name');
        return view('admin.authors.index',compact('authors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.authors.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AuthorRequest $request)
    {
        try{
            Author::create($request->all());
            return redirect()->route('authors.index')->with(['success'=>'لقد تم إضافة المؤلف بنجاح']);
        }catch(\Exception $e){
            return redirect()->back()->with(['fails'=>'لقد حدث خطأ ما الرجاء المحاولة لاحقا']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function show(Author $author)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function edit(Author $author)
    {
        return view('admin.authors.edit',compact('author'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function update(AuthorRequest $request, Author $author)
    {
        try{
            $author->update($request->all());
            return redirect()->route('authors.index')->with(['success'=>'لقد تم تعديل المؤلف بنجاح']);
        }catch(\Exception $e){
            return $e;
            return redirect()->back()->with(['fails'=>'لقد حدث خطأ ما الرجاء المحاولة لاحقا']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function destroy(Author $author)
    {
        try{
            $author->delete();
            return redirect()->route('authors.index')->with(['success'=>'لقد تم حذف المؤلف بنجاح']);
        }catch(\Exception $e){
            return $e;
            return redirect()->back()->with(['fails'=>'لقد حدث خطأ ما الرجاء المحاولة لاحقا']);
        }
    }

    public function result(Author $author){
        $books=$author->books()->paginate(12);
        $title='الكتب التابعة للمؤلف:'. $author->name;
        return view('gallery',compact('books','title'));
    }

    public function list(){
        $authors=Author::all()->sortBy('name');
        $title='المؤلفون';
        return view('authors.index',compact('authors','title'));
    }
    public function search(Request $request){
        $authors=Author::where('name','like',"%{$request->term}%")->get();
        $title ='عرض نتائج البحث عن: ' . $request->term;
        return view('authors.index',compact('authors','title'));
    }
    
}
