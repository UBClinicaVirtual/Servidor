<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Artisan;
use App\Searchers\SpecialitySearch\SpecialitySearch as SpecialitySearch;
use App\Speciality as Speciality;
use App\Managers\SpecialityManager as  SpecialityManager;

require './vendor/autoload.php';


class SpecialityManagerTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate:refresh');
    }


    function test_ManagerSpecialty_Create_With_Invalid_Input() {
        $manager = new SpecialityManager();
        $response = $manager->create(array('name' => 'as'));
        $this->assertEquals(403, $response->getStatusCode());

    }

    function test_ManagerSpecialty_Create_With_Valid_Input() {
        $manager = new SpecialityManager();
        $response = $manager->create(array('name' => 'asd'));
        $this->assertEquals(201, $response->getStatusCode());

    }

    function test_ManagerSpecialty_Create_Returns_Correct_Data() {
        $manager = new SpecialityManager();
        $response = $manager->create(array('name' => 'asd'));
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('name', $content);
        $this->assertArrayHasKey('id', $content);
    }

    function test_ManagerSpecialty_Create_Returns_Correct_Input() {
        $input = 'asd';
        $manager = new SpecialityManager();
        $response = $manager->create(array('name' => $input));
        $content = json_decode($response->getContent(), true);
        $this -> assertEquals($input, $content['name'] );

    }

}