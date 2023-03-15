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

            while (count($response->json()['result']['items']) >= $this->limit - 1) {

                $response = $this->makeRequest($response->json()['result']['last_id']);

                $result = array_merge($result, $response->json()['result']['items']);

            }

            DB::transaction(function () use ($result) {

                foreach ($result as $value) {

                    foreach ($value['stocks'] as $stock) {

                        DB::table('ozon_stocks')->upsert(
                            [
                                'offer_id' => $value['offer_id'],
                                'product_id' => $value['product_id'],
                                'present' => $stock['present'],
                                'reserved' => $stock['reserved'],
                                'type' => $stock['type'],
                                'created_at' => Carbon::today('Europe/Moscow')
                            ],
                            ['offer_id', 'product_id', 'created_at'],
                            ['present', 'reserved', 'type']);
                    }
                }
            });
        } else {
            $response->throw();
        }
    }
}