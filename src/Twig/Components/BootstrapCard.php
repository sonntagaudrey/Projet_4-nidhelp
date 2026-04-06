<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class BootstrapCard
{

    private string $_strName;
    private string $_strImg;
    private string $_strDescription;
    private array $_arrActions;

    /**
     * Monte le composant dans le DOM
     * 
     * @param string $title Titre de la card
     * @param string $img l'URL ou l'URI de la source de l'image
     * @param string $exercpt l'extrait de la card
     * @param string $link l'URL ou l'URI du target du lien du bouton
     * @param string $label Texte affiché dans le bouton
     */
    public function mount(string $name, string $img= "", string $description, array $actions = []): void
    {
        $this->_strName    = $name;
        $this->_strImg      = $img;
        $this->_strDescription  = $description;
        $this->_arrActions  = $actions;
    }

    // GETTERS 

    public function getName(): string
    {
        return $this->_strName;
    }

    public function getImg(): string
    {
        return $this->_strImg;
    }
    public function getDescription(): string
    {
        return $this->_strDescription;
    }

    public function getActions(): array
    {
        return $this->_arrActions;
    }
}
