<?php

namespace Tests\Feature;


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