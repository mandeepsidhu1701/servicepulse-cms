<?php

namespace App\Admin;

use App\Model\ServiceRequest;
use SilverStripe\Admin\ModelAdmin;

class ServiceRequestAdmin extends ModelAdmin
{
    private static array $managed_models = [
        ServiceRequest::class,
    ];

    private static string $url_segment = 'service-requests';

    private static string $menu_title = 'Service Requests';
}
