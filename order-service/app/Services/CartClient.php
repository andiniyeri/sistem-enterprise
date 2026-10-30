<?php

namespace App\Services;

use App\Exceptions\ExternalServiceException;
use Illuminate\Support\Facades\Http;
use Throwable;

class CartClient
{
    public function getCart(int $userId): array
    {
        try {
            $response = Http::baseUrl(
                rtrim(config('services.cart.base_url'), '/')
            )
                ->timeout(config('services.cart.timeout', 5))
                ->get("/carts/{$userId}");
        } catch (Throwable $exception) {
            throw new ExternalServiceException(
                'Cart Service tidak dapat dihubungi.',
                0,
                $exception
            );
        }

        if (!$response->successful()) {
            throw new ExternalServiceException(
                'Cart Service mengembalikan respons yang gagal.'
            );
        }

        $cart = $response->json('data');

        if (
            !is_array($cart) ||
            !isset($cart['items']) ||
            !is_array($cart['items'])
        ) {
            throw new ExternalServiceException(
                'Format respons Cart Service tidak valid.'
            );
        }

        return $cart;
    }

    public function clearCart(int $userId): bool
    {
        try {
            $response = Http::baseUrl(
                rtrim(config('services.cart.base_url'), '/')
            )
                ->timeout(config('services.cart.timeout', 5))
                ->delete("/carts/{$userId}");
        } catch (Throwable $exception) {
            throw new ExternalServiceException(
                'Gagal menghubungi Cart Service untuk mengosongkan keranjang.',
                0,
                $exception
            );
        }

        if (!$response->successful()) {
            throw new ExternalServiceException(
                'Cart Service gagal mengosongkan keranjang.'
            );
        }

        return true;
    }
}
