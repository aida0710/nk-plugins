<?php

declare(strict_types = 0);

namespace bbo51dog\announce\repository\dto;

class UserDto {

	private string $name;

	private bool $confirmed;

	private int $announceId;

	private bool $hasRead;

	public function __construct(string $name, bool $confirmed, int $announceId, bool $hasRead = false) {
		$this->name = $name;
		$this->confirmed = $confirmed;
		$this->announceId = $announceId;
		$this->hasRead = $hasRead;
	}

	public function getName() : string {
		return $this->name;
	}

	public function isConfirmed() : bool {
		return $this->confirmed;
	}

	public function setConfirmed(bool $confirmed) : void {
		$this->confirmed = $confirmed;
	}

	public function getAnnounceId() : int {
		return $this->announceId;
	}

	public function setAnnounceId(int $announceId) : void {
		$this->announceId = $announceId;
	}

	public function hasRead() : bool {
		return $this->hasRead;
	}

	public function setHasRead(bool $hasRead) : void {
		$this->hasRead = $hasRead;
	}

}
