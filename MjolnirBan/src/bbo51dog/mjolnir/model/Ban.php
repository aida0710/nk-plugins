<?php

namespace bbo51dog\mjolnir\model;

class Ban {

    private string $literal;

    private BanType $type;

    private string $reason;

    /**
     * @param string $literal
     * @param BanType $type
     * @param string $reason
     */
    public function __construct(string $literal, BanType $type, string $reason) {
        $this->literal = $literal;
        $this->type = $type;
        $this->reason = $reason;
    }

    /**
     * @return string
     */
    public function getLiteral(): string {
        return $this->literal;
    }

    /**
     * @return BanType
     */
    public function getType(): BanType {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getReason(): string {
        return $this->reason;
    }
}