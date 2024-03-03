<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();

        // Filtering by author
        if ($request->has('author')) {
            $query->where('author', $request->input('author'));
        }

        // Filtering by title
        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->input('title') . '%');
        }

        // Filtering by genre
        if ($request->has('genre')) {
            $query->where('genre', $request->input('genre'));
        }

        // Filtering by date
        if ($request->has('date')) {
            $query->whereDate('publication_date', $request->input('date'));
        }

        // Filtering by date range
        if ($request->has('fromto')) {
            $dates = explode(',', $request->input('fromto'));
            if (count($dates) == 2) {
                $query->whereBetween('publication_date', $dates);
            }
        }

        $books = $query->get();
        // return count number of books
        if ($request->has('count') && is_numeric($request->input('count'))) {
            $count = intval($request->input('count'));
            $books = $books->take($count);
        }
        return response()->json($books);
    }



    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'publication_date' => 'required|date',
            'isbn' => 'nullable|string|unique:books',
            'genre' => 'nullable|string|max:255',
        ]);

        $book = Book::create($validatedData);

        return response()->json($book, 201);
    }

    public function show($id)
    {
        $book = Book::findOrFail($id);
        return response()->json($book);
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'publication_date' => 'required|date',
            'isbn' => 'nullable|string|unique:books,isbn,' . $book->id,
            'genre' => 'nullable|string|max:255',
        ]);

        $book->update($validatedData);

        return response()->json($book, 200);
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return response()->json(null, 204);
    }
}
