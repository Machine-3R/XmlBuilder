<?php

namespace Machine3R\XmlBuilder;

use Machine3R\XmlBuilder\Element\Element;
use Machine3R\XmlBuilder\Exception\ValidationException;
use SimpleXMLElement;
use DOMDocument;
use Exception;
use ErrorException;

class XmlBuilder extends Element {

	const EXCEPTION_XSD_UNSET = 'A valid XSD must be set to validate';

	/** @var string $xsd */
	private $xsd;

	/** @var DOMDocument $dom */
	protected $dom;

	/**
	 * Constructor
	 */
	public function __construct($tagName) {
		parent::__construct($tagName, new SimpleXMLElement("<$tagName/>"), null);
	}

	/**
	 * set XSD for validation
	 * @param string $xsd
	 * @return $this
	 */
	public function setXSD($xsd) {
		$this->xsd = $xsd;
		return $this;
	}

	/**
	 * validates xml according set or given xsd
	 * @param string $xsd , optional if already set else required
	 * @return bool on success
	 * @throws Exception
	 * @throws ErrorException
	 */
	public function validate($xsd = null) {
		$xsd = ($xsd) ? $xsd : $this->xsd;
		if (!realpath($xsd)) {
			throw new ValidationException(self::EXCEPTION_XSD_UNSET);
		}
		if (!$this->getDOMDocument()->schemaValidate($xsd)) {
			list($type, $message, $file, $line) = array_values(error_get_last());
			throw new ValidationException($message, $type, E_ERROR, $file, $line);
		}
		return true;
	}

	/**
	 * outputs as (formatted) xml
	 * @param bool $isFormatted
	 * @return string xml
	 */
	public function asXML($isFormatted = false) {
		if ($isFormatted) {
			return $this->asFormattedXML();
		}
		return $this->getSXE()->asXML();
	}

	/**
	 * @return DOMDocument
	 */
	protected function getDOMDocument() {
		if (!$this->dom) {
			$this->dom = new DOMDocument('1.0', 'UTF-8');
			$this->dom->loadXML($this->getSXE()->asXML());
		}
		return $this->dom;
	}

	/**
	 * returns formatted xml 
	 * @return string xml
	 */
	public function asFormattedXML() {
		$this->getDOMDocument()->preserveWhiteSpace = false;
		$this->getDOMDocument()->formatOutput = true;
		return $this->dom->saveXML();
	}

	/**
	 * returns formatted xml
	 * @return string xml
	 */
	public function __toString() {
		return $this->asFormattedXML();
	}
}
