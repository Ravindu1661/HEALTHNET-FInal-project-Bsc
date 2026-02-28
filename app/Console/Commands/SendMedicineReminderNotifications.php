<?php

namespace App\Console\Commands;

use App\Models\MedicineReminder;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendMedicineReminderNotifications extends Command
{
    protected $signature   = 'reminders:send';
    protected $description = 'Exact alarm time එකට notification save කරනවා (every minute)';

    public function handle(): void
    {
        $now    = Carbon::now('Asia/Colombo');
        $nowStr = $now->format('H:i');   // "04:34"
        $today  = $now->toDateString();  // "2026-03-01"

        // Active reminders — date range valid
        $reminders = MedicineReminder::with('patient')
            ->where('is_active', true)
            ->where('start_date', '<=', $today)
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', $today);
            })
            ->get();

        $count = 0;

        foreach ($reminders as $reminder) {

            // Current minute ≠ alarm time → skip
            if (!in_array($nowStr, $reminder->times ?? [])) {
                continue;
            }

            // Same reminder + same time + today → duplicate check
            $exists = Notification::where('notifiable_type', 'App\Models\User')
                ->where('notifiable_id', $reminder->patient->user_id)
                ->where('related_type', 'medicine_reminder')
                ->where('related_id', $reminder->id)
                ->whereDate('created_at', $today)
                ->where('title', 'like', "%{$nowStr}%")
                ->exists();

            if ($exists) {
                continue;
            }

            // ✅ Exact time — notification save
            Notification::create([
                'notifiable_type' => 'App\Models\User',
                'notifiable_id'   => $reminder->patient->user_id,
                'type'            => 'medicine_reminder',
                'title'           => "💊 Medicine Time — {$nowStr}",
                'message'         => "Time to take your {$reminder->medicine_name}" .
                                     ($reminder->dosage ? " ({$reminder->dosage})" : '') .
                                     ". Don't skip your dose!",
                'related_type'    => 'medicine_reminder',
                'related_id'      => $reminder->id,
                'is_read'         => false,
            ]);

            $count++;
        }

        $this->info("✅ [{$nowStr}] {$count} reminder notification(s) saved.");
    }
}
