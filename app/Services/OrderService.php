<?php

namespace App\Services;

use App\Models\Box;
use App\Models\Header;
use App\Models\MethodPayment;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Routing\Controller;


class OrderService extends Controller
{
    public function removeTildes($string)
    {
        $unwanted_array = array(
            'Š' => 'S',
            'š' => 's',
            'Ž' => 'Z',
            'ž' => 'z',
            'À' => 'A',
            'Á' => 'A',
            'Â' => 'A',
            'Ã' => 'A',
            'Ä' => 'A',
            'Å' => 'A',
            'Æ' => 'A',
            'Ç' => 'C',
            'È' => 'E',
            'É' => 'E',
            'Ê' => 'E',
            'Ë' => 'E',
            'Ì' => 'I',
            'Í' => 'I',
            'Î' => 'I',
            'Ï' => 'I',
            'Ñ' => 'N',
            'Ò' => 'O',
            'Ó' => 'O',
            'Ô' => 'O',
            'Õ' => 'O',
            'Ö' => 'O',
            'Ø' => 'O',
            'Ù' => 'U',
            'Ú' => 'U',
            'Û' => 'U',
            'Ü' => 'U',
            'Ý' => 'Y',
            'Þ' => 'B',
            'ß' => 'Ss',
            'à' => 'a',
            'á' => 'a',
            'â' => 'a',
            'ã' => 'a',
            'ä' => 'a',
            'å' => 'a',
            'æ' => 'a',
            'ç' => 'c',
            'è' => 'e',
            'é' => 'e',
            'ê' => 'e',
            'ë' => 'e',
            'ì' => 'i',
            'í' => 'i',
            'î' => 'i',
            'ï' => 'i',
            'ð' => 'o',
            'ñ' => 'n',
            'ò' => 'o',
            'ó' => 'o',
            'ô' => 'o',
            'õ' => 'o',
            'ö' => 'o',
            'ø' => 'o',
            'ù' => 'u',
            'ú' => 'u',
            'û' => 'u',
            'ý' => 'y',
            'þ' => 'b',
            'ÿ' => 'y'
        );
        return strtr($string, $unwanted_array);
    }

    public function getOrdersData()
    {
        $methodPayments = MethodPayment::all();
        $methodId = $methodPayments->pluck('id')->toArray();

        $latestBoxId = Box::max('id');
        $lastBox = Box::where('id', $latestBoxId)->first();
        $boxDate = $lastBox->opening;

        $orders = Order::with('header', 'product')
            ->whereHas('header', function ($query) use ($boxDate) {
                $query->where('box_date', $boxDate);
            })
            ->get()
            ->map(function ($order) use ($methodPayments, $boxDate) {
                $payments = Payment::where('detail_id', $order->header->note_sale)->get();
                $mozo = User::where('id', $order->header->mozo_id)->first();
                $paymentSummary = array_fill_keys($methodPayments->pluck('name')->map(function ($name) {
                    return $this->removeTildes($name);
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

        $total = $orders->reduce(function ($carry, $order) {
            return $carry + array_sum($order['payment_summary']);
        }, 0);

        return response()->json($total);
    }
}
