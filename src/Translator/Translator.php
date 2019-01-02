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

    /** @var boolean */
    private $isCacheInvalidated = false; // zatím není přidáno...

    /** @var Caching\Cache */
    private $cache;

    /**
     * Translator constructor.
     * @param TranslationFacade $facadeTranslation
     * @param IStorage $cacheStorage
     * @param Session $session
     */
    public function __construct(TranslationFacade $facadeTranslation, IStorage $cacheStorage)
    {
        $this->cache = new Cache($cacheStorage, sprintf('Translator.Translation'));
    }

    /**
     * @param $message
     * @param null $count
     * @return null|string
     */
    public function translate($message, $count = null)
    {
        if ($message instanceof Translation)
        {
            return $message;
        }

        return 't_' . $_message;
    }
}
