<?php

declare(strict_types = 0);

namespace bbo51dog\announce\model;

use bbo51dog\announce\repository\dto\AnnounceDto;

class Announce {

	private string $content;

	private int $type;

	private int $timestamp;

	/**
	 * @param string $content
	 * @param int    $type
	 * @param int    $timestamp
	 */
	public function __construct(string $content, int $type, int $timestamp) {
		$this->content = $content;
		$this->type = $type;
		$this->timestamp = $timestamp;
	}

	/**
	 * @param AnnounceDto $dto
	 * @return static
	 */
	public static function createFromDto(AnnounceDto $dto) : self {
		return new Announce($dto->getContent(), $dto->getType(), $dto->getTimestamp());
	}

	/**
	 * @return string
	 */
	public function getContent() : string {
		return $this->content;
	}

	/**
	 * @return int
	 */
	public function getType() : int {
		return $this->type;
	}

	/**
	 * @return int
	 */
	public function getTimestamp() : int {
		return $this->timestamp;
	}
}
