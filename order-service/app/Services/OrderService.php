<?php

namespace App\Services;

use App\Exceptions\OrderConflictException;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class OrderService
{
    public function __construct(
        private CartClient $cartClient,
        private ProductClient $productClient
    ) {
    }

    public function createOrder(int $userId): Order
    {
        $cart = $this->cartClient->getCart($userId);

        if (empty($cart['items'])) {
            throw new OrderConflictException(
                'Keranjang pengguna kosong.'
            );
        }

        $orderItems = [];
        $totalAmount = 0;

        foreach ($cart['items'] as $item) {
            $productId = (int) $item['product_id'];
            $quantity = (int) $item['quantity'];

            if ($quantity < 1) {
                throw new OrderConflictException(
                    'Jumlah produk harus minimal 1.'
                );
            }

            $product = $this->productClient->getProduct(
                $productId
            );

            if ($quantity > (int) $product['stock']) {
                throw new OrderConflictException(
                    "Stok produk {$productId} tidak mencukupi."
                );
            }

            $unitPrice = (float) $product['price'];
            $subtotal = $unitPrice * $quantity;

            $orderItems[] = [
                'product_id' => $productId,
                'product_name' => $product['name'],
                'unit_price' => $unitPrice,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ];

            $totalAmount += $subtotal;
        }

        $order = DB::transaction(function () use (
            $userId,
            $orderItems,
            $totalAmount
        ) {
            $order = Order::create([
                'user_id' => $userId,
                'status' => 'PENDING',
                'total_amount' => $totalAmount,
            ]);

            $order->items()->createMany($orderItems);

            return $order->load('items');
        });

        try {
            $this->cartClient->clearCart($userId);
        } catch (Throwable $exception) {
            Log::error(
                'Pesanan berhasil dibuat, tetapi keranjang gagal dikosongkan.',
                [
                    'order_id' => $order->id,
                    'user_id' => $userId,
                    'error' => $exception->getMessage(),
                ]
            );
        }

        return $order;
    }
}
