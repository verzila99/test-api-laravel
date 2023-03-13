<?php

namespace Tests\Feature;

use Tests\TestCase;

class UpdateWBSalesTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testJobsEvents()
    {


        $job = new \App\Jobs\UpdateWBSales;

        $job->handle();


    }
}