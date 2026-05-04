<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::findOrFail(1);

        $accounts = [
            [
                'name' => 'Personal Account',
                'slug' => 'personal-account',
            ],
            [
                'name' => 'Work Account',
                'slug' => 'work-account',
            ],
        ];

        foreach ($accounts as $accountData) {
            $account = Account::firstOrCreate(
                ['slug' => $accountData['slug']],
                [
                    'name' => $accountData['name'],
                    'owner_user_id' => $user->id,
                ]
            );

            $account->users()->syncWithoutDetaching([
                $user->id => [
                    'role' => 'owner',
                ],
            ]);
        }
    }
}
