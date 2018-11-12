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


    function testManagerSpecialtyCreateInvalidInput() {
        $manager = new SpecialityManager();
        $response = $manager->create(array('name' => 'as'));
        $this->assertEquals(403, $response->getStatusCode());

    }

    function testManagerSpecialtyCreatevalidInput() {
    $manager = new SpecialityManager();
    $response = $manager->create(array('name' => 'asd'));
    $this->assertEquals(201, $response->getStatusCode());

    }















    /*
    public function testCreateSpeciality()
    {


        /*$name = 'Cirujano';
        $user = new \App\User;
        $user->fill(array('name' => 'test','email' => 'test@testing.com','password' => 'pass123','active' => '1'));
        $user->generateToken();
        $user  = factory(User::cl ass,1)->create();
        $user = Auth::user();
        $api_token = $user->api_token;
        $this->WithoutMiddleware();
        $name = 'Cirujano';
        $user = new \App\User;
        $this->actingAs($user,'web');
        $api_toke = $user->api_token;
        $response = $this->client->post('/api/v1/speciality', 
            array(
                'headers' => array('Accept' => 'application/json','Content-type' => 'application/json','Authorization' => 'Bearer AN_API_TOKEN'),
                'form_params'    => array('name' => $name)
            )
        );
        $this->assertEquals(201,$response->getStatusCode());
    }
    */
}