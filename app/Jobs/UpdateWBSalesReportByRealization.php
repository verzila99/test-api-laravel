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

class UpdateWBSalesReportByRealization implements ShouldQueue
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
        )->get('https://statistics-api.wildberries.ru/api/v1/supplier/reportDetailByPeriod', [
                'dateFrom' => Carbon::yesterday("UTC")->toIso8601ZuluString(),
                'dateTo' => Carbon::today("UTC")->toIso8601ZuluString(),
            ]);

        if ($response->successful() && $response->json() !== null) {

            DB::transaction(function () use ($response) {

                foreach ($response->json() as $key => $value) {

                    DB::table('wb_sales_report_by_realizations')->insert([
                        'realizationreport_id' => $value['realizationreport_id'],
                        'date_from' => Carbon::parse($value['date_from']),
                        'date_to' => Carbon::parse($value['date_to']),
                        'create_dt' => Carbon::parse($value['create_dt']),
                        'suppliercontract_code' => $value['suppliercontract_code'],
                        'rrd_id' => $value['rrd_id'],
                        'gi_id' => $value['gi_id'],
                        'subject_name' => $value['subject_name'],
                        'nm_id' => $value['nm_id'],
                        'brand_name' => $value['brand_name'],
                        'sa_name' => $value['sa_name'],
                        'ts_name' => $value['ts_name'],
                        'barcode' => $value['barcode'],
                        'doc_type_name' => $value['doc_type_name'],
                        'quantity' => $value['quantity'],
                        'retail_price' => $value['retail_price'],
                        'retail_amount' => $value['retail_amount'],
                        'sale_percent' => $value['sale_percent'],
                        'commission_percent' => $value['commission_percent'],
                        'office_name' => $value['office_name'],
                        'supplier_oper_name' => $value['supplier_oper_name'],
                        'order_dt' => Carbon::parse($value['order_dt']),
                        'sale_dt' => Carbon::parse($value['sale_dt']),
                        'rr_dt' => Carbon::parse($value['rr_dt']),
                        'shk_id' => $value['shk_id'],
                        'retail_price_withdisc_rub' => $value['retail_price_withdisc_rub'],
                        'delivery_amount' => $value['delivery_amount'],
                        'return_amount' => $value['return_amount'],
                        'delivery_rub' => $value['delivery_rub'],
                        'gi_box_type_name' => $value['gi_box_type_name'],
                        'product_discount_for_report' => $value['product_discount_for_report'],
                        'supplier_promo' => $value['supplier_promo'],
                        'rid' => $value['rid'],
                        'ppvz_spp_prc' => $value['ppvz_spp_prc'],
                        'ppvz_kvw_prc_base' => $value['ppvz_kvw_prc_base'],
                        'ppvz_kvw_prc' => $value['ppvz_kvw_prc'],
                        'ppvz_sales_commission' => $value['ppvz_sales_commission'],
                        'ppvz_for_pay' => $value['ppvz_for_pay'],
                        'ppvz_reward' => $value['ppvz_reward'],
                        'acquiring_fee' => $value['acquiring_fee'],
                        'acquiring_bank' => $value['acquiring_bank'],
                        'ppvz_vw' => $value['ppvz_vw'],
                        'ppvz_vw_nds' => $value['ppvz_vw_nds'],
                        'ppvz_office_id' => $value['ppvz_office_id'],
                        'ppvz_office_name' => $value['ppvz_office_name'] ?? '',
                        'ppvz_supplier_id' => $value['ppvz_supplier_id'],
                        'ppvz_supplier_name' => $value['ppvz_supplier_name'],
                        'ppvz_inn' => $value['ppvz_inn'],
                        'declaration_number' => $value['declaration_number'],
                        'bonus_type_name' => $value['bonus_type_name'] ?? '',
                        'sticker_id' => $value['sticker_id'],
                        'site_country' => $value['site_country'],
                        'penalty' => $value['penalty'],
                        'additional_payment' => $value['additional_payment'],
                        'kiz' => $value['kiz'] ?? '',
                        'srid' => $value['srid'],

                    ]);
                }
            });
        } elseif ($response->json() !== null) {

            $response->throw();
        }

    }
}