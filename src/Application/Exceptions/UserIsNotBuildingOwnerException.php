<?php

namespace Application\Exceptions;

use DomainException;

class UserIsNotBuildingOwnerException extends DomainException
{
    protected $message = "You are not the owner of this building";
}
