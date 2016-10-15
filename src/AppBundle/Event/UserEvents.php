<?php

namespace AppBundle\Event;

use AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class UserEvents extends Event
{
    const NEW_USER_CREATED = 'new_user_created';
    const NEW_USER_ENABLED = 'new_user_enabled';
}
