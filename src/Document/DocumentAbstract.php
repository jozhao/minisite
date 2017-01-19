<?php

/**
 * @file DocumentAbstract.php
 */

namespace Minisite\Document;

use Symfony\Component\DomCrawler\Crawler;
use Minisite\Exception\RuntimeException;
use Minisite\Exception\InvalidArgumentException;

/**
 * Class DocumentAbstract
 * @package minisite\Document
 */
abstract class DocumentAbstract implements DocumentInterface
{
    private $_crawler;
    private $_document;

    /**
     * DocumentAbstract constructor.
     * @param null $doc
     */
    public function __construct($doc = null)
    {
        if (!empty($doc)) {
            // Bypass lib error.
            libxml_use_internal_errors(true);

            $document = new \DOMDocument();
            $document->loadHTMLFile($doc);
            self::setDocument($document);

            $crawler = new Crawler();
            $crawler->addDocument($document);
            self::setCrawler($crawler);
        } else {
            throw new InvalidArgumentException('Invalid document');
        }
    }

    /**
     * Parse the document.
     */
    public function parse(array $options = [])
    {
        $parse = new DocumentParse($options);
        $parse->setDocument($this->getDocument());
        $parse->setCrawler($this->getCrawler());
        $parse->parse();
        $document = $parse->getDocument();
        self::setDocument($document);
    }

    /**
     * Render document html.
     * @return mixed
     */
    public function render()
    {
        $html = $this->getDocument()->saveHTML();

        return $html;
    }

    /**
     * @return mixed
     */
    public function getCrawler()
    {
        return $this->_crawler;
    }

    /**
     * @param mixed $crawler
     */
    public function setCrawler($crawler)
    {
        $this->_crawler = $crawler;
    }

    /**
     * @return mixed
     */
    public function getDocument()
    {
        return $this->_document;
    }

    /**
     * @param mixed $doc
     */
    public function setDocument($doc)
    {
        $this->_document = $doc;
    }
}
