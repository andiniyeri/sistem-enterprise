<?php

namespace App\Http\Controllers;

use App\Exceptions\ExternalServiceException;
use App\Exceptions\OrderConflictException;
use App\Http\Requests\StoreOrderRequest;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Throwable;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService
    ) {
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $validated = $request->validated();

        try {
            $order = $this->orderService->createOrder(
                (int) $validated['user_id']
            );

            return response()->json([
                'message' => 'Pesanan berhasil dibuat.',
                'data' => $order,
            ], 201);

        } catch (OrderConflictException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 409);

        } catch (ExternalServiceException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 503);

        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'message' => 'Terjadi kesalahan pada server.',
            ], 500);
        }
    }
}
