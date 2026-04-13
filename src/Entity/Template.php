<?php

namespace MartenaSoft\AdminBundle\Entity;

use MartenaSoft\CommonLibrary\Entity\Interfaces\AdminEntityInterface;
use MartenaSoft\CommonLibrary\Entity\Traits\UuidTrait;

class Template implements AdminEntityInterface
{
    use UuidTrait;
    private string $name;
    private string $fileName;

    private string $html;


    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getHtml(): string
    {
        return $this->html;
    }

    public function setHtml(string $html): self
    {
        $this->html = $html;
        return $this;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;
        return $this;
    }
}
