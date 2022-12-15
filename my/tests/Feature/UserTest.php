<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{

    public function usersStatusTestProvider()
    {
        return [
            [
                "id" => 1,
                "expected" => User::STATUS_TYPE_PROVISIONAL
            ],
            [
                "id" => 2,
                "expected" => User::STATUS_TYPE_MEMBER
            ],
            [
                "id" => 3,
                "expected" => User::STATUS_TYPE_INVITE
            ],
            [
                "id" => 4,
                "expected" => User::STATUS_TYPE_LEAVE
            ],
        ];
    }

    /**
    * @test 
    * @dataProvider usersStatusTestProvider
    */
    public function testUsersStatusTest($id, $expected)
    {
        $user = User::find($id);
        $this->assertEquals($user->status, $expected);
    }

    /**
     *
     * @return void
     */
    public function test_top_access()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }
}
