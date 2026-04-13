<?php

namespace MartenaSoft\AdminBundle\Dto;

class CountsOnMainDto
{
    public function __construct(
        private int $pages = 0,
        private int $pagesOnMain = 0,
        private int $pageSections = 0,
        private int $users = 0,
        private int $roles = 0,
        private int $permissions = 0,
        private int $pagesInBasket = 0,
        private int $usersInBasket = 0,
        private int $rolseInBasket = 0,
        private int $permissionsInBasket = 0,
    ) {
    }

    public function getPages(): int
    {
        return $this->pages;
    }

    public function getPagesOnMain(): int
    {
        return $this->pagesOnMain;
    }

    public function getPageSections(): int
    {
        return $this->pageSections;
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

    public function getPagesInBasket(): int
    {
        return $this->pagesInBasket;
    }

    public function setPagesInBasket(int $pagesInBasket): self
    {
        $this->pagesInBasket = $pagesInBasket;
        return $this;
    }

    public function getUsersInBasket(): int
    {
        return $this->usersInBasket;
    }

    public function setUsersInBasket(int $usersInBasket): self
    {
        $this->usersInBasket = $usersInBasket;
        return $this;
    }

    public function getRolseInBasket(): int
    {
        return $this->rolseInBasket;
    }

    public function setRolseInBasket(int $rolseInBasket): self
    {
        $this->rolseInBasket = $rolseInBasket;
        return $this;
    }

    public function getPermissionsInBasket(): int
    {
        return $this->permissionsInBasket;
    }

    public function setPermissionsInBasket(int $permissionsInBasket): self
    {
        $this->permissionsInBasket = $permissionsInBasket;
        return $this;
    }
}
