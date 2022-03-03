<?php

namespace bbo51dog\announce\repository\dto;

class UserDto {

    private string $name;

    private bool $confirmed;

    private int $announceId;

    private bool $hasRead;

    /**
     * @param string $name
     * @param bool $confirmed
     * @param int $announceId
     * @param bool $hasRead
     */
    public function __construct(string $name, bool $confirmed, int $announceId, bool $hasRead = false) {
        $this->name = $name;
        $this->confirmed = $confirmed;
        $this->announceId = $announceId;
        $this->hasRead = $hasRead;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isConfirmed(): bool {
        return $this->confirmed;
    }

    /**
     * @param bool $confirmed
     */
    public function setConfirmed(bool $confirmed): void {
        $this->confirmed = $confirmed;
    }

    /**
     * @return int
     */
    public function getAnnounceId(): int {
        return $this->announceId;
    }

    /**
     * @param int $announceId
     */
    public function setAnnounceId(int $announceId): void {
        $this->announceId = $announceId;
    }

    /**
     * @return bool
     */
    public function hasRead(): bool {
        return $this->hasRead;
    }

    /**
     * @param bool $hasRead
     */
    public function setHasRead(bool $hasRead): void {
        $this->hasRead = $hasRead;
    }

}