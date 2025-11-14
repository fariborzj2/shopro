<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\ZibalService;
use App\Models\Setting;
use App\Models\Product;
use App\Models\Order;
use App\Models\Transaction;

class PaymentController
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Start the payment process.
     */
    public function startPayment()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'ابتدا باید وارد حساب کاربری خود شوید.']);
            return;
        }

        $data = Request::json();
        $product_id = $data['product_id'] ?? null;
        $custom_fields = $data['custom_fields'] ?? [];

        $product = Product::find($product_id);
        if (!$product) {
            http_response_code(404);
            echo json_encode(['error' => 'محصول یافت نشد.']);
            return;
        }

        // --- Create Order ---
        $order_id = Order::create([
            'user_id' => $_SESSION['user_id'],
            'product_id' => $product->id,
            'category_id' => $product->category_id,
            'amount' => $product->price,
            'status' => 'pending',
            'custom_fields_data' => json_encode($custom_fields),
            'order_code' => 'ORD-' . time() . rand(100, 999),
        ]);

        // --- Create Transaction Record ---
        Transaction::create([
            'order_id' => $order_id,
            'user_id' => $_SESSION['user_id'],
            'amount' => $product->price,
            'status' => 'pending',
        ]);

        $settings = Setting::getAll();
        $merchant_id = $settings['zibal_merchant_id'] ?? null;
        if (!$merchant_id) {
            http_response_code(500);
            echo json_encode(['error' => 'پیکربندی درگاه پرداخت ناقص است.']);
            return;
        }

        $zibal = new ZibalService($merchant_id);
        $callback_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/payment/callback";

        $result = $zibal->request($product->price * 10, $callback_url, $order_id, "خرید محصول " . $product->name_fa);

        if ($result['success']) {
            // Update transaction with track_id
            Transaction::updateByOrderId($order_id, ['track_id' => $result['track_id']]);
            echo json_encode(['payment_url' => $result['payment_url']]);
        } else {
            Order::update($order_id, ['status' => 'failed']);
            Transaction::updateByOrderId($order_id, ['status' => 'failed', 'gateway_response' => $result['message']]);
            http_response_code(500);
            echo json_encode(['error' => 'خطا در اتصال به درگاه پرداخت: ' . $result['message']]);
        }
    }

    /**
     * Verify the payment after returning from the gateway.
     */
    public function verifyPayment()
    {
        $track_id = $_GET['trackId'] ?? null;
        $success = $_GET['success'] ?? '0';

        if (!$track_id) {
            // Handle error: No trackId
            echo "خطا: کد رهگیری یافت نشد.";
            return;
        }

        $transaction = Transaction::findBy('track_id', $track_id);
        if (!$transaction) {
            echo "خطا: تراکنش یافت نشد.";
            return;
        }

        if ($success !== '1') {
            Order::update($transaction->order_id, ['status' => 'failed']);
            Transaction::update($transaction->id, ['status' => 'failed', 'gateway_response' => 'تراکنش توسط کاربر لغو شد.']);
            // Redirect to a failure page
            header('Location: /dashboard/orders/' . $transaction->order_id . '?status=cancelled');
            exit();
        }

        $settings = Setting::getAll();
        $merchant_id = $settings['zibal_merchant_id'] ?? null;

        $zibal = new ZibalService($merchant_id);
        $result = $zibal->verify($track_id);

        if ($result['success'] && $result['status'] == 1) {
            // Security Check: Verify amount paid matches the transaction amount
            $paid_amount = $result['response']['amount']; // Amount from gateway is in Rials
            $transaction_amount_rials = $transaction->amount * 10; // Amount in DB is in Tomans

            if ($paid_amount != $transaction_amount_rials) {
                // Amount mismatch - potential fraud
                Order::update($transaction->order_id, ['status' => 'failed']);
                $mismatch_response = "مغایرت در مبلغ پرداخت شده. مبلغ تراکنش: {$transaction_amount_rials} ریال، مبلغ پرداخت شده: {$paid_amount} ریال.";
                Transaction::update($transaction->id, ['status' => 'failed', 'gateway_response' => $mismatch_response]);
                header('Location: /dashboard/orders/' . $transaction->order_id . '?status=mismatch');
                exit();
            }

            // Payment successful and amount is correct
            Order::update($transaction->order_id, ['status' => 'paid']);
            Transaction::update($transaction->id, ['status' => 'successful', 'gateway_response' => json_encode($result['response'])]);
            // Redirect to a success/receipt page
            header('Location: /dashboard/orders/' . $transaction->order_id . '?status=success');
        } else {
            // Payment failed
            Order::update($transaction->order_id, ['status' => 'failed']);
            Transaction::update($transaction->id, ['status' => 'failed', 'gateway_response' => json_encode($result['response'])]);
            // Redirect to a failure page
            header('Location: /dashboard/orders/' . $transaction->order_id . '?status=failed');
        }
        exit();
    }
}
