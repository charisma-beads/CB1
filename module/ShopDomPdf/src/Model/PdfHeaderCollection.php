<?php

namespace ShopDomPdf\Model;

use Common\Model\AbstractCollection;


class PdfHeaderCollection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $entityClass = PdfTextLine::class;

    /**
     * @param array $array
     */
    public function __construct($array = [])
    {
        $this->addHeaderLines($array);
    }

    /**
     * @param array $headerLines
     */
    public function addHeaderLines(array $headerLines)
    {
        foreach ($headerLines as $line) {
            $this->addHeaderLine($line);
        }
    }

    /**
     * @param $headerLine
     * @return $this
     */
    public function addHeaderLine($headerLine)
    {
        if ($headerLine instanceof PdfTextLine) {
            $this->add($headerLine);
        }

        if (is_array($headerLine)) {
            $headerLine = new $this->entityClass($headerLine);
            $this->add($headerLine);
        }

        return $this;
    }
}
