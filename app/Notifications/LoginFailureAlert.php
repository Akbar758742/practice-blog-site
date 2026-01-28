<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoginFailureAlert extends Notification
{
    use Queueable;

    protected string $loginId;
    protected string $ipAddress;
    protected int $failedAttempts;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $loginId, string $ipAddress, int $failedAttempts = 1)
    {
        $this->loginId = $loginId;
        $this->ipAddress = $ipAddress;
        $this->failedAttempts = $failedAttempts;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $severity = $this->failedAttempts >= 5 ? 'CRITICAL' : ($this->failedAttempts >= 3 ? 'WARNING' : 'INFO');

        return [
            'type' => 'login_failure',
            'icon' => 'dw dw-padlock1',
            'color' => $this->failedAttempts >= 5 ? 'danger' : ($this->failedAttempts >= 3 ? 'warning' : 'secondary'),
            'title' => 'Failed Login Attempt',
            'message' => "Failed login attempt for '{$this->loginId}' from IP {$this->ipAddress}. Total failed attempts: {$this->failedAttempts}",
            'login_id' => $this->loginId,
            'ip_address' => $this->ipAddress,
            'failed_attempts' => $this->failedAttempts,
            'url' => route('admin.activity-log'),
            'severity' => $severity,
        ];
    }
}
