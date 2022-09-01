<?php

namespace bbo51dog\announce\repository\dto;

class PasswordDto {

    private string $name;

    private bool $isConfirmed;

    /**
     * @param string $name
     * @param bool   $isConfirmed
     */
    public function __construct(string $name, bool $isConfirmed) {
        $this->name = $name;
        $this->isConfirmed = $isConfirmed;
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
        return $this->isConfirmed;
    }

}