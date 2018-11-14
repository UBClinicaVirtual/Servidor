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

    public function test_GenderManager_all(){
    	$response = $this->manager->all(array (" "));
    	$this->assertEquals(200,$response->getStatusCode());

    }

}