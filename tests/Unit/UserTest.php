<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use servidor\app\User;
use Illuminate\Support\Facades\Artisan;



class UserTest extends TestCase {

    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate:refresh');
    }

    public function testUserDeactivate()

    {
    	$obj = new \App\User;
        $obj->fill(array('name' => 'test','email' => 'test@testing.com','password' => 'pass123','active' => '0'));
        $obj->deactivate();
    	$this->assertNull($obj->api_token);
    	$this->assertEquals($obj->active,0);
    }

    public function testUserGenerateToken()

    {
        $obj= new \App\User;
        $obj->fill(array('name' => 'test','email' => 'test@testing.com','password' => 'pass123','active' => '0'));  
        $obj=null;
        $dummyToken = $obj->api_token;
        $this->assertEquals($obj->api_token,$dummyToken); 
    }
    /**
    *@depends this::testUserGenerateToken
    */

    public function testUserRevokeToken()

    {
    	$obj = new \App\User;
        $obj->fill(array('name' => 'test','email' => 'test@testing.com','password' => 'pass123','active' => '0'));  
        $obj->generateToken();
        $obj->revokeToken();
    	$this->assertNull($obj->api_token);
    }

    public function testUserActive()

    {
        $obj= new \App\User;
        $obj->fill(array('name' => 'test','email' => 'test@testing.com','password' => 'pass123','active' => '0'));
        $this->assertEquals($obj->active, 0);
    }
}
