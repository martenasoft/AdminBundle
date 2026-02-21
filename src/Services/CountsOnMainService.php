<?php

namespace MartenaSoft\AdminBundle\Services;

use MartenaSoft\AdminBundle\Dto\CountsOnMainDto;
//use MartenaSoft\PageBundle\Repository\PageRepository;
use MartenaSoft\CommonLibrary\Dto\ActiveSiteDto;
//use MartenaSoft\UserBundle\Repository\PermissionRepository;
//use MartenaSoft\UserBundle\Repository\RoleRepository;
//use MartenaSoft\UserBundle\Repository\UserRepository;

class CountsOnMainService
{
    public function __construct(
//        private readonly PageRepository $pageRepository,
//        private readonly UserRepository $userRepository,
//        private readonly RoleRepository $roleRepository,
//        private readonly PermissionRepository $permissionRepository,
    ) {
    }

    public function get(ActiveSiteDto $activeSiteDto, string $lang): CountsOnMainDto
    {
        $criteria = ['siteId' => $activeSiteDto->id];
        $criteriaWithLang = [
            'siteId' => $activeSiteDto->id,
            'lang' => $lang,
        ];
        return new CountsOnMainDto(
//            pages: $this->pageRepository->count($criteriaWithLang),
//            users: $this->userRepository->count($criteria),
//            roles: $this->roleRepository->count($criteria),
//            permissions: $this->permissionRepository->count($criteria),
        );
    }
}
