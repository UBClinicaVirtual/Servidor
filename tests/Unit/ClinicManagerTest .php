<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use App\Managers\ClinicManager as  ClinicManager;
use App\User as User;

class ClinicManagerTest extends TestCase
{
    protected $manager;
    protected $user;

    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate:refresh');
        $this->manager = new ClinicManager();
        $this->user = new User();
        $this->user->fill(array('name' => 'test','email' => 'test@testing.com','password' => 'pass123','active' => '1'));
        $this->user->generateToken();
    }

    public function validData(){
        $testData = array();
        $testData['clinic'] = array('business_name' => 'test','business_number' => 'testtesttest','adress' => 'fakeStreet 123', 'phone' => '12345');
        return $testData;
    }

    public function invalidData(){
        $testData = array();
        $testData['clinic'] = array('business_name' => 'te','business_number' => 'testtesttest','adress' => 'fakeStreet 123', 'phone' => '12345');
        return $testData;
    }


    public function test_ClinicManager_Update_Profile_Response_With_Invalid_Data(){
        $response = $this->manager->update_profile($this->user,$this->invalidData());
        $this->assertEquals(403,$response->getStatusCode());
    }

    public function test_Clinic_Update_Profile_Response_With_valid_Data(){
        $response = $this->manager->update_profile($this->user,$this->validData());
        $this->assertEquals(200,$response->getStatusCode());
    }

    public function test_ClinicManager_Update_Profile_Returns_Correct_Data_Structure(){
        $response = $this->manager->update_profile($this->user,$this->validData());
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('clinic', $content);
        $this->assertArrayHasKey('business_name', $content['hcp']);
    }
    public function test_ClinicManager_Update_Profile_Returns_Correct_Data(){
        $response = $this->manager->update_profile($this->user,$this->validData());
        $content = json_decode($response->getContent(), true);
        $this->assertEquals($this->validData()['clinic']['business_name'],$content['clinic']['business_name']);
    }

    /*public function test_HCPManager_Get_Profile_Response_With_valid_Data(){
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

    public function test_HCPManager_Search_With_valid_Data(){
        $response = $this->manager->search($this->validSearchData());
        $this->assertEquals(200,$response->getStatusCode());
    }

    public function test_HCPManager_Search_With_Invalid_Data(){
        $response = $this->manager->search($this->invalidSearchData());
        $this->assertEquals(403,$response->getStatusCode());
    }

    public function test_HCPManager_Search_Returns_Correct_Data_Structure(){
        $this->manager->update_profile($this->user,$this->validData());
        $response = $this->manager->search($this->validSearchData());
        $content = json_decode($response->getContent(),true);
        $this->assertArrayHasKey('hcps',$content);
    }*/

}