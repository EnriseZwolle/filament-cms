<?php

namespace Enrisezwolle\FilamentCms\Exceptions;

use Exception;
use Throwable;

class SluggableInterfaceNotImplemented extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct("Class '{$message}' has not implemented the IsSluggable interface.", $code, $previous);
    }
}
