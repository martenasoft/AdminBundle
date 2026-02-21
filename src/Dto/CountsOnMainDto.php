<?php

namespace MartenaSoft\AdminBundle\Dto;

class CountsOnMainDto
{
    public function __construct(
        private int $pages = 0,
        private int $users = 0,
        private int $roles = 0,
        private int $permissions = 0,
    ) {
    }

    public function getPages(): int
    {
        return $this->pages;
    }

    public function getUsers(): int
    {
        return $this->users;
    }

    public function getRoles(): int
    {
        return $this->roles;
    }

    public function getPermissions(): int
    {
        return $this->permissions;
    }
}
