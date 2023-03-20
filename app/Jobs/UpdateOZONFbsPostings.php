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

class UpdateOZONFbsPostings implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $limit = 1000;
    private int $offset = 1000;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

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
                    'since' => Carbon::create(2023, 1, 1, 0, 0, 0, "Europe/Moscow")->toIso8601ZuluString(),
                    'to' => Carbon::today("Europe/Moscow")->toIso8601ZuluString(),
                    'status' => '',
                    'warehouse_id' => [],
                ],
                'limit' => $this->limit,
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

    public function transaction($result)
    {
        DB::transaction(function () use ($result) {

            foreach ($result as $value) {
                foreach ($value['financial_data']['products'] as $financialDataProduct) {
                    foreach ($value['products'] as $product) {

                        DB::table('ozon_fbs_postings')->updateOrInsert([
                            'order_id' => $value['order_id'],
                            'posting_number' => $value['posting_number'],
                            'sku' => $product['sku'],
                        ], [
                                'addressee' => json_encode($value['addressee'] ?? null, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                                'city' => $value['analytics_data']['city'] ?? null,
                                'delivery_date_begin' => Carbon::parse($value['analytics_data']['delivery_date_begin'] ?? null),
                                'delivery_date_end' => Carbon::parse($value['analytics_data']['delivery_date_end'] ?? null),
                                'delivery_type' => $value['analytics_data']['delivery_type'] ?? null,
                                'is_legal' => $value['analytics_data']['is_legal'] ?? null,
                                'is_premium' => $value['analytics_data']['is_premium'] ?? null,
                                'payment_type_group_name' => $value['analytics_data']['payment_type_group_name'] ?? null,
                                'region' => $value['analytics_data']['region'] ?? null,
                                'barcodes' => json_encode($value['barcodes'] ?? null, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                                'affect_cancellation_rating' => $value['cancellation']['affect_cancellation_rating'] ?? null,
                                'cancel_reason_id' => $value['cancellation']['cancel_reason_id'] ?? null,
                                'cancel_reason' => $value['cancellation']['cancel_reason'] ?? null,
                                'cancellation_initiator' => $value['cancellation']['cancellation_initiator'] ?? null,
                                'cancellation_type' => $value['cancellation']['cancellation_type'] ?? null,
                                'cancelled_after_ship' => $value['cancellation']['cancelled_after_ship'] ?? null,
                                'customer' => json_encode($value['customer'] ?? null, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                                'delivering_date' => Carbon::parse($value['delivering_date']) ?? null,
                                'delivery_method_id' => $value['delivery_method']['id'] ?? null,
                                'delivery_method_name' => $value['delivery_method']['name'] ?? null,
                                'tpl_provider' => $value['delivery_method']['tpl_provider'] ?? null,
                                'tpl_provider_id' => $value['delivery_method']['tpl_provider_id'] ?? null,
                                'warehouse' => $value['delivery_method']['warehouse'] ?? null,
                                'warehouse_id' => $value['delivery_method']['warehouse_id'] ?? null,
                                'cluster_from' => $value['financial_data']['cluster_from'] ?? null,
                                'cluster_to' => $value['financial_data']['cluster_to'] ?? null,
                                'marketplace_service_item_deliv_to_customer' => $value['financial_data']['posting_services']['marketplace_service_item_deliv_to_customer'] ?? null,
                                'marketplace_service_item_direct_flow_trans' => $value['financial_data']['posting_services']['marketplace_service_item_direct_flow_trans'] ?? null,
                                'marketplace_service_item_dropoff_ff' => $value['financial_data']['posting_services']['marketplace_service_item_dropoff_ff'] ?? null,
                                'marketplace_service_item_dropoff_pvz' => $value['financial_data']['posting_services']['marketplace_service_item_dropoff_pvz'] ?? null,
                                'marketplace_service_item_dropoff_sc' => $value['financial_data']['posting_services']['marketplace_service_item_dropoff_sc'] ?? null,
                                'marketplace_service_item_fulfillment' => $value['financial_data']['posting_services']['marketplace_service_item_fulfillment'] ?? null,
                                'marketplace_service_item_pickup' => $value['financial_data']['posting_services']['marketplace_service_item_pickup'] ?? null,
                                'marketplace_service_item_return_after_deliv_to_customer' => $value['financial_data']['posting_services']['marketplace_service_item_return_after_deliv_to_customer'] ?? null,
                                'marketplace_service_item_return_flow_trans' => $value['financial_data']['posting_services']['marketplace_service_item_return_flow_trans'] ?? null,
                                'marketplace_service_item_return_not_deliv_to_customer' => $value['financial_data']['posting_services']['marketplace_service_item_return_not_deliv_to_customer'] ?? null,
                                'marketplace_service_item_return_part_goods_customer' => $value['financial_data']['posting_services']['marketplace_service_item_return_part_goods_customer'] ?? null,
                                'available_actions' => json_encode($financialDataProduct['available_actions'] ?? null, JSON_UNESCAPED_SLASHES |
                                    JSON_UNESCAPED_UNICODE),
                                'actions' => json_encode($financialDataProduct['actions'] ?? null, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                                'client_price' => $financialDataProduct['client_price'] ?? null,
                                'commission_amount' => $financialDataProduct['commission_amount'] ?? null,
                                'commission_percent' => $financialDataProduct['commission_percent'] ?? null,
                                'old_price' => $financialDataProduct['old_price'] ?? null,
                                'payout' => $financialDataProduct['payout'] ?? null,
                                'picking' => json_encode($financialDataProduct['picking'] ?? null, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                                'product_id' => $financialDataProduct['product_id'] ?? null,
                                'total_discount_percent' => $financialDataProduct['total_discount_percent'] ?? null,
                                'total_discount_value' => $financialDataProduct['total_discount_value'] ?? null,
                                'in_process_at' => Carbon::parse($value['in_process_at'] ?? null),
                                'is_express' => $value['is_express'] ?? null,
                                'is_multibox' => $value['is_multibox'] ?? null,
                                'multi_box_qty' => $value['multi_box_qty'] ?? null,
                                'order_number' => $value['order_number'] ?? null,
                                'parent_posting_number' => $value['parent_posting_number'] ?? null,
                                'mandatory_mark' => json_encode($product['mandatory_mark'] ?? null, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                                'name' => $product['name'] ?? null,
                                'offer_id' => $product['offer_id'] ?? null,
                                'currency_code' => $product['currency_code'] ?? null,
                                'price' => $product['price'] ?? null,
                                'quantity' => $product['quantity'] ?? null,
                                'products_requiring_gtd' => json_encode($value['requirements']['products_requiring_gtd'] ?? null, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                                'products_requiring_country' => json_encode($value['requirements']['products_requiring_country'] ?? null, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                                'products_requiring_mandatory_mark' => json_encode($value['requirements']['products_requiring_mandatory_mark'] ?? null, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                                'products_requiring_rnpt' => json_encode($value['requirements']['products_requiring_rnpt'] ?? null, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                                'shipment_date' => Carbon::parse($value['shipment_date'] ?? null),
                                'status' => $value['status'] ?? null,
                                'tpl_integration_type' => $value['tpl_integration_type'] ?? null,
                                'tracking_number' => $value['tracking_number'] ?? null,
                                'created_at' => Carbon::today('Europe/Moscow'),
                                'updated_at' => Carbon::today('Europe/Moscow'),
                            ]);
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

            $result = $response->json()['result']['postings'];

            $this->transaction($result);

            while ($response->json()['result']['has_next']) {

                $this->transaction($result);

                $response = $this->makeRequest($this->offset);

                $result = $response->json()['result']['postings'];

                $this->offset += $this->limit;
            }


        } else {
            $response->throw();
        }
    }
}