<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class AvailableJobsNotification extends Notification
{
    use Queueable;

    protected $availableJobs;
    protected $location;

    public function __construct($availableJobs, $location)
    {
        $this->availableJobs = $availableJobs;
        $this->location = $location;
    }

    public function via($notifiable)
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        $jobCount = count($this->availableJobs);
        $location = $this->location;

        return (new MailMessage)
            ->subject("New Jobs Available in {$location}")
            ->greeting("Hey {$notifiable->first_name}!")
            ->line("There are {$jobCount} available jobs in {$location}.")
            ->line("Would you like to accept them?")
            ->action('View Available Jobs', url('/loadboard-agent'))
            ->line('Check your dashboard for more details.');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "There are " . count($this->availableJobs) . " available jobs in {$this->location}",
            'jobs' => $this->availableJobs,
            'location' => $this->location
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => "There are " . count($this->availableJobs) . " available jobs in {$this->location}",
            'jobs' => $this->availableJobs,
            'location' => $this->location
        ]);
    }
} 