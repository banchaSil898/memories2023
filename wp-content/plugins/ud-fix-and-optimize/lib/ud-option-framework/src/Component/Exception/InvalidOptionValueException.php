<?php

namespace UDFixAndOptimize\UDOptionFramework\Component\Exception;

if (! defined('ABSPATH')) {
    exit;
}

class InvalidOptionValueException extends \Exception
{
    /**
     * WordPress handles string error codes.
     * @var string
     */
    protected $code;
    /**
     * Error instance.
     * @var \WP_Error
     */
    protected $wp_error;

    /**
     * WordPress exception constructor.
     *
     * The class constructor accepts either the traditional `\Exception` creation
     * parameters or a `\WP_Error` instance in place of the previous exception.
     *
     * If a `\WP_Error` instance is given in this way, the `$message` and `$code`
     * parameters are ignored in favour of the message and code provided by the
     * `\WP_Error` instance.
     *
     * Depending on whether a `\WP_Error` instance was received, the instance is kept
     * or a new one is created from the provided parameters.
     *
     * @param string               $message  Exception message (optional, defaults to empty).
     * @param string               $code     Exception code (optional, defaults to empty).
     * @param \Exception|\WP_Error $previous Previous exception or error (optional).
     *
     * @uses \WP_Error
     * @uses \WP_Error::get_error_code()
     * @uses \WP_Error::get_error_message()
     *
     * @codeCoverageIgnore
     */
    public function __construct($message = '', $code = '', $previous = null)
    {
        $exception = $previous;
        $wp_error = null;
        if ($previous instanceof \WP_Error) {
            $code = $previous->get_error_code();
            $message = $previous->get_error_message($code);
            $wp_error = $previous;
            $exception = null;
        }
        parent::__construct($message, null, $exception);
        $this->code = $code;
        $this->wp_error = $wp_error;
    }

    /**
     * Obtain the exception's `\WP_Error` object.
     *
     * @return \WP_Error WordPress error.
     */
    public function getWPError()
    {
        return $this->wp_error ? $this->wp_error : new \WP_Error($this->code, $this->message, $this);
    }
}
