<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Design Resources',
            'Tutorials',
            'Inspiration',
            'Code Snippets',
            'Marketing',
            'Social Media',
            'Photography',
            'Videos',
            'Articles',
            'Tools',
            'UI Kits',
            'Dashboards',
            'Branding',
            'Research',
            'Ideas',
            'Personal',
            'Work',
            'Clients',
            'Learning',
            'Archive',
        ];

        Account::query()->each(function (Account $account) use ($categories) {
            foreach ($categories as $index => $name) {
                Category::firstOrCreate(
                    [
                        'account_id' => $account->id,
                        'slug' => Str::slug($name),
                    ],
                    [
                        'name' => $name,
                        'color' => null,
                        'sort_order' => $index + 1,
                    ]
                );
            }
        });
    }
}
