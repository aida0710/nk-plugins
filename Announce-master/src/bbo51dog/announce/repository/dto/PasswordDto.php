<?php

declare(strict_types = 0);

namespace bbo51dog\announce\repository\dto;

class PasswordDto {

	private string $name;

	private bool $isConfirmed;

	public function __construct(string $name, bool $isConfirmed) {
		$this->name = $name;
		$this->isConfirmed = $isConfirmed;
	}

	public function getName() : string {
		return $this->name;
	}

	public function isConfirmed() : bool {
		return $this->isConfirmed;
	}

}
