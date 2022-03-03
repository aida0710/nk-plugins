<?php

namespace bbo51dog\announce\repository\dto;

class AnnounceDto {

    private int $id;

    private string $content;

    private int $type;

    private int $timestamp;

    /**
     * @param string $content
     * @param int $type
     * @param string $timestamp
     * @param int $id
     */
    public function __construct(string $content, int $type, string $timestamp, int $id = -1) {
        $this->content = $content;
        $this->type = $type;
        $this->timestamp = $timestamp;
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getContent(): string {
        return $this->content;
    }

    /**
     * @return int
     */
    public function getType(): int {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getTimestamp(): int {
        return $this->timestamp;
    }

}