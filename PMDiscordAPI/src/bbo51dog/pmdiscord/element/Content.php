<?php

declare(strict_types = 0);

namespace bbo51dog\pmdiscord\element;

class Content extends Element {

	/** @var string */
	protected mixed $data = '';

	protected string $type = self::TYPE_CONTENT;

	/**
	 * Set content text
	 */
	public function setText(string $text) : void {
		$this->data = $text;
	}
}
