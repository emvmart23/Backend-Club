<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessOrderJob;
use App\Models\Box;
use App\Models\MethodPayment;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected $orderService;

    public function  __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            '*.hostess_id' => 'required|integer',
            '*.product_id' => 'required|integer',
            '*.price' => 'required|numeric|between:0,999999.99',
            '*.count' => 'required|integer',
            '*.total_price' => 'required|numeric|between:0,999999.99',
            '*.order_id' => 'sometimes|integer',
            "*.box_date" => 'sometimes|string',
            "*.current_user" => 'sometimes|integer'
        ]);
        Log::info(["order validate",$validatedData]);
        $user = Auth::user();
        
        ProcessOrderJob::dispatch($validatedData, $user->id);

        return response()->json(['message' => 'received order'], 200);
    }

    public function show()
    {
        $ordersData = $this->orderService;
        $methodPayments = MethodPayment::all();
        $latestBoxId = Box::max('id');
        $lastBox = Box::where('id', $latestBoxId)->first();
        $boxDate = $lastBox->opening;

        $orders = Order::with('header', 'product')->first()
            ->whereHas('header', function ($query) use ($boxDate) {
                $query->where('box_date', $boxDate);
            })
            ->get()
            ->map(function ($order) use ($methodPayments, $boxDate, $ordersData) {
                $payments = Payment::where('detail_id', $order->header->note_sale)->get();
                $mozo = User::where('id', $order->header->mozo_id)->first();
                $paymentSummary = array_fill_keys($methodPayments->pluck('name')->map(function ($name) use ($ordersData) {
                    return $ordersData->removeTildes($name);
                })->toArray(), 0);

                foreach ($payments as $payment) {
                    $paymentMethod = $methodPayments->where('id', $payment->payment_id)->first();
                    if ($paymentMethod) {
                        $methodName = $this->removeTildes($paymentMethod->name);
                        $paymentSummary[$methodName] += $payment->mountain;
                    }
                }

                return [
                    'order' => [
                        'id' => $order->id,
                        'hostess_id' => $order->hostess_id,
                        'product_id' => $order->product_id,
                        'product_name' => $order->product->name,
                        'has_alcohol' => $order->product->has_alcohol,
                        'price' => $order->price,
                        'count' => $order->count,
                        'total_price' => $order->total_price,
                        'order_id' => $order->order_id,
                        'current_user' => $order->current_user,
                        'box_date' => $boxDate,
                        'header_id' => $order->header->id,
                        'mozo_id' => $order->header->mozo_id,
                        'mozo' => $mozo->name,
                        'state_doc' => $order->header->state_doc,
                        'detail_id' => $order->header->note_sale,
                    ],
                    'payment_summary' => $paymentSummary
                ];
            });

        $processOrders = function ($orders) use ($methodPayments) {
            return $orders->groupBy('order.mozo')
                ->map(function ($group) use ($methodPayments) {
                    $paymentSummary = array_fill_keys($methodPayments->pluck('name')->map(function ($name) {
                        return $this->removeTildes($name);
                    })->toArray(), 0);
                    foreach ($group as $order) {
                        foreach ($order['payment_summary'] as $method => $amount) {
                            $paymentSummary[$method] += $amount;
                        }
                    }
                    $firstOrder = $group->first()['order'] ?? [];

                    $result = [
                        'mozo' => $firstOrder['mozo'] ?? null,
                        'box_date' => $firstOrder['box_date'] ?? null
                    ];

                    foreach ($methodPayments as $paymentMethod) {
                        $methodName = $this->removeTildes($paymentMethod->name);
                        $result[$methodName] = $paymentSummary[$methodName];
                    }

                    return $result;
                })
                ->values()
                ->toArray();
        };

        $alcoholOrders = $processOrders($orders->where('order.has_alcohol', 1)->groupBy('order.detail_id')->map(function ($group) {
            assert($group instanceof \Illuminate\Support\Collection);
            return $group->first();
        })->values());

        $nonAlcoholOrders = $processOrders($orders->where('order.has_alcohol', 0)->groupBy('order.detail_id')->map(function ($group) {
            assert($group instanceof \Illuminate\Support\Collection);
            return $group->first();
        })->values());

        return response()->json([
            'alcoholOrders' => $alcoholOrders,
            'nonAlcoholOrders' => $nonAlcoholOrders
        ]);
    }
}
