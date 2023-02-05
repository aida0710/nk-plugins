<?php

declare(strict_types = 1);
namespace ymggacha\src\yomogi_server\ymggacha\scheduler;

use Closure;

class ScheduledEvent {

    private Closure $fn;
    private int $delay;

    public function __construct(Closure $fn, int $delay) {
        $this->fn = $fn;
        $this->delay = $delay;
    }

    public function getFunction(): Closure {
        return $this->fn;
    }

    public function getDelay(): int {
        return $this->delay;
    }
}
