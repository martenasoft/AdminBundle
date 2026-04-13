<?php

namespace MartenaSoft\AdminBundle\Services;

use MartenaSoft\AdminBundle\Dto\CountsOnMainDto;
use MartenaSoft\CommonLibrary\Dictionary\DictionaryPage;
use MartenaSoft\CommonLibrary\Dto\ActiveSiteDto;
use MartenaSoft\SdkBundle\Service\Interfaces\PageSdkInterface;
use MartenaSoft\SdkBundle\Service\Interfaces\PermissionSdkInterface;
use MartenaSoft\SdkBundle\Service\Interfaces\RoleSdkInterface;
use MartenaSoft\SdkBundle\Service\Interfaces\UserSdkInterface;


class CountsOnMainService
{
    public function __construct(
        private PageSdkInterface $pageSdk,
        private UserSdkInterface $userSdk,
        private RoleSdkInterface $roleSdk,
        private PermissionSdkInterface $permissionSdk,
    ) {
    }

    public function get(ActiveSiteDto $activeSiteDto, string $lang): CountsOnMainDto
    {
        $filterSiteId = [
            'siteId' => $activeSiteDto->id
        ];

        $filterLang = [
            'lang' => $lang
        ];

        $filterIsDeletedTrue = array_merge($filterSiteId, [
            'isDeleted' => true,
        ]);

        $filterIsDeletedFalse = array_merge($filterSiteId, [
            'isDeleted' => true,
        ]);

        $filterIsDeletedTrueLang = array_merge($filterSiteId, $filterLang, [
            'isDeleted' => true,
        ]);

        $filterIsDeletedFalseLang = array_merge($filterSiteId, $filterLang, [
            'isDeleted' => false,
        ]);


        return new CountsOnMainDto(
            pages: $this->pageSdk->getCount(array_merge($filterIsDeletedFalseLang, ['isOnMain' => false])),
            pagesOnMain: $this->pageSdk->getCount(array_merge($filterIsDeletedFalseLang, ['isOnMain' => true])),
            pageSections: $this->pageSdk->getCount(array_merge($filterIsDeletedFalseLang, ['type' => DictionaryPage::SECTION_TYPE])),
            users: $this->userSdk->getCount($filterIsDeletedFalse),
            roles: $this->roleSdk->getCount($filterIsDeletedFalse),
            permissions: $this->permissionSdk->getCount($filterIsDeletedFalse),
            pagesInBasket: $this->pageSdk->getCount($filterIsDeletedTrueLang),
            usersInBasket: $this->userSdk->getCount($filterIsDeletedTrue),
            rolseInBasket: $this->roleSdk->getCount($filterIsDeletedTrue),
            permissionsInBasket: $this->permissionSdk->getCount($filterIsDeletedTrue),

        );
    }
}
