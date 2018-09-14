<?php

namespace Griston\Translator;

use App\Model\Translation\TranslationFacade;
use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use Nette\Http\Session;
use Nette\Localization\ITranslator;

/**
 * Class Translator
 * @package Griston\Translator
 * @author David Skála <skala2524@gmail.com>
 */
class Translator implements ITranslator
{
    use Nette\SmartObject;

    CONST TMP_LANG = [
        'cs' => 1
    ];

    /** @var boolean */
    private $isCacheInvalidated = false; // zatím není přidáno...

    /** @var TranslationFacade */
    private $facadeTranslation;

    /** @var Caching\Cache */
    private $cache;

    /** @var integer */
    private $languageId;

    /** @var array */
    private $traslations;

    /** @var Session */
    public $session;

    /**
     * @return int
     */
    public function getLanguageId()
    {
        return $this->languageId;
    }

    /**
     * Translator constructor.
     * @param TranslationFacade $facadeTranslation
     * @param IStorage $cacheStorage
     * @param Session $session
     */
    public function __construct(TranslationFacade $facadeTranslation, IStorage $cacheStorage, Session $session)
    {
        $this->facadeTranslation = $facadeTranslation;
        $this->cache = new Cache($cacheStorage, sprintf('Translator.Translation'));
        $this->session = $session;
    }

    /**
     * @param $languageId
     * @return Translator
     */
    public function setLanguage($languageId): Translator
    {
        $this->languageId = self::TMP_LANG[$languageId];
        $this->loadTranslations();
//$this->invalidateCache();
        return $this;
    }

    /**
     * @param $message
     * @param null $count
     * @return null|string
     */
    public function translate($message, $count = null)
    {
        if (is_null($this->traslations))
        {
            return null;
        }

        if ($message instanceof Translation)
        {
            return $message;
        }

        $_message = trim($message);
        if ($_message)
        {
            $hash = md5($_message);
            if (!isset($this->traslations[$hash]))
            {
                // \Tracy\Debugger::barDump(sprintf('translate:isNotIn:%s', $_message));
                try
                {
                    $this->facadeTranslation->addNewTranslation($_message);
                } catch (\Exception $ex)
                {
                    // překlad již existuje, ale není v cachi, kterou budeme stejně invalidovat...
                }
                $this->invalidateCache();
            }
            $_message = $this->getCorrectFormat($this->traslations[$hash], abs($count));
            $this->session->getSection('g-translator')->translations[$hash] = $_message;
        }

        return $_message;
    }

    /**
     * @param \App\Model\Translation\TranslationMutation $mutation
     * @param $count
     * @return string
     */
    private function getCorrectFormat(\App\Model\Translation\TranslationMutation $mutation, $count)
    {
        if (in_array($count, [
            null,
            1
        ]))
        {
            return $mutation->getSingular() ?: '';
        }
        elseif (in_array($count, [
            2,
            3,
            4
        ]))
        {
            return $mutation->getPlural1() ?: '';
        }
        else
        {
            return $mutation->getPlural2() ?: '';
        }
    }

    /**
     * @param null $languageId
     */
    public function invalidateCache($languageId = null)
    {
        $_languageId = is_null($languageId) ? $this->languageId : $languageId;
        $this->cache->remove($_languageId);

        if ($_languageId == $this->languageId)
        {
            $this->loadTranslations();
        }
    }

    /**
     *
     */
    private function loadTranslations()
    {
        $this->traslations = $this->cache->load($this->languageId, function ()
        {
            $mutations = array_reduce($this->facadeTranslation->getMutationsByLanguage($this->languageId), function ($result, \App\Model\Translation\TranslationMutation $mutation)
            {
                $result[$mutation->getTranslation()->getHash()] = $mutation;
                return $result;
            }, []);
            return $mutations;
        });
    }

    /**
     * @param string $text
     * @param array $params
     * @return Translation
     */
    public function getTranslation($text = '', array $params = [])
    {
        $translation = new Translation($this, $text, $params);
        return $translation;
    }

}
