<?php

declare(strict_types=1);
namespace bbo51dog\announce\model;

use bbo51dog\announce\repository\dto\AnnounceDto;

class Announce {

	private string $content;

	private int $type;

	private int $timestamp;

	public function __construct(string $content, int $type, string $timestamp) {
		$this->content = $content;
		$this->type = $type;
		$this->timestamp = $timestamp;
	}

	public static function createFromDto(AnnounceDto $dto) : self {
		return new Announce($dto->getContent(), $dto->getType(), $dto->getTimestamp());
	}

	public function getContent() : string {
		return $this->content;
	}

	public function getType() : int {
		return $this->type;
	}

	public function getTimestamp() : int {
		return $this->timestamp;
	}
}
