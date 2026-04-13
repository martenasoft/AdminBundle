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
        '/admin/{_locale}',
        name: 'admin_main',
        requirements: ['_locale' => '[a-z]{2}'],
        methods: ['GET'])
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
