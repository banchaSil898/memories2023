<?php

declare (strict_types=1);
namespace Sentry\Integration;

use WPSentry\ScopedVendor\Http\Discovery\Psr17Factory;
use WPSentry\ScopedVendor\Psr\Http\Message\ServerRequestInterface;
/**
 * Default implementation for RequestFetcherInterface. Creates a request object
 * from the PHP superglobals.
 */
final class RequestFetcher implements \Sentry\Integration\RequestFetcherInterface
{
    /**
     * {@inheritdoc}
     */
    public function fetchRequest() : ?\WPSentry\ScopedVendor\Psr\Http\Message\ServerRequestInterface
    {
        if (!isset($_SERVER['REQUEST_METHOD']) || \PHP_SAPI === 'cli') {
            return null;
        }
        return (new \WPSentry\ScopedVendor\Http\Discovery\Psr17Factory())->createServerRequestFromGlobals();
    }
}
