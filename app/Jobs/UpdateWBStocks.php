<?php

namespace App\Jobs;

use App\Models\WbStock;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class UpdateWBStocks implements ShouldQueue
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
        )->get('https://statistics-api.wildberries.ru/api/v1/supplier/stocks', [
                'dateFrom' => Carbon::yesterday("Europe/Moscow")->toDateTimeLocalString()
            ]);

        if ($response->successful()) {

            DB::transaction(function () use ($response) {


                DB::table('wb_stocks')->where(
                    'date',
                    '=', Carbon::today('Europe/Moscow')->format('Y-m-d'))->delete();

                foreach ($response->json() as $key => $value) {

                    DB::table('wb_stocks')->insert(
                        [
                            'barcode' => $value['barcode'],
                            'warehouseName' => $value['warehouseName'],
                            'date' => Carbon::today('Europe/Moscow'),
                            'lastChangeDate' => $value['lastChangeDate'],
                            'supplierArticle' => $value['supplierArticle'],
                            'techSize' => $value['techSize'],
                            'quantity' => $value['quantity'],
                            'isSupply' => $value['isSupply'],
                            'isRealization' => $value['isRealization'],
                            'quantityFull' => $value['quantityFull'],
                            'nmId' => $value['nmId'],
                            'subject' => $value['subject'],
                            'category' => $value['category'],
                            'daysOnSite' => $value['daysOnSite'],
                            'brand' => $value['brand'],
                            'SCCode' => $value['SCCode'],
                            'Price' => $value['Price'],
                            'Discount' => $value['Discount'],
                        ], );
                }
            });
        } else {
            $response->throw();
        }
    }
}