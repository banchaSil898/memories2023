<?php

namespace WPSentry\ScopedVendor\Http\Message\Decorator;

use WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface;
use WPSentry\ScopedVendor\Psr\Http\Message\UriInterface;
/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
trait RequestDecorator
{
    use MessageDecorator {
        getMessage as getRequest;
    }
    /**
     * Exchanges the underlying request with another.
     */
    public function withRequest(\WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface $request) : \WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface
    {
        $new = clone $this;
        $new->message = $request;
        return $new;
    }
    public function getRequestTarget() : string
    {
        return $this->message->getRequestTarget();
    }
    public function withRequestTarget(string $requestTarget) : \WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface
    {
        $new = clone $this;
        $new->message = $this->message->withRequestTarget($requestTarget);
        return $new;
    }
    public function getMethod() : string
    {
        return $this->message->getMethod();
    }
    public function withMethod(string $method) : \WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface
    {
        $new = clone $this;
        $new->message = $this->message->withMethod($method);
        return $new;
    }
    public function getUri() : \WPSentry\ScopedVendor\Psr\Http\Message\UriInterface
    {
        return $this->message->getUri();
    }
    public function withUri(\WPSentry\ScopedVendor\Psr\Http\Message\UriInterface $uri, bool $preserveHost = \false) : \WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface
    {
        $new = clone $this;
        $new->message = $this->message->withUri($uri, $preserveHost);
        return $new;
    }
}
