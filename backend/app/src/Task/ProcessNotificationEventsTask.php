<?php

namespace App\Task;

use App\Model\NotificationEvent;
use SilverStripe\Dev\BuildTask;
use SilverStripe\PolyExecution\PolyOutput;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;

class ProcessNotificationEventsTask extends BuildTask
{
    private static string $segment = 'process-notification-events';

    protected string $title = 'Process Notification Events';

    protected static string $description = 'Processes pending notification events.';

    protected function execute(InputInterface $input, PolyOutput $output): int
    {
        $events = NotificationEvent::get()
            ->filter('Status', 'Pending')
            ->limit(10);

        foreach ($events as $event) {
            $event->Status = 'Processing';
            $event->Attempts = $event->Attempts + 1;
            $event->write();

            $payload = json_decode($event->Payload, true);

            $output->writeln("Processing event #{$event->ID}");
            $output->writeln("Request ID: {$payload['requestId']}");

            $event->Status = 'Processed';
            $event->LastError = null;
            $event->write();
        }

        $output->writeln('Done.');

        return Command::SUCCESS;
    }
}
