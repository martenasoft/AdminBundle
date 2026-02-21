<?php

namespace MartenaSoft\AdminBundle\Dto;

class CountsOnMainDto
{
    public function __construct(
        private int $pages,
        private int $users,
        private int $roles,
        private int $permissions,
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
