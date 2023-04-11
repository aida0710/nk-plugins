<?php

declare(strict_types = 0);

namespace bbo51dog\announce\repository\dto;

class AnnounceDto {

	private int $id;

	private string $content;

	private int $type;

	private int $timestamp;

	/**
	 * @param string $content
	 * @param int    $type
	 * @param int    $timestamp
	 * @param int    $id
	 */
	public function __construct(string $content, int $type, int $timestamp, int $id = -1) {
		$this->content = $content;
		$this->type = $type;
		$this->timestamp = $timestamp;
		$this->id = $id;
	}

	public function getId() : int {
		return $this->id;
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
