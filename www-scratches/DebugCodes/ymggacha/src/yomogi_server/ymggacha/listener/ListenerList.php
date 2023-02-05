<?php

declare(strict_types = 1);
namespace ymggacha\src\yomogi_server\ymggacha\listener;

use pocketmine\event\Listener;
use ymggacha\src\yomogi_server\ymggacha\listener\listeners\AddTicketListener;
use ymggacha\src\yomogi_server\ymggacha\listener\listeners\UnregisterRollingGachaListener;

class ListenerList {

    /** @var array<Listener> */
    private array $listeners;

    public function __construct() {
        $this->init();
    }

    private function init(): void {
        $this->addListener(new UnregisterRollingGachaListener());
        $this->addListener(new AddTicketListener());
        // and more...?
    }

    /**
     * @return Listener[]
     * 登録されている全てのリスナーを返します
     */
    public function getAll(): array {
        return $this->listeners;
    }

    /**
     * @return void
     * リスナーを登録します
     */
    public function addListener(Listener $listener): void {
        $this->listeners[] = $listener;
    }
}
