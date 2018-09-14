<?php

namespace Griston\Doctrine2\Translator;

/**
 * Description of TranslationRepository
 *
 * @author David Skála <skala2524@gmail.com>
 */
class TranslationRepository extends \Kdyby\Doctrine\EntityRepository {

    /** @var string */
    private $table;

    /** @var string */
    private $tableMutation;

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);

        $this->table = Translation::class;
        $this->tableMutation = TranslationMutation::class;
    }

    public function findMutationsByLanguage($languageId) {
        $criteria = [
            'language' => $languageId
        ];

        return $this->getEntityManager()
                        ->getRepository($this->tableMutation)
                        ->findBy($criteria);
    }

    public function findTranslationsByHash($hashes) {
        $criteria = [
            'hash' => $hashes
        ];

        return $this->getEntityManager()
                        ->getRepository($this->table)
                        ->findBy($criteria);
    }

}
