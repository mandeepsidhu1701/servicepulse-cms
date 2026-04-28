<?php

namespace App\Model;

use SilverStripe\ORM\DataObject;

class ServiceRequest extends DataObject
{
    private static string $table_name = 'ServiceRequest';

    private static array $db = [
        'CustomerName' => 'Varchar(150)',
        'CustomerEmail' => 'Varchar(150)',
        'SiteName' => 'Varchar(150)',
        'Category' => 'Varchar(100)',
        'Priority' => 'Enum("Low,Medium,High,Urgent", "Medium")',
        'Status' => 'Enum("New,Assigned,InProgress,Resolved,Closed", "New")',
        'Description' => 'Text',
    ];

    private static array $summary_fields = [
        'CustomerName',
        'SiteName',
        'Category',
        'Priority',
        'Status',
        'Created',
    ];
}
