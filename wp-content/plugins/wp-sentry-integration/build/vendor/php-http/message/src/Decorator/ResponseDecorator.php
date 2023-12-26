<?php

namespace WPSentry\ScopedVendor\Http\Message\Decorator;

use WPSentry\ScopedVendor\Psr\Http\Message\ResponseInterface;
/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
trait ResponseDecorator
{
    use MessageDecorator {
        getMessage as getResponse;
    }
    /**
     * Exchanges the underlying response with another.
     */
    public function withResponse(\WPSentry\ScopedVendor\Psr\Http\Message\ResponseInterface $response) : \WPSentry\ScopedVendor\Psr\Http\Message\ResponseInterface
    {
        $new = clone $this;
        $new->message = $response;
        return $new;
    }
    public function getStatusCode() : int
    {
        return $this->message->getStatusCode();
    }
    public function withStatus(int $code, string $reasonPhrase = '') : \WPSentry\ScopedVendor\Psr\Http\Message\ResponseInterface
    {
        $new = clone $this;
        $new->message = $this->message->withStatus($code, $reasonPhrase);
        return $new;
    }
    public function getReasonPhrase() : string
    {
        return $this->message->getReasonPhrase();
    }
}
