<?php

namespace Application\Exceptions;

use DomainException;

class AddCommentNotPermittedException extends DomainException
{
    protected $message = "Only the task creator can add comments";
}
