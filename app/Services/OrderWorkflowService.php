<?php

namespace App\Services;

use App\Mail\AdminOrderNotificationMail;
use App\Mail\OrderCancelledMail;
use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderWorkflowService
{
    public function handleCreated(Order $order): void
    {
        $this->sendCustomerConfirmation($order);
        $this->sendAdminNotification($order);
        GoogleSheetsService::syncCreated($order);
        NotificationService::sendNewOrderAlert($order);
    }

    public function handleStatusChange(Order $order, ?string $previousStatus = null): void
    {
        GoogleSheetsService::syncStatusUpdate($order, $previousStatus);

        if ($previousStatus !== Order::STATUS_CANCELLED && $order->status === Order::STATUS_CANCELLED) {
            $this->sendCustomerCancellation($order);
            NotificationService::sendCancellationAlert($order);
        }
    }

    public function handleDeleted(Order $order): void
    {
        GoogleSheetsService::syncDeleted($order);
    }

    protected function sendCustomerConfirmation(Order $order): void
    {
        try {
            Mail::to($order->customer_email)->send(new OrderConfirmationMail($order));
        } catch (\Throwable $exception) {
            Log::warning('Failed to send customer confirmation email', [
                'order_id' => $order->id,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    protected function sendCustomerCancellation(Order $order): void
    {
        try {
            Mail::to($order->customer_email)->send(new OrderCancelledMail($order));
        } catch (\Throwable $exception) {
            Log::warning('Failed to send customer cancellation email', [
                'order_id' => $order->id,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    protected function sendAdminNotification(Order $order): void
    {
        $adminEmail = config('services.notifications.admin_email');

        if (!$adminEmail) {
            return;
        }

        try {
            Mail::to($adminEmail)->send(new AdminOrderNotificationMail($order));
        } catch (\Throwable $exception) {
            Log::warning('Failed to send admin order email', [
                'order_id' => $order->id,
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
