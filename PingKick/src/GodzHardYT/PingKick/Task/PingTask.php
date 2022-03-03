<?php

namespace GodzHardYT\PingKick\Task;

use GodzHardYT\PingKick\Main;
use pocketmine\scheduler\Task;

class PingTask extends Task {

    private $plugin;

    public function __construct(Main $main) {
        $this->plugin = $main;
    }

    /**
     * @inheritDoc
     */
    public function onRun(): void {
        foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
            if ($player->getNetworkSession()->getPing() >= 1500) {
                $player->kick("回線速度が遅すぎる為、kickされました(Ping 1500以上)\n何かあれば@lazyperson0710にご連絡ください");
            }
        }
    }

}
