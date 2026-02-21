<?php

namespace MartenaSoft\AdminBundle\Services;

use MartenaSoft\AdminBundle\Dto\CountsOnMainDto;
use MartenaSoft\CommonLibrary\Dto\ActiveSiteDto;
use MartenaSoft\SdkBundle\Service\Interfaces\PageSdkInterface;
use MartenaSoft\SdkBundle\Service\Interfaces\RoleSdkInterface;
use MartenaSoft\SdkBundle\Service\Interfaces\UserSdkInterface;


class CountsOnMainService
{
    public function __construct(
        private PageSdkInterface $pageSdk,
        private UserSdkInterface $userSdk,
        private RoleSdkInterface $roleSdk,
    ) {
    }

    public function get(ActiveSiteDto $activeSiteDto, string $lang): CountsOnMainDto
    {
        $filter = [
            'siteId' => $activeSiteDto->id
        ];

        return new CountsOnMainDto(
            pages: $this->pageSdk->getCount(array_merge($filter, ['lang' => $lang])),
            users: $this->userSdk->getCount($filter),
            roles: $this->roleSdk->getCount($filter),
//            permissions: $this->permissionRepository->count($criteria),
        );
    }
}
