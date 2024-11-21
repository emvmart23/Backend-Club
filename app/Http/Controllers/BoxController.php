<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\Box;
use App\Models\Header;
use App\Models\OtherExpense;
use Illuminate\Support\Facades\Auth;
use App\Services\OrderService;
use Illuminate\Support\Facades\Log;

class BoxController extends Controller
{
    protected $orderService;

    public function show()
    {
        $boxes = Box::all();

        return response()->json([
            "boxes" => $boxes,
            "total of hostes" => $this->finalBalance()
        ]);
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            "opening" => "required|date",
            "user_opening" => "required|string",
            "initial_balance" => "required|numeric|between:0,999999.99",
            "final_balance" => "sometimes|numeric|between:0,999999.99",
            "state" => "required|boolean"
        ]);

        $box = Box::create($data);

        return response()->json([
            "box" => $box
        ]);
    }

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function finalBalance()
    {
        $ordersData = $this->orderService->getOrdersData();

        $attendances = Attendance::with('user', 'box')->get()->map(function ($attendance) {
            return (object) [
                'id' => $attendance->id,
                'user_id' => $attendance->user_id,
                'present' => $attendance->present,
                'box_date' => $attendance->box_date,
                'role_user' => $attendance->user->role_id,
                'salary' => $attendance->user->salary,
                'profit_margin' => $attendance->user->profit_margin,
            ];
        });

        $headers = Header::with('orders')->get()->map(function ($header) {
            return (object) [
                'box_date' => $header->box_date,
                'state_doc' => $header->state_doc,
                'orders' => collect($header->orders)->map(function ($order) {
                    return (object) [
                        'hostess_id' => $order->hostess_id,
                        'price' => $order->price,
                    ];
                }),
            ];
        });

        $latestBox = Box::latest()->first();

        $presentUsers = $attendances->filter(function ($attendance) use ($latestBox) {
            return $attendance->present === 1 &&
                $attendance->box_date === $latestBox->opening &&
                ($attendance->role_user === 4 || $attendance->role_user === 8);
        });

        $result = $presentUsers->map(function ($user) use ($headers, $latestBox) {
            $sales = collect($headers)->filter(function ($header) use ($latestBox) {
                return $header->box_date === $latestBox->opening && $header->state_doc === 0;
            })->flatMap(function ($header) {
                return $header->orders ?? collect();
            });

            $totalSale = $this->currentSale($sales, $user->user_id);

            $comission = $totalSale * ($user->profit_margin / 100);

            return (object) [
                'total' => (float) $user->salary + $comission
            ];
        })->values();

        $totalSalary = $result->reduce(function ($acc, $curr) {
            return $acc + $curr->total;
        }, 0);

        $totalExpenses = $this->calculateTotalExpenses($latestBox->opening);

        $total =  $totalSalary + $totalExpenses - $ordersData->original;

        $total = $totalSalary + $totalExpenses - $ordersData->original;

        $total = abs($total);

        return $total;
    }

    private function currentSale($sales, $userId)
    {
        return $sales->reduce(function ($acc, $curr) use ($userId) {
            if ($curr->hostess_id === $userId) {
                return $acc + (float) $curr->price;
            }
            return $acc;
        }, 0);
    }

    private function calculateTotalExpenses($boxDate)
    {
        $expenses = OtherExpense::where('box_date', $boxDate)->get();
        return $expenses->sum('total');
    }

    public function close($id)
    {
        $user = Auth::user();
        $ldate = date('Y-m-d');
        $box = Box::find($id);

        $box->final_balance = $this->finalBalance();
        $box->closing = $ldate;
        $box->state = false;
        $box->user_closing = $user->user;
        $box->save();

        return response()->json([
            "box" => $box
        ]);
    }
}
