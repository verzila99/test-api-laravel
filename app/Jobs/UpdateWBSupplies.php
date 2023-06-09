<?php

namespace App\Jobs;

use App\Models\WbSupply;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class UpdateWBSupplies implements ShouldQueue
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
        )->get('https://statistics-api.wildberries.ru/api/v1/supplier/incomes', [
                'dateFrom' => Carbon::create(2022, 1, 1, 0, 0, 0, "Europe/Moscow")->toDateTimeLocalString()

            ]);

        if ($response->successful()) {

            DB::transaction(function () use ($response) {

                foreach ($response->json() as $key => $value) {

                    DB::table('wb_supplies')->insertOrIgnore([
                        'incomeId' => $value['incomeId'],
                        'number' => $value['number'],
                        'date' => $value['date'],
                        'lastChangeDate' => $value['lastChangeDate'],
                        'supplierArticle' => $value['supplierArticle'],
                        'techSize' => $value['techSize'],
                        'barcode' => $value['barcode'],
                        'quantity' => $value['quantity'],
                        'totalPrice' => $value['totalPrice'],
                        'dateClose' => $value['dateClose'],
                        'warehouseName' => $value['warehouseName'],
                        'nmId' => $value['nmId'],
                        'status' => $value['status']
                    ]);
                }
            });

        } else {
            $response->throw();
        }
    }
}