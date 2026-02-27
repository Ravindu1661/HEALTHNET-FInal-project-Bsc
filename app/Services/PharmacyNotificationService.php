<?php

namespace App\Services;

use App\Models\PharmacyOrder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PharmacyNotificationService
{
    /**
     * Order confirm/verify වූ විට
     * → Patient: "Pay Now" prompt
     * → Pharmacy: confirmation echo
     */
    public static function orderVerified(PharmacyOrder $order): void
    {
        $total = number_format(
            ($order->total_amount ?? 0) + ($order->delivery_fee ?? 0), 2
        );

        // ── 1. PATIENT notification ──
        self::send(
            userId: $order->patient?->user_id,
            type: 'pharmacy_order',
            title: '💊 Order Verified — Payment Required',
            message: 'Your prescription order #' . $order->order_number .
                     ' has been verified by ' . ($order->pharmacy?->name ?? 'the pharmacy') .
                     '. Total amount: Rs. ' . $total .
                     '. Please login and complete the payment to proceed.',
            relatedId: $order->id
        );

        // ── 2. PHARMACY notification ──
        self::send(
            userId: $order->pharmacy?->user_id,
            type: 'pharmacy_order',
            title: '✅ Order #' . $order->order_number . ' Verified',
            message: 'You have successfully verified order #' . $order->order_number .
                     ' for patient ' . ($order->patient?->first_name ?? '') .
                     ' ' . ($order->patient?->last_name ?? '') .
                     '. Total set: Rs. ' . $total .
                     '. Awaiting patient payment.',
            relatedId: $order->id
        );
    }

    /**
     * Order status change notifications
     * processing / ready / dispatched / delivered / cancelled
     */
    public static function statusChanged(PharmacyOrder $order, string $status): void
    {
        $map = [
            'processing' => [
                'patient_title'   => '🔄 Order Being Processed',
                'patient_message' => 'Your order #%s is now being prepared by %s.',
                'pharmacy_title'  => '🔄 Marked as Processing',
                'pharmacy_message'=> 'Order #%s has been marked as processing.',
            ],
            'ready' => [
                'patient_title'   => '📦 Order Ready',
                'patient_message' => 'Your order #%s is packed and ready. ' .
                                     ($order->delivery_address === 'PICKUP' ? 'Please collect from the pharmacy.' : 'Will be dispatched soon.'),
                'pharmacy_title'  => '📦 Marked as Ready',
                'pharmacy_message'=> 'Order #%s has been marked as ready.',
            ],
            'dispatched' => [
                'patient_title'   => '🚚 Order Dispatched!',
                'patient_message' => 'Your order #%s has been dispatched by %s.' .
                                     ($order->tracking_number ? ' Tracking: ' . $order->tracking_number : ''),
                'pharmacy_title'  => '🚚 Order Dispatched',
                'pharmacy_message'=> 'Order #%s has been dispatched.' .
                                     ($order->tracking_number ? ' Tracking: ' . $order->tracking_number : ''),
            ],
            'delivered' => [
                'patient_title'   => '✅ Order Delivered Successfully!',
                'patient_message' => 'Your order #%s from %s has been delivered. Thank you!',
                'pharmacy_title'  => '✅ Order Delivered',
                'pharmacy_message'=> 'Order #%s has been marked as delivered.',
            ],
            'cancelled' => [
                'patient_title'   => '❌ Order Cancelled',
                'patient_message' => 'Your order #%s has been cancelled by %s.' .
                                     ($order->cancelled_reason ? ' Reason: ' . $order->cancelled_reason : ''),
                'pharmacy_title'  => '❌ Order Cancelled',
                'pharmacy_message'=> 'Order #%s has been cancelled.' .
                                     ($order->cancelled_reason ? ' Reason: ' . $order->cancelled_reason : ''),
            ],
        ];

        if (!isset($map[$status])) return;

        $m            = $map[$status];
        $orderNum     = $order->order_number;
        $pharmacyName = $order->pharmacy?->name ?? 'the pharmacy';

        // ── Patient ──
        self::send(
            userId: $order->patient?->user_id,
            type: 'pharmacy_order',
            title: $m['patient_title'],
            message: sprintf($m['patient_message'], $orderNum, $pharmacyName),
            relatedId: $order->id
        );

        // ── Pharmacy ──
        self::send(
            userId: $order->pharmacy?->user_id,
            type: 'pharmacy_order',
            title: $m['pharmacy_title'],
            message: sprintf($m['pharmacy_message'], $orderNum, $pharmacyName),
            relatedId: $order->id
        );
    }

    /**
     * New order submitted — Pharmacy ට notify
     */
    public static function newOrderSubmitted(PharmacyOrder $order): void
    {
        $patientName = ($order->patient?->first_name ?? '') . ' ' . ($order->patient?->last_name ?? 'Patient');

        // ── Pharmacy: new order arrived ──
        self::send(
            userId: $order->pharmacy?->user_id,
            type: 'pharmacy_order',
            title: '🆕 New Prescription Order #' . $order->order_number,
            message: 'New prescription order received from ' . trim($patientName) .
                     '. Delivery type: ' . ($order->delivery_address === 'PICKUP' ? 'Pickup' : 'Home Delivery') .
                     '. Please review the prescription and verify the order.',
            relatedId: $order->id
        );

        // ── Patient: submission confirmed ──
        self::send(
            userId: $order->patient?->user_id,
            type: 'pharmacy_order',
            title: '📋 Prescription Submitted Successfully',
            message: 'Your prescription has been submitted to ' . ($order->pharmacy?->name ?? 'the pharmacy') .
                     ' (Order #' . $order->order_number . '). ' .
                     'The pharmacy will review and verify your order shortly.',
            relatedId: $order->id
        );
    }

    /**
     * Payment received — both parties notify
     */
    public static function paymentReceived(PharmacyOrder $order): void
    {
        $total = number_format(
            ($order->total_amount ?? 0) + ($order->delivery_fee ?? 0), 2
        );

        // ── Patient ──
        self::send(
            userId: $order->patient?->user_id,
            type: 'payment',
            title: '💳 Payment Successful',
            message: 'Payment of Rs. ' . $total . ' confirmed for order #' .
                     $order->order_number . ' from ' .
                     ($order->pharmacy?->name ?? 'pharmacy') . '. Your order is now being processed.',
            relatedId: $order->id
        );

        // ── Pharmacy ──
        self::send(
            userId: $order->pharmacy?->user_id,
            type: 'payment',
            title: '💰 Payment Received — Order #' . $order->order_number,
            message: 'Online payment of Rs. ' . $total . ' received via Stripe for order #' .
                     $order->order_number . ' from patient ' .
                     ($order->patient?->first_name ?? '') . ' ' .
                     ($order->patient?->last_name ?? '') . '.',
            relatedId: $order->id
        );
    }

    // ──────────────────────────────────────────
    // CORE SEND — notifications table insert
    // ──────────────────────────────────────────
    private static function send(
        ?int $userId,
        string $type,
        string $title,
        string $message,
        int $relatedId
    ): void {
        if (!$userId) return;

        try {
            DB::table('notifications')->insert([
                'notifiable_type' => User::class,   // App\Models\User
                'notifiable_id'   => $userId,        // users.id
                'type'            => $type,           // pharmacy_order / payment
                'title'           => $title,
                'message'         => $message,
                'related_type'    => 'prescriptionorder',  // migration comment: laborder/prescriptionorder
                'related_id'      => $relatedId,     // prescription_orders.id
                'is_read'         => false,
                'read_at'         => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        } catch (\Exception $e) {
            Log::warning('PharmacyNotification send failed: ' . $e->getMessage());
        }
    }
}
