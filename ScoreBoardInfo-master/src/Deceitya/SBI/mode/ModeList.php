<?php

namespace Deceitya\SBI\mode;

use pocketmine\utils\SingletonTrait;

class ModeList {

    use SingletonTrait;

    /** @var Mode[] */
    private array $modes;

    public function __construct() {
        $this->registerAll(
            new NormalMode(),
            new SimpleMode(),
            new ItemMode(),
            new TimeMode(),
            new NoMode(),
        );
    }

    public function register(Mode $mode): self {
        $this->modes[$mode->getId()] = $mode;
        return $this;
    }

    public function registerAll(Mode ...$modes) {
        foreach ($modes as $mode) {
            $this->register($mode);
        }
    }

    public function get(int $id): Mode {
        return $this->modes[$id];
    }

    /**
     * @return Mode[]
     */
    public function getAll(): array {
        return $this->modes;
    }
}