<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTestTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $inserts = [
            ['email' => 'takahashi@hoge.com',
             'password' => Hash::make('password'),
             'name' => 'takahashi',
             'status' => 'provisional',
             'admin_id' => 1,
             'provisional_registered_at' => date("Y-m-d H:i:s"),
             'created_at' => date("Y-m-d H:i:s"),
             'updated_at' => date("Y-m-d H:i:s"),
            ],
            ['email' => 'harataku@hoge.com',
             'password' => Hash::make('password'),
             'name' => 'harataku',
             'status' => 'member',
             'admin_id' => 1,
             'provisional_registered_at' => date("Y-m-d H:i:s"),
             'created_at' => date("Y-m-d H:i:s"),
             'updated_at' => date("Y-m-d H:i:s"),
            ],
            ['email' => 'soma@hoge.com',
             'password' => Hash::make('password'),
             'name' => 'soma',
             'status' => 'invite',
             'admin_id' => 1,
             'provisional_registered_at' => date("Y-m-d H:i:s"),
             'created_at' => date("Y-m-d H:i:s"),
             'updated_at' => date("Y-m-d H:i:s"),
            ],
            ['email' => 'yaba@hoge.com',
             'password' => Hash::make('password'),
             'name' => 'yabaihito',
             'status' => 'leave',
             'admin_id' => 1,
             'provisional_registered_at' => date("Y-m-d H:i:s"),
             'created_at' => date("Y-m-d H:i:s"),
             'updated_at' => date("Y-m-d H:i:s"),
            ],
        ];
        DB::table('users')->insert($inserts);
    }
}
