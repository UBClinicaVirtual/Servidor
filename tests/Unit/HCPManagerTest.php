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
    protected $user;

    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate:refresh');
        $this->manager = new HCPManager();
        $this->user = new User();
        $this->user->fill(array('name' => 'test','email' => 'test@testing.com','password' => 'pass123','active' => '1'));
        $this->user->generateToken();
    }

    public function validData(){
        $testData = array();
        $testData['hcp'] = array('first_name' => 'test','last_name' => 'test', 'birth_date' => '1987-01-01','gender_id' => '1', 'identification_number' => '0303456', 'register_number' => 'RG3685','phone' => '1234-4758','address' => 'Street 1234');
        return $testData;
    }

    public function invalidData(){
        $testData = array();
        $testData['hcp'] = array('first_name' => 'te','last_name' => 'test', 'birth_date' => '1987-01-01','gender_id' => '1', 'identification_number' => '0303456', 'register_number' => 'RG3685','phone' => '1234-4758','address' => 'Street 1234');
        return $testData;
    }


    public function test_HCPManager_Update_Profile_Response_With_Invalid_Data(){
        $response = $this->manager->update_profile($this->user,$this->invalidData());
        $this->assertEquals(403,$response->getStatusCode());
    }

    public function test_HCPManager_Update_Profile_Response_With_valid_Data(){
        $response = $this->manager->update_profile($this->user,$this->validData());
        $this->assertEquals(200,$response->getStatusCode());
    }

    public function test_HCPManager_Update_Profile_Returns_Correct_Data_Structure(){
        $response = $this->manager->update_profile($this->user,$this->validData());
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('hcp', $content);
        $this->assertArrayHasKey('id', $content['hcp']);
    }
    public function test_HCPManager_Update_Profile_Returns_Correct_Data(){
        $response = $this->manager->update_profile($this->user,$this->validData());
        $content = json_decode($response->getContent(), true);
        $this->assertEquals($this->validData()['hcp']['first_name'],$content['hcp']['first_name']);
    }

    public function test_HCPManager_Get_Profile_Response_With_valid_Data(){
        $this->manager->update_profile($this->user,$this->validData());
        $response = $this->manager->get_profile($this->user, array( " " ));

        $this->assertEquals(200,$response->getStatusCode());
    }

    public function test_HCPManager_Get_Profile_Returns_Correct_Data_Structure(){
        $this->manager->update_profile($this->user,$this->validData());
        $response = $this->manager->get_profile($this->user, array(" "));
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('hcp', $content);
        $this->assertArrayHasKey('id', $content['hcp']);

    }

    public function test_HCPManager_Get_Profile_Returns_Correct_Data(){
        $this->manager->update_profile($this->user,$this->validData());
        $response = $this->manager->get_profile($this->user, array(" "));
        $content = json_decode($response->getContent(), true);
        $this->assertEquals($this->validData()['hcp']['first_name'],$content['hcp']['first_name']);
    }


}