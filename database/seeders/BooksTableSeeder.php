<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;

class BooksTableSeeder extends Seeder
{
    public function run()
    {
        for ($i = 3; $i <= 10; $i++) {
            Book::create([
                'title' => 'Book Title ' . $i,
                'author' => 'Author Name ' . $i,
                'description' => 'Description of Book ' . $i,
                'publication_date' => now()->subDays($i)->format('Y-m-d'),
                'isbn' => mt_rand(1000000000, 9999999999),
                'genre' => $i % 2 == 0 ? 'Fiction' : 'Non-Fiction',
            ]);
        }
    }
}
