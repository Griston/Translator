<?php

namespace Griston\Translator;


/**
 * Class TranslationEntity
 * @package Griston\Translator
 * @author David Skála <skala2524@gmail.com>
 */
class TranslationEntity
{

    /** @var string */
    private $text = '';

    /** @var array */
    private $params = [];

    /**
     * TranslationEntity constructor.
     * @param $text
     * @param array $params
     */
    function __construct($text, array $params)
    {
        $this->text = $text;
        $this->params = $params;
    }


}
