<?php

namespace App\Jobs;

use App\Models\WbSale;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class UpdateWBSales implements ShouldQueue
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
        )->get('https://statistics-api.wildberries.ru/api/v1/supplier/sales', [
                'dateFrom' => Carbon::create(2023, 1, 1, 0, 0, 0, "Europe/Moscow")->toDateTimeLocalString(),
                'flag' => 0
            ]);

        if ($response->successful()) {

            DB::transaction(function () use ($response) {

                foreach ($response->json() as $key => $value) {

                    DB::table('wb_sales')->insertOrIgnore([
                        'gNumber' => $value['gNumber'],
                        'date' => $value['date'],
                        'lastChangeDate' => $value['lastChangeDate'],
                        'supplierArticle' => $value['supplierArticle'],
                        'techSize' => $value['techSize'],
                        'barcode' => $value['barcode'],
                        'totalPrice' => $value['totalPrice'],
                        'discountPercent' => $value['discountPercent'],
                        'isSupply' => $value['isSupply'],
                        'isRealization' => $value['isRealization'],
                        'promoCodeDiscount' => $value['promoCodeDiscount'],
                        'warehouseName' => $value['warehouseName'],
                        'countryName' => $value['countryName'],
                        'oblastOkrugName' => $value['oblastOkrugName'],
                        'regionName' => $value['regionName'],
                        'incomeID' => $value['incomeID'],
                        'saleID' => $value['saleID'],
                        'odid' => $value['odid'],
                        'spp' => $value['spp'],
                        'forPay' => $value['forPay'],
                        'finishedPrice' => $value['finishedPrice'],
                        'priceWithDisc' => $value['priceWithDisc'],
                        'nmId' => $value['nmId'],
                        'subject' => $value['subject'],
                        'category' => $value['category'],
                        'brand' => $value['brand'],
                        'isStorno' => $value['IsStorno'],
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