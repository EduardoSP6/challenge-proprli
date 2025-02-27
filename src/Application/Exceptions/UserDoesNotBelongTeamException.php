<?php

namespace Application\Exceptions;

use DomainException;

class UserDoesNotBelongTeamException extends DomainException
{
    protected $message = "The assigned user does not belong to this team";
}
