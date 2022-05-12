<?php

namespace lazyperson710\core\task;

use pocketmine\entity\effect\Effect;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class FlyCheckTask extends Task {

    private function FlyTaskExecution(Player $player, bool $infinity, ?int $time = null) {
        //$infinityがtrueの場合は無制限、falseは有限のため$timeの分数を代わりに使用
    }

    public function onRun(): void {
        $this->FlyTaskExecution();
    }
}