<?php

namespace MartenaSoft\AdminBundle\Manager;

use MartenaSoft\AdminBundle\Entity\Template;
use MartenaSoft\CommonLibrary\Dictionary\DictionaryMessage;
use MartenaSoft\CommonLibrary\Dto\ActiveSiteDto;
use MartenaSoft\CommonLibrary\Entity\Interfaces\AdminEntityInterface;
use MartenaSoft\CommonLibrary\Helper\StringHelper;
use MartenaSoft\CommonLibrary\Manager\AdminManagerInterface;;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class TemplateManager implements AdminManagerInterface
{
    public function __construct(private TranslatorInterface $translator)
    {

    }

    public function getTemplateFile(
        string $path,
        ?string $fileName = null,
        ?string $uuid = null
    ): ?Template {

        if (empty($path) || !file_exists($path)) {
            return null;
        }
        $entity = null;
        if (!empty($fileName) || !empty($uuid)) {
            foreach (scandir($path) as $file) {
                if (!empty($fileName)) {
                    $pattern = '/^(' . $fileName. ')___(.*)\.html\.twig$/';
                } else {
                    $pattern = '/^(.*)___('.$uuid.')\.html\.twig$/';

                }

                if (preg_match($pattern, $file, $matches) && isset($matches[1]) && isset($matches[2])) {
                    $entity = new Template();
                    $entity
                        ->setName($matches[1])
                        ->setFileName($matches[0])
                        ->setUuid(StringHelper::toUuid($matches[2]))
                        ->setHtml(file_get_contents(StringHelper::pathCleaner($path . '/' . $matches[0])))
                    ;
                    return $entity;
                }
            }
        }
        return $entity;
    }
    public function save(AdminEntityInterface $entity, ActiveSiteDto $activeSiteDto): void
    {
        $path = $this->getTemplateDirectoryPath('/custom-templates', $activeSiteDto, 'directory');
        $entity_ = $this->getTemplateFile($path, fileName: $entity->getName()) ?? $entity;

        if (empty($entity_->getUuid())) {
            $entity_->setUuid(StringHelper::getRandomUuid());
            $fileName = join('___', [
                    StringHelper::slug($entity_->getName()),
                    $entity_->getUuid()->toString(),
                ]).'.html.twig';
            $entity_->setFileName($fileName);
        }

        $fullPath = StringHelper::pathCleaner($path . '/' . $entity_->getFileName());
        file_put_contents($fullPath, $entity_->getHtml());
    }

    /**
     * @throws \Exception
     */
    public function getTemplateDirectoryPath(string $path, ActiveSiteDto $activeSiteDto, string $resource = 'file'): string
    {
        $path = StringHelper::pathCleaner(
            sprintf(__DIR__.'/../templates/%s/%s', $activeSiteDto->templatePath, $path)
        );

        $message = null;

        if (!file_exists($path)) {
            $message = 'is not exists';
        } elseif (!is_writable($path)) {
            $message = 'is not writable';
        } elseif (!is_readable($path)) {
            $message = 'is not readable';
        }

        if (!empty($message)) {
            $message = $this->translator->trans(
                sprintf(DictionaryMessage::RESOURCE_NOT_WRITABLE, $resource, $path, $message)
            );
            throw new \Exception($message);
        }

        $path = StringHelper::pathCleaner($path . '/' . $activeSiteDto->id);

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        return $path;
    }

    public function getMessages(): ?array
    {
        return DictionaryMessage::TEMPLATES;
    }


    public function getLoggerPrefix(): string
    {
        return 'TemplateManager';
    }
}