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

class UpdateOZONFboPostings implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $limit;
    private int $offset;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->limit  = 1000;
        $this->offset = 1000;
    }

    public function makeRequest(int $offset = 0): \Illuminate\Http\Client\Response
    {
        $response = Http::retry(3, 100)->withHeaders(
            [
                'Client-Id' => env('OZON_CLIENT_ID'),
                'Api-Key' => env('OZON_API_KEY')
            ]
        )->post('https://api-seller.ozon.ru/v2/posting/fbo/list', [
                'dir' => 'desc',
                'filter' => [
                    'since' => Carbon::create(2023, 1, 1, 0, 0, 0, "Europe/Moscow")->toIso8601ZuluString(),
                    'to' => Carbon::today("Europe/Moscow")->toIso8601ZuluString(),
                    'status' => '',
                ],
                'limit' => $this->limit,
                "offset" => $offset,
                'with' => [
                    'analytics_data' => true,
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
    public function transaction($result)
    {
        DB::transaction(function () use ($result) {

            foreach ($result as $value) {

                if (empty($value['additional_data'])) {
                    $value['additional_data'] = [null];
                }
                foreach ($value['additional_data'] as $additionalData) {
                    if (empty($value['financial_data']['products'])) {
                        $value['financial_data']['products'] = [null];
                    }
                    foreach ($value['financial_data']['products'] as $financialDataProduct) {

                        if (empty($value['products'])) {
                            $value['products'] = [null];
                        }
                        foreach ($value['products'] as $product) {
                            if (empty($product['digital_codes'])) {
                                $product['digital_codes'] = [null];
                            }
                            foreach ($product['digital_codes'] as $digital_code) {

                                DB::table('ozon_fbo_postings')->updateOrInsert([
                                    'order_id' => $value['order_id'],
                                    'posting_number' => $value['posting_number'],
                                    'products_sku' => $product['sku'],
                                ], [
                                        'additional_data_key' => $additionalData['key'] ?? null,
                                        'additional_data_value' => $additionalData['value'] ?? null,
                                        'city' => $value['analytics_data']['city'] ?? null,
                                        'delivery_type' => $value['analytics_data']['delivery_type'] ?? null,
                                        'is_legal' => $value['analytics_data']['is_legal'] ?? null,
                                        'is_premium' => $value['analytics_data']['is_premium'] ?? null,
                                        'payment_type_group_name' => $value['analytics_data']['payment_type_group_name'] ?? null,
                                        'region' => $value['analytics_data']['region'] ?? null,
                                        'warehouse_id' => $value['analytics_data']['warehouse_id'] ?? null,
                                        'warehouse_name' => $value['analytics_data']['warehouse_name'] ?? null,
                                        'cancel_reason_id' => $value['cancel_reason_id'] ?? null,
                                        'created_at' => Carbon::parse($value['created_at'] ?? null),
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
                                        'cluster_from' => $value['financial_data']['cluster_from'] ?? null,
                                        'cluster_to' => $value['financial_data']['cluster_to'] ?? null,
                                        'actions' => json_encode($financialDataProduct['actions'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?? null,
                                        'currency_code' => $financialDataProduct['currency_code'] ?? null,
                                        'client_price' => $financialDataProduct['client_price'] ?? null,
                                        'commission_amount' => $financialDataProduct['commission_amount'] ?? null,
                                        'commission_percent' => $financialDataProduct['commission_percent'] ?? null,
                                        'commissions_currency_code' => $financialDataProduct['commissions_currency_code'] ?? null,
                                        'item_marketplace_service_item_deliv_to_customer' => $financialDataProduct['item_services']['marketplace_service_item_deliv_to_customer'] ?? null,
                                        'item_marketplace_service_item_direct_flow_trans' => $financialDataProduct['item_services']['marketplace_service_item_direct_flow_trans'] ?? null,
                                        'item_marketplace_service_item_dropoff_ff' => $financialDataProduct['item_services']['marketplace_service_item_dropoff_ff'] ?? null,
                                        'item_marketplace_service_item_dropoff_pvz' => $financialDataProduct['item_services']['marketplace_service_item_dropoff_pvz'] ?? null,
                                        'item_marketplace_service_item_dropoff_sc' => $financialDataProduct['item_services']['marketplace_service_item_dropoff_sc'] ?? null,
                                        'item_marketplace_service_item_fulfillment' => $financialDataProduct['item_services']['marketplace_service_item_fulfillment'] ?? null,
                                        'item_marketplace_service_item_pickup' => $financialDataProduct['item_services']['marketplace_service_item_pickup'] ?? null,
                                        'item_marketplace_service_item_return_after_deliv_to_customer' => $financialDataProduct['item_services']['marketplace_service_item_return_after_deliv_to_customer'] ?? null,
                                        'item_marketplace_service_item_return_flow_trans' => $financialDataProduct['item_services']['marketplace_service_item_return_flow_trans'] ?? null,
                                        'item_marketplace_service_item_return_not_deliv_to_customer' => $financialDataProduct['item_services']['marketplace_service_item_return_not_deliv_to_customer'] ?? null,
                                        'old_price' => $financialDataProduct['old_price'] ?? null,
                                        'payout' => $financialDataProduct['payout'] ?? null,
                                        'picking' => json_encode($financialDataProduct['picking'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?? null,
                                        'financial_data_products_price' => $financialDataProduct['price'] ?? null,
                                        'financial_data_products_product_id' => $financialDataProduct['product_id'] ?? null,
                                        'financial_data_products_quantity' => $financialDataProduct['quantity'] ?? null,
                                        'total_discount_percent' => $financialDataProduct['total_discount_percent'] ?? null,
                                        'total_discount_value' => $financialDataProduct['total_discount_value'] ?? null,
                                        'in_process_at' => Carbon::parse($value['in_process_at'] ?? null),
                                        'order_number' => $value['order_number'] ?? null,
                                        'products_digital_code' => $digital_code ?? null,
                                        'products_name' => $product['name'] ?? null,
                                        'products_offer_id' => $product['offer_id'] ?? null,
                                        'products_currency_code' => $product['currency_code'] ?? null,
                                        'products_price' => $product['price'] ?? null,
                                        'products_quantity' => $product['quantity'] ?? null,
                                        'status' => $value['status'] ?? null,
                                    ]);
                            }
                        }
                    }
                }
            }
        });
    }
    public function handle()
    {
        $response = $this->makeRequest();

        if ($response->successful()) {

            $result = $response->json()['result'];

            $this->transaction($result);

            while (count($response->json()['result']) >= $this->limit - 1) {

                $this->transaction($result);

                $response = $this->makeRequest($this->offset);

                $result = $response->json()['result'];

                $this->offset += $this->limit;
            }


        } else {
            $response->throw();
        }
    }
}