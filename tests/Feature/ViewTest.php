<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('InduLab 數學建模系統');
        $response->assertSee('姓名：');
        $response->assertSee('學校：');
        $response->assertSee('學號：');
        $response->assertSee('進行實驗：');
        $response->assertSee('開始建模');
    }
}
