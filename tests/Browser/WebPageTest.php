<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ExampleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testHomeExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
            ->assertSee('InduLab 數學建模系統')
            ->assertSee('姓名：')
            ->assertSee('學校：')
            ->assertSee('學號：')
            ->assertSee('進行實驗：')
            ->clickLink('InduLab')
            ->clickLink('開始實驗')
            ->clickLink('斜坡運動')
            ->clickLink('繪製圖表');
        });
    }

    // 測試首頁輸入後自動導向到import頁面
    public function testImportExample()
    {
        $this->browse(function ($browser) {
            $browser->visit('/')
                    ->type('name', 'dummy-name')
                    ->type('school', 'dummy-school')
                    ->type('id', 'dummy-id')
                    ->press('開始建模')
                    ->assertPathIs('/import');
        });
    }

    public function testSlopeExample()
    {
        $this->browse(function ($browser) {
            $browser->visit('/slope')
                    ->type('name', 'dummy-name')
                    ->type('school', 'dummy-school')
                    ->type('id', 'dummy-id')
                    ->press('開始建模')
                    ->assertSee('使用教學');
        });
    }
}
