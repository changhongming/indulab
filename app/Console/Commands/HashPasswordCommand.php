<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

// 將輸入密碼進行hash加密並輸出結果
class HashPasswordCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:hash {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '將輸入密碼進行hash加密產生加密密碼';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info(Hash::make($this->argument('password')));
    }
}
