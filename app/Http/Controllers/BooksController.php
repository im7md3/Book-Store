<?php

namespace App\Http\Controllers;

use App\Author;
use App\Book;
use App\Category;
use App\Http\Requests\BookRequest;
use App\Publisher;
use App\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books=Book::paginate(12);
        return view('admin.books.index',compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories=Category::all();
        $authors=Author::all();
        $publishers=Publisher::all();
        return view('admin.books.create',compact('categories','authors','publishers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookRequest $request)
    {
        try{
            if($request->has('cover_image')){
                $file_path=ImageUpload( $request->cover_image );
            }
            
            $book=Book::create([
            'title' => $request->title,
            'isbn' => $request->isbn,
            'cover_image' => $file_path,
            'category_id' => $request->category,
            'publisher_id' => $request->publisher,
            'description' => $request->description,
            'publish_year' => $request->publish_year,
            'number_of_pages' =>$request->number_of_pages,
            'number_of_copies' => $request->number_of_copies,
            'price' => $request->price,
            ]);
            $book->authors()->attach($request->authors);
            return redirect()->route('books.show',$book)->with(['success'=>'لقد تم إضافة الكتاب بنجاح']);
        }catch(\Exception $e){
            return redirect()->back()->with(['fails'=>'لقد حدث خطأ ما الرجاء المحاولة لاحقا']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        return view('admin.books.show',compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book)
    {
        $categories=Category::all();
        $authors=Author::all();
        $publishers=Publisher::all();
        return view('admin.books.edit',compact('book','categories','authors','publishers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(BookRequest $request, Book $book)
    {
        try{
            if($request->has('cover_image')){
                Storage::disk('public')->delete($book->cover_image);
                $file_path=ImageUpload($request->cover_image);
            }
            $book->update([
                'title' => $request->title,
                'isbn' => $request->isbn,
                'cover_image' => $file_path,
                'category_id' => $request->category,
                'publisher_id' => $request->publisher,
                'description' => $request->description,
                'publish_year' => $request->publish_year,
                'number_of_pages' =>$request->number_of_pages,
                'number_of_copies' => $request->number_of_copies,
                'price' => $request->price,
                ]);
                $book->authors()->detach();
                $book->authors()->attach($request->authors);
                return redirect()->route('books.show',$book)->with(['success'=>'تم تعديل البيانات بنجاح']);
        }catch(\Exception $e){
            return $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        try{
            Storage::disk('public')->delete($book->cover_image);
            $book->authors()->detach();
            $book->delete();
            return redirect()->route('books.index')->with(['success'=>'تم حذف الكتاب بنجاح']);
        }catch(\Exception $e){
            return $e;
            return redirect()->back()->with(['fails'=>'لقد حدث خطأ ما الرجاء المحاولة لاحقا']);
        }
    }

    public function details(Book $book){
        
        $bookfind = 0;
        if (Auth::check()) {
            $bookfind = auth()->user()->ratedpurches()->where('book_id', $book->id)->first();
        }
        return view('books.details', compact('book', 'bookfind'));
    }

    public function rate(Request $request,Book $book){
        
        if(auth()->user()->rated($book)){
            $rating=Rating::where(['user_id'=>auth()->id(),'book_id'=>$book->id])->first();
            $rating->value=$request->value;
            $rating->save();
        }else{
            Rating::create([
                'user_id'=>auth()->id(),
                'book_id'=>$book->id,
                'value'=>$request->value
            ]);
        }
        
}
}
