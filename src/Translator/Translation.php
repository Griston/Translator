<?php

namespace Griston\Translator;

use Nette\SmartObject;

/**
 * Class Translation
 * @package Griston\Translator
 */
class Translation
{

    /** @var \Nette\Localization\ITranslator */
    private $translator;

    /** @var string */
    private $text = '';

    /** @var array */
    private $params = [];

    /**
     * Translation constructor.
     * @param \Nette\Localization\ITranslator $translator
     * @param $text
     * @param array $params
     */
    function __construct(\Nette\Localization\ITranslator $translator, $text, array $params)
    {
        $this->translator = $translator;
        $this->text = $text;
        $this->params = $params;
    }

    /**
     * @param $text
     * @return $this
     */
    function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return string
     */
    function getText()
    {
        return $this->text;
    }

    /**
     * @param array $params
     * @return $this
     */
    function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @return array
     */
    function getParams()
    {
        return $this->params;
    }

    /**
     * @param $param
     * @return $this
     */
    function addParam($param)
    {
        $this->params[] = $param;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return vsprintf($this->translator->translate($this->text), $this->params);
    }

}
