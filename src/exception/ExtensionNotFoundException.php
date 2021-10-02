<?php
namespace thinkTideways\tideways\exception;

use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;
use Throwable;

class ExtensionNotFoundException extends RuntimeException implements NotFoundExceptionInterface
{
    protected $extension;

    public function __construct(string $message, string $extension = '', Throwable $previous = null)
    {
        $this->message = $message;
        $this->extension   = $extension;

        parent::__construct($message, 0, $previous);
    }

    /**
     * 获取扩展名
     * @access public
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }
}