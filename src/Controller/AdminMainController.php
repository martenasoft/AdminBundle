<?php

namespace MartenaSoft\AdminBundle\Controller;

use MartenaSoft\AdminBundle\Services\CountsOnMainService;
use MartenaSoft\CommonLibrary\Dto\ActiveSiteDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminMainController extends AbstractController
{
    #[Route(
        '/{_locale}/admin',
        name: 'admin_main',
        requirements: ['_locale' => '[a-z]{2}'],
        defaults: ['_locale' => 'en'],
        methods: ['GET'],
        priority: 50)
    ]
    public function index(
        Request $request,
        CountsOnMainService $countsOnMainService
    ): Response {
        /** @var ActiveSiteDto $activeSite */
        $activeSite = $request->attributes->get('active_site');

        return $this->render(sprintf('@Admin/%s/index.html.twig', $activeSite->templatePath), [
            'counts' => $countsOnMainService->get(
                $activeSite,
                $request->getLocale(),
            )
        ]);
    }
}
