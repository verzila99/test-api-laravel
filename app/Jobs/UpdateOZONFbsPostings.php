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
                    'since' => Carbon::create(2022, 10, 1, 0, 0, 0, "Europe/Moscow")->toIso8601ZuluString(),
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

                $this->offset += $this->limit;
            }

            DB::transaction(function () use ($result) {

                foreach ($result as $value) {
                    foreach ($value['financial_data']['products'] as $financialDataProduct) {
                        foreach ($value['products'] as $product) {

                            DB::table('ozon_fbs_postings')->insertOrIgnore([
                                'addressee' => json_encode($value['addressee'] ?? null, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                                'city' => $value['analytics_data']['city'] ?? null,
                                'delivery_date_begin' => Carbon::parse($value['analytics_data']['delivery_date_begin']) ?? null,
                                'delivery_date_end' => Carbon::parse($value['analytics_data']['delivery_date_end']) ?? null,
                                'delivery_type' => $value['analytics_data']['delivery_type'] ?? null,
                                'is_legal' => $value['analytics_data']['is_legal'] ?? null,
                                'is_premium' => $value['analytics_data']['is_premium'] ?? null,
                                'payment_type_group_name' => $value['analytics_data']['payment_type_group_name'] ?? null,
                                'region' => $value['analytics_data']['region'] ?? null,
                                'tpl_provider' => $value['analytics_data']['tpl_provider'] ?? null,
                                'tpl_provider_id' => $value['analytics_data']['tpl_provider_id'] ?? null,
                                'warehouse' => $value['analytics_data']['warehouse'] ?? null,
                                'warehouse_id' => $value['analytics_data']['warehouse_id'] ?? null,
                                'barcodes' => json_encode($value['barcodes'] ?? null, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                                'affect_cancellation_rating' => $value['cancellation']['affect_cancellation_rating'] ?? null,
                                'cancel_reason_id' => $value['cancellation']['cancel_reason_id'] ?? null,
                                'cancellation_initiator' => $value['cancellation']['cancellation_initiator'] ?? null,
                                'cancellation_type' => $value['cancellation']['cancellation_type'] ?? null,
                                'cancelled_after_ship' => $value['cancellation']['cancelled_after_ship'] ?? null,
                                'customer' => json_encode($value['customer'] ?? null, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                                'delivering_date' => Carbon::parse($value['delivering_date']) ?? null,
                                'delivery_method_id' => $value['delivery_method']['id'] ?? null,
                                'delivery_method_name' => $value['delivery_method']['name'] ?? null,
                                'delivery_method_tpl_provider' => $value['delivery_method']['tpl_provider'] ?? null,
                                'delivery_method_tpl_provider_id' => $value['delivery_method']['tpl_provider_id'] ?? null,
                                'delivery_method_warehouse' => $value['delivery_method']['warehouse'] ?? null,
                                'delivery_method_warehouse_id' => $value['delivery_method']['warehouse_id'] ?? null,
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
                                'actions' => json_encode($financialDataProduct['actions'] ?? null, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                                'currency_code' => $financialDataProduct['currency_code'] ?? null,
                                'client_price' => $financialDataProduct['client_price'] ?? null,
                                'commission_amount' => $financialDataProduct['commission_amount'] ?? null,
                                'commission_percent' => $financialDataProduct['commission_percent'] ?? null,
                                'commissions_currency_code' => $financialDataProduct['commissions_currency_code'] ?? null,
                                'item_marketplace_service_item_deliv_to_customer' => $financialDataProduct['item_services']['item_marketplace_service_item_deliv_to_customer'] ?? null,
                                'item_marketplace_service_item_direct_flow_trans' => $financialDataProduct['item_services']['item_marketplace_service_item_direct_flow_trans'] ?? null,
                                'item_marketplace_service_item_dropoff_ff' => $financialDataProduct['item_services']['item_marketplace_service_item_dropoff_ff'] ?? null,
                                'item_marketplace_service_item_dropoff_pvz' => $financialDataProduct['item_services']['item_marketplace_service_item_dropoff_pvz'] ?? null,
                                'item_marketplace_service_item_dropoff_sc' => $financialDataProduct['item_services']['item_marketplace_service_item_dropoff_sc'] ?? null,
                                'item_marketplace_service_item_fulfillment' => $financialDataProduct['item_services']['item_marketplace_service_item_fulfillment'] ?? null,
                                'item_marketplace_service_item_pickup' => $financialDataProduct['item_services']['item_marketplace_service_item_pickup'] ?? null,
                                'item_marketplace_service_item_return_after_deliv_to_customer' => $financialDataProduct['item_services']['item_marketplace_service_item_return_after_deliv_to_customer'] ?? null,
                                'item_marketplace_service_item_return_flow_trans' => $financialDataProduct['item_services']['item_marketplace_service_item_return_flow_trans'] ?? null,
                                'item_marketplace_service_item_return_not_deliv_to_customer' => $financialDataProduct['item_services']['item_marketplace_service_item_return_not_deliv_to_customer'] ?? null,
                                'item_marketplace_service_item_return_part_goods_customer' => $financialDataProduct['item_services']['item_marketplace_service_item_return_part_goods_customer'] ?? null,
                                'old_price' => $financialDataProduct['old_price'] ?? null,
                                'payout' => $financialDataProduct['payout'] ?? null,
                                'picking' => json_encode($financialDataProduct['picking'] ?? null, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                                'financial_data_products_price' => $financialDataProduct['price'] ?? null,
                                'financial_data_products_product_id' => $financialDataProduct['product_id'] ?? null,
                                'financial_data_products_quantity' => $financialDataProduct['quantity'] ?? null,
                                'total_discount_percent' => $financialDataProduct['total_discount_percent'] ?? null,
                                'total_discount_value' => $financialDataProduct['total_discount_value'] ?? null,
                                'in_process_at' => Carbon::parse($value['in_process_at'] ?? null),
                                'is_express' => $value['is_express'] ?? null,
                                'is_multibox' => $value['is_multibox'] ?? null,
                                'multi_box_qty' => $value['multi_box_qty'] ?? null,
                                'order_id' => $value['order_id'],
                                'order_number' => $value['order_number'] ?? null,
                                'parent_posting_number' => $value['parent_posting_number'] ?? null,
                                'posting_number' => $value['posting_number'],
                                'mandatory_mark' => json_encode($product['mandatory_mark'] ?? null, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                                'products_name' => $product['name'] ?? null,
                                'products_offer_id' => $product['offer_id'] ?? null,
                                'products_currency_code' => $product['currency_code'] ?? null,
                                'products_price' => $product['price'] ?? null,
                                'products_quantity' => $product['quantity'] ?? null,
                                'products_sku' => $product['sku'],
                                'products_requiring_gtd' => json_encode($value['requirements']['products_requiring_gtd'] ?? null, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                                'products_requiring_country' => json_encode($value['requirements']['products_requiring_country'] ?? null, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                                'products_requiring_mandatory_mark' => json_encode($value['requirements']['products_requiring_mandatory_mark'] ?? null, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                                'products_requiring_rnpt' => json_encode($value['requirements']['products_requiring_rnpt'] ?? null, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                                'shipment_date' => Carbon::parse($value['shipment_date'] ?? null),
                                'status' => $value['status'] ?? null,
                                'tpl_integration_type' => $value['tpl_integration_type'] ?? null,
                                'tracking_number' => $value['tracking_number'] ?? null,

                            ]);
                        }
                    }
                }


            });
        } else {
            $response->throw();
        }
    }
}