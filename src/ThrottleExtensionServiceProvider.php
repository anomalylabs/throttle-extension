<?php namespace Anomaly\ThrottleExtension;

use Anomaly\Streams\Platform\Addon\AddonServiceProvider;
use Anomaly\ThrottleExtension\Http\Middleware\ThrottleRequest;

/**
 * Class ThrottleExtensionServiceProvider
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class ThrottleExtensionServiceProvider extends AddonServiceProvider
{

    /**
     * The addon middleware.
     *
     * @type array|null
     */
    protected $middleware = [
        ThrottleRequest::class,
    ];
}
