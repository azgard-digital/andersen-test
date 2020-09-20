<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => Str::random(10),
            'email' => Str::random(10) . '@gmail.com',
            'password' => Hash::make('123456'),
        ]);

        $user = DB::table('users')->first(['id']);

        if (!$user) {
            return;
        }

        $this->initWallet($user->id);

        $wallet = DB::table('wallets')->first(['id']);

        if (!$wallet) {
            return;
        }

        $this->initTransaction($user->id, $wallet->id);
    }

    /**
     * @param int $userId
     * @param int $walletId
     * @return void
     */
    protected function initTransaction(int $userId, int $walletId): void
    {
        DB::table('transactions')->insert([
            'created_at' => '2020-09-14 07:39:33',
            'updated_at' => '2020-09-14 07:39:33',
            'user_id' => $userId,
            'wallet_id' => $walletId,
            'status' => 1,
            'amount' => 100000,
            'fee' => 0,
        ]);
    }

    /**
     * @param int $userId
     * @return void
     */
    protected function initWallet(int $userId): void
    {
        DB::table('wallets')->insert([
            'address' => '5a4221f08980d4ec6ba0c437734473d7',
            'user_id' => $userId,
            'balance' => 100000000,
        ]);
    }
}
