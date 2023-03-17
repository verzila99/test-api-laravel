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

class UpdateOZONStocks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $limit = 1000;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    public function makeRequest(string $lastId = ''): \Illuminate\Http\Client\Response
    {
        $response = Http::retry(3, 100)->withHeaders(
            [
                'Client-Id' => env('OZON_CLIENT_ID'),
                'Api-Key' => env('OZON_API_KEY')
            ]
        )->post('https://api-seller.ozon.ru/v3/product/info/stocks', [
                'filter' => [
                    'offer_id' => [],
                    'product_id' => [],
                    'visibility' => 'ALL'
                ],
                'limit' => $this->limit,
                "last_id" => $lastId
            ]);
        return $response;
    }
    public function transaction($result)
    {
        DB::transaction(function () use ($result) {

            DB::table('ozon_stocks')->where(
                'date',
                '=', Carbon::today('Europe/Moscow')->format('Y-m-d'))->delete();

            foreach ($result as $value) {

                foreach ($value['stocks'] as $stock) {
                    if ($stock['type'] === 'fbs') {
                        DB::table('ozon_stocks')->updateOrInsert(
                            [
                                'product_id' => $value['product_id'],
                                'date' => Carbon::today('Europe/Moscow'),
                            ],
                            [
                                'offer_id' => $value['offer_id'],
                                'fbs_present' => $stock['present'],
                                'fbs_reserved' => $stock['reserved'],

                            ],
                        );
                    } else {
                        DB::table('ozon_stocks')->updateOrInsert(
                            [
                                'product_id' => $value['product_id'],
                                'date' => Carbon::today('Europe/Moscow'),
                            ],
                            [
                                'offer_id' => $value['offer_id'],
                                'fbo_present' => $stock['present'],
                                'fbo_reserved' => $stock['reserved'],

                            ],
                        );
                    }
                }
            }
        });
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = $this->makeRequest();

        if ($response->successful()) {

            $result = $response->json()['result']['items'];

            $this->transaction($result);

            while (count($response->json()['result']['items']) >= $this->limit - 1) {

                $this->transaction($result);

                $response = $this->makeRequest($response->json()['result']['last_id']);

                $result = $response->json()['result']['items'];

            }


        } else {
            $response->throw();
        }
    }
}