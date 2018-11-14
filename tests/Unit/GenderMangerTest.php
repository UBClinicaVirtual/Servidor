<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use App\Managers\GenderManager as  GenderManager;

class GenderManagerTest extends TestCase
{
    protected $manager;

    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate:refresh');
        $this->manager = new GenderManager();
    }

    public function test_GenderManager_all_Response(){
    	$response = $this->manager->all(array (" "));
    	$this->assertEquals(200,$response->getStatusCode());

    }

    public function test_GenderManager_all_Returns_Correct_Data_Structure(){
    	$response = $this->manager->all(array (" "));
    	$content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('genders', $content);

    }

}