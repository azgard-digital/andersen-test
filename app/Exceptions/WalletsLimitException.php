<?php
declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use App\Enums\HttpStatuses;
use Illuminate\Support\MessageBag;

class WalletsLimitException extends ResourceException
{
    /**
     * @inheritDoc
     */
    public function __construct($message = null, $errors = null, Exception $previous = null, $headers = [], $code = 0)
    {
        if (is_null($errors)) {
            $this->errors = new MessageBag;
        } else {
            $this->errors = is_array($errors) ? new MessageBag($errors) : $errors;
        }

        parent::__construct(HttpStatuses::value('forbidden'), $message, $previous, $headers, $code);
    }
}
