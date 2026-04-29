<?php

namespace App\Model;

use SilverStripe\ORM\DataObject;

class NotificationEvent extends DataObject
{
    private static string $table_name = 'NotificationEvent';

    private static array $db = [
        'EventType' => 'Varchar(100)',
        'Payload' => 'Text',
        'Status' => 'Enum("Pending,Processing,Processed,Failed", "Pending")',
        'Attempts' => 'Int',
        'LastError' => 'Text',
    ];

    private static array $summary_fields = [
        'EventType',
        'Status',
        'Attempts',
        'Created',
    ];
}
