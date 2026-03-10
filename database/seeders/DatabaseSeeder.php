<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // Sample Books
        \App\Models\Book::create([
            'title' => 'The Great Gatsby',
            'author' => 'F. Scott Fitzgerald',
            'ISBN' => '9780743273565',
            'category' => 'Fiction',
            'published_year' => 1925,
            'available_copies' => 5,
        ]);

        \App\Models\Book::create([
            'title' => 'Clean Code',
            'author' => 'Robert C. Martin',
            'ISBN' => '9780132350884',
            'category' => 'Technology',
            'published_year' => 2008,
            'available_copies' => 3,
        ]);

        // Sample Members
        \App\Models\Member::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'membership_date' => now(),
        ]);

        \App\Models\Member::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'phone' => '0987654321',
            'membership_date' => now(),
        ]);
    }
}
