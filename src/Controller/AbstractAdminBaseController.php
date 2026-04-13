<?php

namespace MartenaSoft\AdminBundle\Controller;

use MartenaSoft\AdminBundle\Dto\AdminDefinitionDto;
use MartenaSoft\CommonLibrary\Dictionary\DictionaryMessage;
use MartenaSoft\CommonLibrary\Dto\ActiveSiteDto;
use MartenaSoft\CommonLibrary\Entity\Interfaces\AdminEntityInterface;
use MartenaSoft\CommonLibrary\Helper\StringHelper;
use MartenaSoft\CommonLibrary\Manager\AdminManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

abstract class AbstractAdminBaseController extends AbstractController
{
    protected const string ADMIN_CREATE = 'create';
    protected const string ADMIN_UPDATE = 'update';
    protected const string ADMIN_VIEW = 'view';
    protected const string ADMIN_DELETE = 'delete';
    protected const string ADMIN_DELETE_SAFE = 'deleteSafe';

    protected const string ADMIN_TEMPLATES = 'templates';
    protected const string ADMIN_ROUTES = 'routes';

    protected string $successFlushKey = 'success';
    protected string $errorFlushKey = 'danger';
    protected function save(
        Request $request,
        AdminManagerInterface $adminManager,
        LoggerInterface $logger,
        ActiveSiteDto $activeSiteDto,
    ): Response {

        $entity = $this->getEntity();
        $form = $this->getForm($entity);
        $form->handleRequest($request);
        $routes = $this->initRoutes($entity, $activeSiteDto);
        $messages = $adminManager->getMessages();

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $adminManager->save($entity, $activeSiteDto);
                $this->addFlash(
                    $this->successFlushKey,
                    $this->getMessage(DictionaryMessage::CREATED_SUCCESSFUL_KEY, $messages)
                );

                if ($request->request->getBoolean('returnToNewFormAfterSave')) {
                    return $this->redirectToRoute($request->attributes->get('_route'));
                }

                return $this->redirectToRoute($routes[self::ADMIN_ROUTES][self::ADMIN_CREATE]);
            } catch (\Throwable $exception) {
                $logger->critical($adminManager->getLoggerPrefix() . ' {message}', [
                    'message' => $exception->getMessage(),
                    'trace' => $exception->getTrace(),
                    'line' => $exception->getLine(),
                    'file' => $exception->getFile(),
                ]);

                $this->addFlash(
                    $this->errorFlushKey,
                    $this->getMessage(DictionaryMessage::ERROR_CREATING_KEY, $messages)
                );
            }
        }

        $errors = '';
        foreach ($form->getErrors() as $error) {
            $errors .= '<li>' . $error->getOrigin()->getName() . ': ' . $error->getMessage() . '</li>';
        }

        if (!empty($errors)) {
            $this->addFlash($this->errorFlushMessage, '<ul>' . $errors . '</ul>');
        }

        return $this->render(
            $routes[self::ADMIN_TEMPLATES][self::ADMIN_CREATE], [
                'form' => $form->createView(),
                'entity' => $entity,
                'routes' => $routes
            ]
        );
    }


    protected function getMessage(string $messageKey, ?array $messages): string
    {
        return $messages[$messageKey] ?? DictionaryMessage::DEFAULTS[$messages];
    }

    abstract protected function getEntity(?array $params = null): AdminEntityInterface;
    abstract protected function getForm(AdminEntityInterface $entity): FormInterface;

    protected function initRoutes(
        AdminEntityInterface $entity,
        ActiveSiteDto $activeSiteDto
    ): array {
        $routeName = StringHelper::classNameToRoute(get_class($entity));

        $getTemplate = fn (string $name) =>
            sprintf('@Admin/%s/admin/%s/%s.html.twig',
                $activeSiteDto->templatePath,
                $routeName,
                $name)
        ;

        $getRoute = fn (string $name) => sprintf(
            'admin_%s_%s',
            StringHelper::classNameToRoute(get_class($entity), false),
            $name
        );

        return [
            self::ADMIN_TEMPLATES => [
                self::ADMIN_CREATE => $getTemplate('create'),
                self::ADMIN_UPDATE => $getTemplate('update'),
                self::ADMIN_VIEW => $getTemplate('view'),
            ],
            self::ADMIN_ROUTES => [
                self::ADMIN_CREATE => $getRoute('create'),
                self::ADMIN_UPDATE => $getRoute('update'),
                self::ADMIN_DELETE_SAFE => $getRoute('delete-safe'),
                self::ADMIN_DELETE => $getRoute('delete'),
                self::ADMIN_VIEW => $getRoute('view'),
            ]
        ];
    }

}
