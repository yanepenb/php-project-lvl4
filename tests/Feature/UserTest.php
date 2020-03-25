<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use DatabaseMigrations;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->actingAs($this->user);
    }
    
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUsersIndex()
    {
        $response = $this->get(route('users.index'));

        $response->assertStatus(200);
    }

    public function testUsersShow()
    {
        $response = $this->get(route('users.show', $this->user));
        $userName = $this->user->name;

        $response->assertStatus(200)->assertSee($userName);
    }

    public function testUsersUpdate()
    {
        $expected = [
            'nickname' => 'Update',
            'name' => 'updateName',
            'email' => 'update@email.com',
            'lastName' => 'Updaticus',
            'gender' => 'female',
            'birthday' => '2020-12-20',
        ];
        print_r($this->user->toArray());
        $url = route('users.update', $this->user);
        $response = $this->patch($url, $expected);
        $response->assertStatus(302);
        $this->user->refresh();
        print_r($this->user->toArray());
        $this->assertDatabaseHas('users', $expected);
    }
}
