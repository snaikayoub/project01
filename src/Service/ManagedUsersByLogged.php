<?php

namespace App\Service;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;


class ManagedUsersByLogged
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUsers($LoggedUser)
    {
        $users = [];
        $fullProperties = $this->userRepository->findOneBy(['email'=> $LoggedUser]);
        $groupesGeres = $fullProperties->getGroupesGeres();
        foreach ($groupesGeres as $key => $groupesGere) {
            $users = array_merge($this->userRepository->findBy(['groupe'=>$groupesGere]),$users);
        }
        return $users;
    }

   
}