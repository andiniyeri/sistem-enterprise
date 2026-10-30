<?php

namespace App\Services;

use App\Exceptions\ExternalServiceException;
use App\Exceptions\OrderConflictException;
use Illuminate\Support\Facades\Http;
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
            throw new ExternalServiceException(
                'Product Service tidak dapat dihubungi.',
                0,
                $exception
            );
        }

        if ($response->status() === 404) {
            throw new OrderConflictException(
                'Produk tidak ditemukan.'
            );
        }

        if (!$response->successful()) {
            throw new ExternalServiceException(
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
            throw new ExternalServiceException(
                'Format respons Product Service tidak valid.'
            );
        }

        return $product;
    }
}
