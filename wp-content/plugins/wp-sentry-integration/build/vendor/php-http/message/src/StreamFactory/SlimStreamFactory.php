<?php

namespace WPSentry\ScopedVendor\Http\Message\StreamFactory;

use WPSentry\ScopedVendor\Http\Message\StreamFactory;
use WPSentry\ScopedVendor\Psr\Http\Message\StreamInterface;
use WPSentry\ScopedVendor\Slim\Http\Stream;
if (!\interface_exists(\WPSentry\ScopedVendor\Http\Message\StreamFactory::class)) {
    throw new \LogicException('You cannot use "Http\\Message\\MessageFactory\\SlimStreamFactory" as the "php-http/message-factory" package is not installed. Try running "composer require php-http/message-factory". Note that this package is deprecated, use "psr/http-factory" instead');
}
/**
 * Creates Slim 3 streams.
 *
 * @author Mika Tuupola <tuupola@appelsiini.net>
 *
 * @deprecated This will be removed in php-http/message2.0. Consider using the official Slim PSR-17 factory
 */
final class SlimStreamFactory implements \WPSentry\ScopedVendor\Http\Message\StreamFactory
{
    /**
     * {@inheritdoc}
     */
    public function createStream($body = null)
    {
        if ($body instanceof \WPSentry\ScopedVendor\Psr\Http\Message\StreamInterface) {
            return $body;
        }
        if (\is_resource($body)) {
            return new \WPSentry\ScopedVendor\Slim\Http\Stream($body);
        }
        $resource = \fopen('php://memory', 'r+');
        $stream = new \WPSentry\ScopedVendor\Slim\Http\Stream($resource);
        if (null !== $body && '' !== $body) {
            $stream->write((string) $body);
        }
        return $stream;
    }
}
