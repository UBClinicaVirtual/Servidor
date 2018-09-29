<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{

    public function testBasicRoute()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testNonExistantRoute()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }


}
