<?php

declare(strict_types = 1);

namespace bbo51dog\pmdiscord\element;

class Content extends Element {

	/** @var string */
	protected mixed $data = '';

	/** @var string */
	protected string $type = self::TYPE_CONTENT;

	/**
	 * Set content text
	 *
	 * @param string $text
	 * @return void
	 */
	public function setText(string $text) : void {
		$this->data = $text;
	}
}
