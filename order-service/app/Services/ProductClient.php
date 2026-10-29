<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;
use Throwable;

class ProductClient
{
    public function getProduct(int $productId): array
    {
        try {
            $response = Http::baseUrl(
                rtrim(config('services.product.base_url'), '/')
            )
                ->timeout(config('services.product.timeout', 5))
                ->get("/products/{$productId}");
        } catch (Throwable $exception) {
            throw new RuntimeException(
                'Product Service tidak dapat dihubungi.',
                0,
                $exception
            );
        }

        if ($response->status() === 404) {
            throw new RuntimeException('Produk tidak ditemukan.');
        }

        if (!$response->successful()) {
            throw new RuntimeException(
                'Product Service mengembalikan respons yang gagal.'
            );
        }

        $product = $response->json('data');

        if (
            !is_array($product) ||
            !isset(
                $product['id'],
                $product['name'],
                $product['price'],
                $product['stock']
            )
        ) {
            throw new RuntimeException(
                'Format respons Product Service tidak valid.'
            );
        }

        return $product;
    }
}
