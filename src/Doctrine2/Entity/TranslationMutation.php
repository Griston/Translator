<?php

namespace Griston\Doctrine2\Entity\Translator;

use Griston\Translator\Language;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of user frontend
 *
 * @author David Skála <skala2524@gmail.com>
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="TranslationRepository")
 */
class TranslationMutation {

    use \Nette\SmartObject;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Griston\Translator\Language")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id", onDelete="cascade")
     */
    private $language;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $singular;

    /**
     * @ORM\Column(name="plural_1", type="string", nullable=true)
     */
    private $plural1;

    /**
     * @ORM\Column(name="plural_2", type="string", nullable=true)
     */
    private $plural2;

    /**
     * @ORM\ManyToOne(targetEntity="Translation", inversedBy="mutations")
     * @ORM\JoinColumn(name="translation_id", referencedColumnName="id", onDelete="cascade")
     */
    private $translation;

    function getId() {
        return $this->id;
    }

    function getLanguage(): Language {
        return $this->language;
    }

    function getSingular() {
        return $this->singular;
    }

    function getPlural1() {
        return $this->plural1;
    }

    function getPlural2() {
        return $this->plural2;
    }

    public function updateMutation($singular, $plural1, $plural2) {
        $this->updateSingular($singular);
        $this->updatePlural1($plural1);
        $this->updatePlural2($plural2);
    }

    public function updateSingular($singular) {
        $this->singular = $singular;
    }

    public function updatePlural1($plural1) {
        $this->plural1 = $plural1;
    }

    public function updatePlural2($plural2) {
        $this->plural2 = $plural2;
    }

    /**
     * 
     * @return \Griston\Translator\Translation
     */
    function getTranslation() {
        return $this->translation;
    }

}
