<?php

namespace Griston\Doctrine2\Entity\Translator;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of user frontend
 *
 * @author David Skála <skala2524@gmail.com>
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="TranslationRepository")
 */
class Translation {

    use \Nette\SmartObject;

    public function __construct() {
        $this->mutations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $original;

    /**
     * @ORM\OneToMany(targetEntity="TranslationMutation", mappedBy="translation")
     */
    private $mutations;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $hash = null;

    function getId() {
        return $this->id;
    }

    function getOriginal() {
        return $this->original;
    }

    function getMutations() {
        return $this->mutations;
    }

    function getHash() {
        return $this->hash;
    }

    public function createNewTranslation($original) {
        $this->original = $original;
        $this->hash = md5($this->original);
    }

}
