<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class UpdateWBPrices implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = Http::retry(3, 100)->withHeaders([
            'Authorization' => env('WB_STANDARD_API_KEY')
        ])->get('https://suppliers-api.wildberries.ru/public/api/v1/info', [
                'quantity' => 0
            ]);

        if ($response->successful()) {

            DB::transaction(function () use ($response) {

                DB::table('wb_prices')->where(
                    'date',
                    '=', Carbon::today('Europe/Moscow')->format('Y-m-d'))->delete();
                foreach ($response->json() as $value) {



                    DB::table('wb_prices')->insert(
                        [
                            'nmId' => $value['nmId'],
                            'date' => Carbon::today('Europe/Moscow')->format('Y-m-d'),
                            'price' => $value['price'],
                            'discount' => $value['discount'],
                            'promoCode' => $value['promoCode']
                        ]

                    );
                }
            });

        } else {
            $response->throw();
        }
    }
}