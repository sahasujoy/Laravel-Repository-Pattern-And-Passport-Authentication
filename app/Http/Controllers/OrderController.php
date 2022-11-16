<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Interfaces\OrderRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    private OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function index()
    {
        //
        return response()->json([
            'data' => $this->orderRepository->getAllOrders(),
        ]);
    }


    public function create()
    {
        //
    }

    public function store(Request $request): JsonResponse
    {
        $orderDetails = $request->only([
            'client',
            'details',
        ]);

        return response()->json(
            [
                'data' => $this->orderRepository->createOrder($orderDetails),
            ],
            Response::HTTP_CREATED
        );
    }

    public function show(Request $request): JsonResponse
    {
        $orderId = $request->route('id');

        return response()->json([
            'data' => $this->orderRepository->getOrderById($orderId),
        ]);
    }

    public function edit(Order $order)
    {
        //
    }

    public function update(Request $request)
    {
        $orderId = $request->route('id');

        $orderDetails = $request->only([
            'client',
            'details',
        ]);

        return response()->json([
            'data' => $this->orderRepository->updateOrder($orderId, $orderDetails),
        ]);
    }

    public function destroy(Request $request)
    {
        $orderId = $request->route('id');

        $this->orderRepository->deleteOrder($orderId);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
