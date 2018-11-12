<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use App\Searchers\SpecialitySearch\SpecialitySearch as SpecialitySearch;
use App\Speciality as Speciality;
use App\Managers\SpecialityManager as  SpecialityManager;

require './vendor/autoload.php';


class SpecialityManagerTest extends TestCase
{
    protected $manager;

    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate:refresh');
        $this->manager = new SpecialityManager();
    }

    private function invalidData(){
        return array('name' => 'as');
    }

    private function validData(){
        return array('name' => 'asd');
    }


    function test_ManagerSpecialty_Create_With_Invalid_Input() {

        $response = $this->manager->create($this->invalidData());
        $this->assertEquals(403, $response->getStatusCode());

    }

    function test_ManagerSpecialty_Create_With_Valid_Input() {

        $response = $this->manager->create($this->validData());
        $this->assertEquals(201, $response->getStatusCode());

    }

    function test_ManagerSpecialty_Create_Returns_Correct_Data_Structure() {

        $response = $this->manager->create($this->validData());
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('name', $content);
        $this->assertArrayHasKey('id', $content);

    }

    function test_ManagerSpecialty_Create_Returns_Correct_Data() {

        $response = $this->manager->create($this->validData());
        $content = json_decode($response->getContent(), true);
        $this -> assertEquals($this->validData()['name'], $content['name'] );

    }

    function test_ManagerSpecialty_Update_With_Invalid_Input() {

        $speciality = new Speciality;
        $speciality->fill(array('name' => 'test'));
        $response = $this->manager->update($this->invalidData(), $speciality);
        $this->assertEquals(403, $response->getStatusCode());

    }

    function test_ManagerSpecialty_Update_With_Valid_Input() {

        $speciality = new Speciality;
        $speciality->fill(array('name' => 'test'));
        $response = $this->manager->update($this->validData(), $speciality);
        $this->assertEquals(201, $response->getStatusCode());

    }

    function test_ManagerSpecialty_Update_Returns_Correct_Data_Structure() {

        $speciality = new Speciality;
        $speciality->fill(array('name' => 'test'));
        $response = $this->manager->update($this->validData(), $speciality);
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('name', $content);
        $this->assertArrayHasKey('id', $content);

    }

    function test_ManagerSpecialty_Update_Returns_Correct_Data() {

        $speciality = new Speciality;
        $speciality->fill(array('name' => 'test'));
        $response = $this->manager->update($this->validData(), $speciality);
        $content = json_decode($response->getContent(), true);
        $this->assertEquals($this->validData()['name'], $content['name']);

    }

    function test_ManagerSpecialty_Search_With_Invalid_Data() {

        $this->manager->create($this->validData());
        $response = $this->manager->search($this->invalidData());
        $content = json_decode($response->getContent(), true);
        $this->assertEquals(403, $response->getStatusCode());
    }

    function test_ManagerSpecialty_Search_With_valid_Data() {

        $this->manager->create($this->validData());
        $response = $this->manager->search($this->validData());
        $content = json_decode($response->getContent(), true);
        $this->assertEquals(200, $response->getStatusCode());
    }

    function test_ManagerSpecialty_Search_Returns_Correct_Data_Structure() {

        $this->manager->create($this->validData());
        $response = $this->manager->search($this->validData());
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('specialities', $content);
    }

    function test_ManagerSpecialty_Search_Returns_Correct_Data() {
        
        $this->manager->create($this->validData());
        $response = $this->manager->search($this->validData());
        $content = json_decode($response->getContent(), true);
        $this->assertEquals($this->validData()['name'], $content['specialities'][0]['name']);
    }

}