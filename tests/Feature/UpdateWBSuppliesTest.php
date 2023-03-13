<?php

namespace Tests\Feature;


use Tests\TestCase;

class UpdateWBSuppliesTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testJobsEvents()
    {


        $job = new \App\Jobs\UpdateWBSupplies;

        $job->handle();


    }
}