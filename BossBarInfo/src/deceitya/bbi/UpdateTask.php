<?php

declare(strict_types = 0);

namespace deceitya\bbi;

use Deceitya\MiningLevel\MiningLevelAPI;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use xenialdan\apibossbar\BossBar;
use function floor;

class UpdateTask extends Task {

    private BossBar $bar;
    private Player $player;

    public function __construct(BossBar $bar, Player $player) {
        $this->bar = $bar;
        $this->player = $player;
    }

    public function onRun() : void {
        if (!$this->player->isOnline()) {
            $this->getHandler()->cancel();
            return;
        }
        $api = MiningLevelAPI::getInstance();
        $player = $this->player;
        $level = $api->getLevel($player);
        $exp = $api->getExp($player);
        $upexp = $api->getLevelUpExp($player);
        if ($exp != 0) {
            $keiken = $exp / $upexp;
        } else {
            $keiken = 0;
        }
        $progress = $keiken * 100;
        $progress2 = floor($progress);
        $this->bar->setTitle("      §a現在のレベル §f:§b {$level} §f|§a 進行度 §f:§b {$progress2}％");
        $this->bar->setSubTitle("§a現在の経験値 §f:§b {$exp} §f|§a 次のレベルまで §f:§b {$upexp}");
        $this->bar->setPercentage($keiken);
    }
}
