<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateWBPricesTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testJobsEvents()
    {


        $job = new \App\Jobs\UpdateWBPrices;

        $job->handle();


    }
}