<?php

namespace App\Helper;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;

class SecurityHelper
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function getLoggedUser(): ?User {
        $user = $this->security->getUser();
        if($user instanceof User) {
            return $user;
        }
        return null;
    }

}
