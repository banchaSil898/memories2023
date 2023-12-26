<?php

namespace WPSentry\ScopedVendor\Http\Discovery;

use WPSentry\ScopedVendor\Psr\Http\Client\ClientInterface;
use WPSentry\ScopedVendor\Psr\Http\Message\RequestFactoryInterface;
use WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface;
use WPSentry\ScopedVendor\Psr\Http\Message\ResponseFactoryInterface;
use WPSentry\ScopedVendor\Psr\Http\Message\ResponseInterface;
use WPSentry\ScopedVendor\Psr\Http\Message\ServerRequestFactoryInterface;
use WPSentry\ScopedVendor\Psr\Http\Message\StreamFactoryInterface;
use WPSentry\ScopedVendor\Psr\Http\Message\UploadedFileFactoryInterface;
use WPSentry\ScopedVendor\Psr\Http\Message\UriFactoryInterface;
/**
 * A generic PSR-18 and PSR-17 implementation.
 *
 * You can create this class with concrete client and factory instances
 * or let it use discovery to find suitable implementations as needed.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
class Psr18Client extends \WPSentry\ScopedVendor\Http\Discovery\Psr17Factory implements \WPSentry\ScopedVendor\Psr\Http\Client\ClientInterface
{
    private $client;
    public function __construct(\WPSentry\ScopedVendor\Psr\Http\Client\ClientInterface $client = null, \WPSentry\ScopedVendor\Psr\Http\Message\RequestFactoryInterface $requestFactory = null, \WPSentry\ScopedVendor\Psr\Http\Message\ResponseFactoryInterface $responseFactory = null, \WPSentry\ScopedVendor\Psr\Http\Message\ServerRequestFactoryInterface $serverRequestFactory = null, \WPSentry\ScopedVendor\Psr\Http\Message\StreamFactoryInterface $streamFactory = null, \WPSentry\ScopedVendor\Psr\Http\Message\UploadedFileFactoryInterface $uploadedFileFactory = null, \WPSentry\ScopedVendor\Psr\Http\Message\UriFactoryInterface $uriFactory = null)
    {
        parent::__construct($requestFactory, $responseFactory, $serverRequestFactory, $streamFactory, $uploadedFileFactory, $uriFactory);
        $this->client = $client ?? \WPSentry\ScopedVendor\Http\Discovery\Psr18ClientDiscovery::find();
    }
    public function sendRequest(\WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface $request) : \WPSentry\ScopedVendor\Psr\Http\Message\ResponseInterface
    {
        return $this->client->sendRequest($request);
    }
}
