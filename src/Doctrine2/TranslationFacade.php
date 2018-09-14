<?php

namespace Griston\Doctrine2\Translator;

use Griston\Translator\Language;
use Doctrine\ORM\Query\Expr;
use Nette\Utils\ArrayHash;

/**
 * Description of TranslationFacade
 *
 * @author Skala
 */
class TranslationFacade extends \App\Model\Facade\Facade
{

    /** @var TranslationService */
    private $translationService;

    /** @var string */
    private $tableMutation;

    /** @var string */
    private $tableLanguage;

    public function __construct(\Kdyby\Doctrine\EntityManager $entityManager)
    {
        parent::__construct($entityManager);

        $this->translation = new TranslationService;
        $this->table = Translation::class;
        $this->tableMutation = TranslationMutation::class;
        $this->tableLanguage = Language::class;
    }

    /**
     *
     * @param type $id
     * @return Translation
     */
    public function get($id = null): Translation
    {
        if (!$id) {
            return new Translation;
        }

        return parent::get($id);
    }

    public function getTranslationsByHash($hashes)
    {
        $translations = $this->entityManager->getRepository($this->table)->findTranslationsByHash($hashes);
        return $translations;
    }

    public function addNewTranslation($original)
    {
        if ($original) {
            $translation = $this->get();
            $translation->createNewTranslation($original);
            $this->entityManager->persist($translation);
            $this->entityManager->flush();
            return $translation;
        }
        return false;
    }

    /* MUTATION */

    /**
     * @return \Kdyby\Doctrine\QueryBuilder
     * @throws \NotSetTableInFacade
     */
    public function getModelMutation()
    {
        $joinTables = [
            'language' => ArrayHash::from([
                'table' => $this->tableLanguage,
                'conditionType' => Expr\Join::WITH,
                'condition' => sprintf('%s.language = %s.id', 'mutation', 'language')
            ]),
            'translation' => ArrayHash::from([
                'table' => $this->table,
                'conditionType' => Expr\Join::WITH,
                'condition' => sprintf('%s.translation = %s.id', 'mutation', 'translation')
            ])
        ];

        return $this->getModelWithJoinTable($this->tableMutation, 'mutation',$joinTables);
    }

    /**
     * @param null $id
     * @return TranslationMutation
     */
    public function getMutation($id = null): TranslationMutation
    {
        if (!$id)
            return new TranslationMutation;

        return $this->entityManager
            ->getRepository($this->tableMutation)
            ->find($id);
    }

    /**
     * @param $languageId
     * @return mixed
     */
    public function getMutationsByLanguage($languageId)
    {
        $mutations = $this->entityManager->getRepository($this->tableMutation)->findMutationsByLanguage($languageId);
        return $mutations;
    }

    /**
     * @param $id
     * @param $singular
     * @param $plural1
     * @param $plural2
     * @return TranslationMutation
     * @throws \Exception
     */
    public function updateMutation($id, $singular, $plural1, $plural2): TranslationMutation
    {
        $mutation = $this->getMutation($id);
        $mutation->updateMutation($singular, $plural1, $plural2);

        $this->entityManager->persist($mutation);
        $this->entityManager->flush();

        return $mutation;
    }

    /**
     * @param $id
     * @param $singular
     * @return TranslationMutation
     * @throws \Exception
     */
    public function updateSingular($id, $singular): TranslationMutation
    {
        $mutation = $this->getMutation($id);
        $mutation->updateSingular($singular);

        $this->entityManager->persist($mutation);
        $this->entityManager->flush();

        return $mutation;
    }

    /**
     * @param $id
     * @param $plural1
     * @return TranslationMutation
     * @throws \Exception
     */
    public function updatePlural1($id, $plural1): TranslationMutation
    {
        $mutation = $this->getMutation($id);
        $mutation->updatePlural1($plural1);

        $this->entityManager->persist($mutation);
        $this->entityManager->flush();

        return $mutation;
    }

    /**
     * @param $id
     * @param $plural2
     * @return TranslationMutation
     * @throws \Exception
     */
    public function updatePlural2($id, $plural2): TranslationMutation
    {
        $mutation = $this->getMutation($id);
        $mutation->updatePlural2($plural2);

        $this->entityManager->persist($mutation);
        $this->entityManager->flush();

        return $mutation;
    }

}
