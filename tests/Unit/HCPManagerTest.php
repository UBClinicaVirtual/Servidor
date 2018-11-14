<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use App\Managers\HCPManager as  HCPManager;
use App\User as User;

class HCPManagerTest extends TestCase
{
    protected $manager;

    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate:refresh');
        $this->manager = new HCPManager();
    }

    public function validData(){
        $testData = array();
        $testData['HCP'] = array('first_name' => 'test','last_name' => 'test', 'birth_date' => '1987-01-01','gender_id' => '1', 'identification_number' => '0303456', 'register_number' => 'RG3685','phone' => '1234-4758','adress' => 'Street 1234');
        return $testData;
    }

    public function invalidData(){
        $testData = array();
        $testData['HCP'] = array('first_name' => 'te','last_name' => 'test', 'birth_date' => '1987-01-01','gender_id' => '1', 'identification_number' => '0303456', 'register_number' => 'RG3685','phone' => '1234-4758','adress' => 'Street 1234');
        return $testData;
    }


    public function test_HCPManager_Update_Profile_Response_With_Invalid_Data(){
        $user = new User();
        $user->fill(array('name' => 'test','email' => 'test@testing.com','password' => 'pass123','active' => '1'));
        $response = $this->manager->update_profile($user,$this->invalidData());
        $this->assertEquals(403,$response->getStatusCode());
    }
}