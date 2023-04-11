<?php

declare(strict_types = 0);

namespace bbo51dog\mjolnir\model;

class Ban {

	private string $literal;

	private BanType $type;

	private string $reason;

	public function __construct(string $literal, BanType $type, string $reason) {
		$this->literal = $literal;
		$this->type = $type;
		$this->reason = $reason;
	}

	public function getLiteral() : string {
		return $this->literal;
	}

	public function getType() : BanType {
		return $this->type;
	}

	public function getReason() : string {
		return $this->reason;
	}
}
