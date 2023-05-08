<?php

declare(strict_types = 1);

namespace bbo51dog\pmdiscord\element;

abstract class Element implements ElementType {

	/** @var mixed */
	protected mixed $data;

	/** @var string */
	protected string $type;

	/**
	 * @return mixed
	 */
	public function getData() : mixed {
		return $this->data;
	}

	/**
	 * @return string
	 */
	public function getType() : string {
		return $this->type;
	}
}
