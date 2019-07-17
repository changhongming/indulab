<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    /**
     * @test
     */
    public function testExample()
    {
        $response = $this->withSession(['student_id' => '2000'])->json('POST', '/api/slope', ['mass' => 20, 'length' => 1, 'angle' => 20]);
        $response->assertStatus(200);
    }
}
