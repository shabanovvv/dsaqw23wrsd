<?php

namespace app\exceptions;

class EntityNotFoundException extends \DomainException
{
    public function __construct(string $message = 'Entity not found', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}