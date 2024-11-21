<?php

namespace App\Jobs;

use App\Models\Box;
use App\Models\Header;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    protected $orderData;
    protected $userId;

    public function __construct(array $orderData, int $userId)
    {
        $this->orderData = $orderData;
        $this->userId = $userId;
    }

    public function handle(): void
    {
        try {
            DB::transaction(function () {
                $latestBox = Box::latest()->first();

                if (!$latestBox) {
                    Log::error("Box not found when processing order for user {$this->userId}");
                    return;
                }

                $latestOrder = Header::create([
                    "mozo_id" => $this->userId,
                    "current_user" => $this->userId,
                    "box_date" => $latestBox->opening
                ]);


                $orders = collect($this->orderData)->map(function ($data) use ($latestOrder, $latestBox) {
                    $data['header_id'] = $latestOrder->id;
                    $data['box_date'] = $latestBox->opening;
                    $data['current_user'] = $this->userId;

                    return Order::create($data);
                });

                Log::info("Processed orders: ", $orders->toArray());
            });
        } catch (\Exception $e) {
            Log::error("Error processing order for user {$this->userId}: " . $e->getMessage());
        }
    }
}
