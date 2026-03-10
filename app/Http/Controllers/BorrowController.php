<?php

namespace App\Http\Controllers;

use App\Models\BorrowRecord;
use App\Models\Book;
use App\Models\Member;
use App\Http\Requests\StoreBorrowRequest;
use App\Http\Requests\UpdateBorrowRequest;
use Illuminate\Http\Request;

class BorrowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $borrowRecords = BorrowRecord::with(['book', 'member'])->paginate(10);
        return view('borrows.index', compact('borrowRecords'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $books = Book::where('available_copies', '>', 0)->get();
        $members = Member::all();
        return view('borrows.create', compact('books', 'members'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBorrowRequest $request)
    {
        $borrowRecord = BorrowRecord::create($request->validated());

        // Decrement available copies
        $borrowRecord->book->decrement('available_copies');

        return redirect()->route('borrows.index')
            ->with('success', 'Borrow record created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BorrowRecord $borrow)
    {
        return view('borrows.show', compact('borrow'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BorrowRecord $borrow)
    {
        $books = Book::all();
        $members = Member::all();
        return view('borrows.edit', compact('borrow', 'books', 'members'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBorrowRequest $request, BorrowRecord $borrow)
    {
        $oldStatus = $borrow->status;
        $borrow->update($request->validated());

        // Logic for returning book
        if ($oldStatus === 'borrowed' && $borrow->status === 'returned') {
            $borrow->book->increment('available_copies');
        } elseif ($oldStatus === 'returned' && $borrow->status === 'borrowed') {
            $borrow->book->decrement('available_copies');
        }

        return redirect()->route('borrows.index')
            ->with('success', 'Borrow record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BorrowRecord $borrow)
    {
        if ($borrow->status === 'borrowed') {
            $borrow->book->increment('available_copies');
        }

        $borrow->delete();

        return redirect()->route('borrows.index')
            ->with('success', 'Borrow record deleted successfully.');
    }
}
