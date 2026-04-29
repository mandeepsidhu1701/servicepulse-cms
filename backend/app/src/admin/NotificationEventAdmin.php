<?php

namespace App\Admin;

use App\Model\NotificationEvent;
use SilverStripe\Admin\ModelAdmin;

class NotificationEventAdmin extends ModelAdmin
{
    private static array $managed_models = [
        NotificationEvent::class,
    ];

    private static string $url_segment = 'notification-events';

    private static string $menu_title = 'Notification Events';
}
