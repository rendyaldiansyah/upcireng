<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $broadcastQueue = 'default';

    /**
     * Create a new event instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('orders'),
        ];
    }

    /**
     * Get the name of the transmitted event.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'OrderCreated';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'id'         => $this->order->id,
            'reference'  => $this->order->reference,
            'customer_name'   => $this->order->customer_name,
            'customer_phone'  => $this->order->customer_phone,
            'total_price'     => $this->order->total_price,
            'payment_method'  => $this->order->payment_method,
            'status'     => $this->order->status,
            'created_at' => $this->order->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
