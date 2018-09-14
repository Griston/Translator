<?php

namespace Griston\Doctrine2\Entity\Translator;

use Doctrine\ORM\Mapping AS ORM;

/**
 * Description of user frontend
 *
 * @author David Skála <skala2524@gmail.com>
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Griston\Translator\TranslationRepository")
 */
class Language
{

    use \Nette\SmartObject;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $code;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="smallint", options={"default": 1})
     */
    private $isActive;

    function getId()
    {
        return $this->id;
    }

    function getCode()
    {
        return $this->code;
    }

    function getName()
    {
        return $this->name;
    }

    function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Vrátí jestli je stránka aktivní
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->isActive == 1;//\Constants::ACTIVE;
    }

    /**
     * @param $stateActive
     */
    public function switchActive($stateActive)
    {
        if (is_null($stateActive)) {
            $this->isActive = ($this->isActive + 1) % 2;
        } else {
            $this->isActive = $stateActive;
        }
    }

    /* EVENTY */
}
