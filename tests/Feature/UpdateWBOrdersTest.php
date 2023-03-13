<?php

namespace Tests\Feature;


use Tests\TestCase;

class UpdateWBOrdersTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testJobsEvents()
    {


        $job = new \App\Jobs\UpdateWBOrders;

        $job->handle();


    }
}