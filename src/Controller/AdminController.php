<?php

namespace MartenaSoft\AdminBundle\Controller;

use MartenaSoft\AdminBundle\Manager\TemplateManager;
use MartenaSoft\CommonLibrary\Entity\Interfaces\AdminEntityInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractAdminBaseController
{
    public function __construct(
        private TemplateManager $templateManager,
        private LoggerInterface $logger
    ) {
    }

    protected function beforeAction(string $name, Request $request): void
    {

    }

    #[Route(
        '/admin/{_locale}/{resource}/create',
        name: 'admin_template_create',
        requirements: ['_locale' => '[a-z]{2}'],
        defaults: ['_locale' => 'en'],
        methods: ['GET', 'POST'])
    ]
    public function create(Request $request)
    {
        $this->beforeAction('create', $request);
        $activeSite = $request->attributes->get('active_site');
        return $this->save($request, $this->templateManager, $this->logger, $activeSite);
    }

    protected function getEntity(?array $params = null): AdminEntityInterface
    {
        // TODO: Implement getEntity() method.
    }

    protected function getForm(AdminEntityInterface $entity): FormInterface
    {
        // TODO: Implement getForm() method.
    }


}