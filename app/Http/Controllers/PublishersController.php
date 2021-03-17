<?php

namespace App\Http\Controllers;

use App\Http\Requests\PublisherRequest;
use App\Publisher;
use Illuminate\Http\Request;

class PublishersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $publishers=Publisher::all()->sortBy('name');
        return view('admin.publishers.index',compact('publishers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.publishers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PublisherRequest $request)
    {
        try{
            Publisher::create($request->all());
            return redirect()->route('publishers.index')->with(['success'=>'لقد تم إضافة الناشر بنجاح']);
        }catch(\Exception $e){
            return redirect()->back()->with(['fails'=>'لقد حدث خطأ ما الرجاء المحاولة لاحقا']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Publisher  $publisher
     * @return \Illuminate\Http\Response
     */
    public function show(Publisher $publisher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Publisher  $publisher
     * @return \Illuminate\Http\Response
     */
    public function edit(Publisher $publisher)
    {
        return view('admin.publishers.edit',compact('publisher'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Publisher  $publisher
     * @return \Illuminate\Http\Response
     */
    public function update(PublisherRequest $request, Publisher $publisher)
    {
        try{
            $publisher->update($request->all());
            return redirect()->route('publishers.index')->with(['success'=>'لقد تم تعديل الناشر بنجاح']);
        }catch(\Exception $e){
            return $e;
            return redirect()->back()->with(['fails'=>'لقد حدث خطأ ما الرجاء المحاولة لاحقا']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Publisher  $publisher
     * @return \Illuminate\Http\Response
     */
    public function destroy(Publisher $publisher)
    {
        try{
            $publisher->delete();
            return redirect()->route('publishers.index')->with(['success'=>'لقد تم حذف القسم بنجاح']);
        }catch(\Exception $e){
            return $e;
            return redirect()->back()->with(['fails'=>'لقد حدث خطأ ما الرجاء المحاولة لاحقا']);
        }
    }

    public function list()
    {
        $publishers=Publisher::all()->sortBy('name');
        $title='الناشرون';
        return view('publishers.index',compact('publishers','title'));
    }

    public function result(Publisher $publisher)
    {
        $books=$publisher->books()->paginate(12);
        $title='الكتب التابعة للناشر:' .$publisher->name;
        return view('gallery',compact('books','title'));
    }

    public function search(Request $request)
    {
        $publishers=Publisher::where('name','like',"%{$request->term}%")->get()->sortBy('name');
        $title='الناشرون';
        return view('publishers.index',compact('publishers','title'));
    }


}
