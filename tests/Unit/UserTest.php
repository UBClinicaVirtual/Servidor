<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use servidor\app\User;


class UserTest extends TestCase{

    public function testUserDeactivated()
    {
    	$user = new \App\User;
    	/*$user->api_token = null;
    	$user->active = 0;*/
        $user->deactivate();
    	$this->assertNull($user->api_token);
    	$this->assertEquals($user->active,0);
    }

    public function testUserTokenRevoked()
    {
    	$user = new \App\User;
    	$user->api_token = null;
    	$this->assertNull($user->api_token);
    }

    public function testUserGenerateToken()
    {
    	$user= new \App\User;
    	$user->api_token = str_random(60);
    	$this->assertNotNull($user->api_token); 
    }
}
