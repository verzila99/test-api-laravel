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

class UpdateOZONPostings implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $offset;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->offset = 10;
    }

    public function makeRequest(int $offset = 0): \Illuminate\Http\Client\Response
    {
        $response = Http::retry(3, 100)->withHeaders(
            [
                'Client-Id' => env('OZON_CLIENT_ID'),
                'Api-Key' => env('OZON_API_KEY')
            ]
        )->post('https://api-seller.ozon.ru/v3/posting/fbs/list', [
                'dir' => 'desc',
                'filter' => [
                    'delivery_method_id' => [],
                    'provider_id' => [],
                    'since' => Carbon::yesterday("Europe/Moscow")->toIso8601ZuluString(),
                    'to' => Carbon::today("Europe/Moscow")->toIso8601ZuluString(),
                    'status' => '',
                    'warehouse_id' => [],
                ],
                'limit' => $this->offset,
                "offset" => $offset,
                'with' => [
                    'analytics_data' => true,
                    'barcodes' => true,
                    'financial_data' => true,

                ],
                'translit' => false,
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

            $result = $response->json()['result']['postings'];


            while ($response->json()['result']['has_next']) {

                $response = $this->makeRequest($this->offset);

                $result = array_merge($result, $response->json()['result']['postings']);

                $this->offset += $this->offset;
            }

            DB::transaction(function () use ($result) {

                foreach ($result as $value) {
                    DB::table('ozon_postings')->insertOrIgnore([
                        'addressee_name' => $value['addressee']['name'] ?? null,
                        'addressee_phone' => $value['addressee']['phone'] ?? null,
                        'city' => $value['analytics_data']['city'],
                        'delivery_date_begin' => Carbon::parse($value['analytics_data']['delivery_date_begin']),
                        'delivery_date_end' => Carbon::parse($value['analytics_data']['delivery_date_end']),
                        'delivery_type' => $value['analytics_data']['delivery_type'],
                        'is_legal' => $value['analytics_data']['is_legal'],
                        'is_premium' => $value['analytics_data']['is_premium'],
                        'payment_type_group_name' => $value['analytics_data']['payment_type_group_name'],
                        'region' => $value['analytics_data']['region'],
                        'tpl_provider' => $value['analytics_data']['tpl_provider'],
                        'tpl_provider_id' => $value['analytics_data']['tpl_provider_id'],
                        'warehouse' => $value['analytics_data']['warehouse'],
                        'warehouse_id' => $value['analytics_data']['warehouse_id'],
                        'lower_barcode' => $value['barcodes']['lower_barcode'],
                        'upper_barcode' => $value['barcodes']['upper_barcode'],
                        'affect_cancellation_rating' => $value['cancellation']['affect_cancellation_rating'],
                        'cancel_reason_id' => $value['cancellation']['cancel_reason_id'],
                        'cancellation_initiator' => $value['cancellation']['cancellation_initiator'],
                        'cancellation_type' => $value['cancellation']['cancellation_type'],
                        'cancelled_after_ship' => $value['cancellation']['cancelled_after_ship'],
                        'customer_address' => json_encode($value['customer']['address'] ?? null),
                        'customer_email' => $value['customer']['customer_email'] ?? null,
                        'customer_id' => $value['customer']['customer_id'] ?? null,
                        'customer_name' => $value['customer']['name'] ?? null,
                        'customer_phone' => $value['customer']['phone'] ?? null,
                        'delivering_date' => Carbon::parse($value['delivering_date']),
                        'delivery_method_id' => $value['delivery_method']['id'],
                        'delivery_method_name' => $value['delivery_method']['name'],
                        'delivery_method_tpl_provider' => $value['delivery_method']['tpl_provider'],
                        'delivery_method_tpl_provider_id' => $value['delivery_method']['tpl_provider_id'],
                        'delivery_method_warehouse' => $value['delivery_method']['warehouse'],
                        'delivery_method_warehouse_id' => $value['delivery_method']['warehouse_id'],
                        'financial_data_cluster_from' => $value['financial_data']['cluster_from'],
                        'financial_data_cluster_to' => $value['financial_data']['cluster_to'],
                        'financial_data_posting_services' => json_encode($value['financial_data']['posting_services']),
                        'financial_data_products' => json_encode($value['financial_data']['products']),
                        'in_process_at' => Carbon::parse($value['in_process_at']),
                        'is_express' => $value['is_express'],
                        'is_multibox' => $value['is_multibox'],
                        'multi_box_qty' => $value['multi_box_qty'],
                        'order_id' => $value['order_id'],
                        'order_number' => $value['order_number'],
                        'parent_posting_number' => $value['parent_posting_number'],
                        'products' => json_encode($value['products']),
                        'requirements' => json_encode($value['requirements']),
                        'shipment_date' => Carbon::parse($value['shipment_date']),
                        'status' => $value['status'],
                        'tpl_integration_type' => $value['tpl_integration_type'],
                        'tracking_number' => $value['tracking_number'],

                    ]);
                }
            });
        } else {
            $response->throw();
        }
    }
}