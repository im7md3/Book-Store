<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function addToCart(Request $request)
    {
        $book = Book::find($request->id);
        if (auth()->user()->bookInCart->contains($book)) {
            $newQuantity = $request->quantity + auth()->user()->bookInCart()->where('book_id', $book->id)->first()->pivot->number_of_copies;
            if ($newQuantity > $book->number_of_copies) {
                return redirect()->back()->with(['warning' => 'لم تتم إضافة الكتاب، لقد تجاوزت عدد النسخ الموجودة لدينا، أقصى عدد موجود بإمكانك حجزه من هذا الكتاب هو ' . ($book->number_of_copies - auth()->user()->booksInCart()->where('book_id', $book->id)->first()->pivot->number_of_copies) . ' كتاب']);
            } else {
                auth()->user()->bookInCart()->updateExistingPivot($book->id, ['number_of_copies' => $newQuantity]);
            }
        } else {
            auth()->user()->bookInCart()->attach($request->id, ['number_of_copies' => $request->quantity]);
        }
        return redirect()->back()->with(['success'=>'لقد تم اضافة الكتاب الى العربة بنجاح']);

    }

    public function viewCart()
    {
        $items = auth()->user()->bookInCart;
        return view('cart', compact('items'));
    }

    public function removeOne(Book $book)
    {
        $oldQuantity = auth()->user()->bookInCart()->where('book_id', $book->id)->first()->pivot->number_of_copies;
        if ($oldQuantity > 1) {
            auth()->user()->bookInCart()->updateExistingPivot($book->id, ['number_of_copies' => --$oldQuantity]);
        } else {
            auth()->user()->bookInCart()->detach($book->id);
        }
        return redirect()->back()->with(['success'=>'لقد تم حذف الكتاب من العربة بنجاح']);
    }

    public function removeAll(Book $book){
        auth()->user()->bookInCart()->detach($book->id);
        return redirect()->back()->with(['success'=>'لقد تم حذف الكتاب من العربة بنجاح']);
        
    }
}
