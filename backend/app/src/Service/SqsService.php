<?php
namespace App\Service;

use Aws\Sqs\SqsClient;

class SqsService
{
    private SqsClient $client;
    private string $queueUrl;

    public function __construct()
    {
        $this->client = new SqsClient([
            'version' => 'latest',
            'region' => $_ENV['AWS_DEFAULT_REGION'] ?? 'ap-southeast-2',
            'credentials' => [
                'key' => $_ENV['AWS_ACCESS_KEY_ID'] ?? '',
                'secret' => $_ENV['AWS_SECRET_ACCESS_KEY'] ?? '',
            ],
        ]);

        $this->queueUrl = $_ENV['AWS_SQS_QUEUE_URL'] ?? '';
    }

    public function sendMessage(array $payload): void
    {
        $this->client->sendMessage([
            'QueueUrl' => $this->queueUrl,
            'MessageBody' => json_encode($payload),
        ]);
    }
}
