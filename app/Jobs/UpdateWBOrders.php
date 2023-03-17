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

class UpdateWBOrders implements ShouldQueue
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
        $response = Http::retry(3, 100)->withHeaders(
            [
                'Authorization' => env('WB_STATISTICS_API_KEY')
            ]
        )->get('https://statistics-api.wildberries.ru/api/v1/supplier/orders', [
                'dateFrom' => Carbon::create(2023, 1, 1, 0, 0, 0, "Europe/Moscow")->toDateTimeLocalString(),
                'flag' => 0
            ]);

        if ($response->successful()) {

            DB::transaction(function () use ($response) {

                foreach ($response->json() as $key => $value) {

                    DB::table('wb_orders')->insertOrIgnore([
                        'gNumber' => $value['gNumber'],
                        'date' => $value['date'],
                        'lastChangeDate' => $value['lastChangeDate'],
                        'supplierArticle' => $value['supplierArticle'],
                        'techSize' => $value['techSize'],
                        'barcode' => $value['barcode'],
                        'totalPrice' => $value['totalPrice'],
                        'discountPercent' => $value['discountPercent'],
                        'warehouseName' => $value['warehouseName'],
                        'oblast' => $value['oblast'],
                        'incomeID' => $value['incomeID'],
                        'odid' => $value['odid'],
                        'nmId' => $value['nmId'],
                        'subject' => $value['subject'],
                        'category' => $value['category'],
                        'brand' => $value['brand'],
                        'isCancel' => $value['isCancel'],
                        'cancel_dt' => $value['cancel_dt'],
                        'sticker' => $value['sticker'],
                        'srid' => $value['srid'],
                        'created_at' => Carbon::today('Europe/Moscow'),
                        'updated_at' => Carbon::today('Europe/Moscow'),
                    ]);
                }
            });
        } else {
            $response->throw();
        }
    }
}