<?php namespace Anomaly\ThrottleExtension\Http\Middleware;

use Anomaly\ThrottleExtension\ThrottleExtension;
use Carbon\Carbon;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ThrottleRequest
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class ThrottleRequest
{

    /**
     * The cache repository.
     *
     * @var Cache
     */
    protected $cache;

    /**
     * The config repository.
     *
     * @var Config
     */
    protected $config;

    /**
     * The request object.
     *
     * @var Request
     */
    protected $request;

    /**
     * The response factory.
     *
     * @var ResponseFactory
     */
    protected $response;

    /**
     * The throttle extension.
     *
     * @var ThrottleExtension
     */
    protected $extension;

    /**
     * Create a new ThrottleRequest instance.
     *
     * @param Cache             $cache
     * @param Config            $config
     * @param Request           $request
     * @param ResponseFactory   $response
     * @param ThrottleExtension $extension
     */
    public function __construct(
        Cache $cache,
        Config $config,
        Request $request,
        ResponseFactory $response,
        ThrottleExtension $extension
    ) {
        $this->cache     = $cache;
        $this->config    = $config;
        $this->request   = $request;
        $this->response  = $response;
        $this->extension = $extension;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle(Request $request, \Closure $next)
    {
        $lockoutInterval  = $this->config->get($this->extension->getNamespace('throttle.lockout_interval'), 5);
        $maxAttempts      = $this->config->get($this->extension->getNamespace('throttle.max_attempts'), 50);
        $throttleInterval = $this->config->get($this->extension->getNamespace('throttle.interval'), 30);

        $attempts   = $this->cache->get($this->extension->getNamespace('attempts:' . $request->ip()), 1);
        $expiration = $this->cache->get($this->extension->getNamespace('expiration:' . $request->ip()));

        $headers = [
            'X-RateLimit-Limit'     => $maxAttempts,
            'X-RateLimit-Remaining' => $maxAttempts - $attempts,
        ];

        if ($expiration || $attempts >= $maxAttempts) {

            $this->cache->put(
                $this->extension->getNamespace('attempts:' . $request->ip()),
                $attempts + 1,
                $throttleInterval
            );

            $this->cache->put(
                $this->extension->getNamespace('expiration:' . $request->ip()),
                time(),
                $lockoutInterval
            );

            $headers['Retry-After'] = (new Carbon())
                ->addMinutes($lockoutInterval)
                ->toDateTimeString();

            return $this->response
                ->make(view('streams::errors/429', []), 429, $headers)
                ->setTtl($lockoutInterval * 1);
        }

        $this->cache->put(
            $this->extension->getNamespace('attempts:' . $request->ip()),
            $attempts + 1,
            $throttleInterval
        );

        /* @var Response $response */
        $response = $next($request);

        $response->headers->add($headers);

        return $response;
    }
}
