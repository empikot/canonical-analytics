<?php

namespace App\IO\Exceptions;

use Illuminate\Contracts\Support\MessageBag;

class ValidationException extends \Exception
{
    /**
     * @var array
     */
    private $messages;

    /**
     * ValidationException constructor.
     * @param MessageBag $messageBag
     */
    public function __construct(MessageBag $messageBag)
    {
        parent::__construct("Form couldn't be validated.", 422);
        $this->messages = $messageBag->toArray();
    }

    /**
     * @return array
     */
    public function getResponse()
    {
        return $this->messages;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return 422;
    }
}
