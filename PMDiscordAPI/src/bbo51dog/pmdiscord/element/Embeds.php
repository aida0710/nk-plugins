<?php

declare(strict_types = 0);

namespace bbo51dog\pmdiscord\element;

class Embeds extends Element {

	/** @var array */
	protected mixed $data = [];

	protected string $type = self::TYPE_EMBEDS;

	/**
	 * Add embed
	 */
	public function add(Embed $embed) : void {
		$this->data[] = $embed->getData();
	}
}
