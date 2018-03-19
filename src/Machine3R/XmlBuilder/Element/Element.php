<?php

namespace Machine3R\XmlBuilder\Element;

use SimpleXMLElement;

class Element {

	/** @var string $tagName */
	public $tagName;

	/** @var SimpleXmlElement $sxe */
	protected $sxe;

	/** @var Element $parent */
	private $parent;

	/**
	 * Constructor
	 * @param string $tagName
	 * @param SimpleXMLElement $sxe
	 * @param Element $parent
	 */
	public function __construct($tagName, SimpleXMLElement $sxe, Element $parent = null) {
		$this->tagName = $tagName;
		$this->parent = $parent;
		$this->sxe = $sxe;
	}

	public function getTagName() {
		return strtoupper($this->tagName);
	}

	/**
	 * creates an element
	 * @param string $tagName
	 * @param array $args array of arguments of called method
	 * @return Element created element
	 */
	public function __call($tagName, array $args) {
		$value = (isset($args[0])) ? $args[0] : null;
		$sxe = $this->sxe->addChild($tagName, $value);
		$child = new Element($tagName, $sxe, $this);
		return $child;
	}

	/**
	 * add attribute
	 * @param string $key
	 * @param string $value
	 * @return $this
	 */
	public function attr($key, $value) {
		$this->sxe->addAttribute($key, $value);
		return $this;
	}

	/**
	 * add attributes
	 * @param array $attrs array of key-value pairs
	 * @return $this
	 */
	public function attrs(array $attrs = []) {
		foreach ($attrs as $key => $value) {
			$this->attr($key, $value);
		}
		return $this;
	}

	/**
	 * returns parent
	 * @return Element
	 */
	public function end() {
		return $this->parent;
	}

	/**
	 * @return SimpleXMLElement
	 */
	protected function getSXE() {
		return $this->sxe;
	}

}
