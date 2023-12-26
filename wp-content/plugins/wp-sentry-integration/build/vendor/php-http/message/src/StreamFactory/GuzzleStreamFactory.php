<?php

namespace WPSentry\ScopedVendor\Http\Message\StreamFactory;

use WPSentry\ScopedVendor\GuzzleHttp\Psr7\Utils;
use WPSentry\ScopedVendor\Http\Message\StreamFactory;
if (!\interface_exists(\WPSentry\ScopedVendor\Http\Message\StreamFactory::class)) {
    throw new \LogicException('You cannot use "Http\\Message\\MessageFactory\\GuzzleStreamFactory" as the "php-http/message-factory" package is not installed. Try running "composer require php-http/message-factory". Note that this package is deprecated, use "psr/http-factory" instead');
}
/**
 * Creates Guzzle streams.
 *
 * @author Михаил Красильников <m.krasilnikov@yandex.ru>
 *
 * @deprecated This will be removed in php-http/message2.0. Consider using the official Guzzle PSR-17 factory
 */
final class GuzzleStreamFactory implements \WPSentry\ScopedVendor\Http\Message\StreamFactory
{
    /**
     * {@inheritdoc}
     */
    public function createStream($body = null)
    {
        if (\class_exists(\WPSentry\ScopedVendor\GuzzleHttp\Psr7\Utils::class)) {
            return \WPSentry\ScopedVendor\GuzzleHttp\Psr7\Utils::streamFor($body);
        }
        // legacy support for guzzle/psr7 1.*
        return \WPSentry\ScopedVendor\GuzzleHttp\Psr7\stream_for($body);
    }
}
