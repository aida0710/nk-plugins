<?php

namespace lazyperson0710\EffectItems\tools\pickaxe\task;

use pocketmine\block\BlockLegacyIds;
use pocketmine\entity\effect\Effect;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class AbyssPickaxe extends Task {

    public function onRun(): void {
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            if ($player->getInventory()->getItemInHand()->getId() === 1502) {//窓一郎の炯眼
                $effect = new EffectInstance(VanillaEffects::NIGHT_VISION(), 500, 0, false);
                $this->addEffect($player, $effect);
            }
        }
    }

    public function addEffect(Player $player, EffectInstance $effect){
        $night_vision = $player->getEffects()->get(VanillaEffects::NIGHT_VISION());
        if ($night_vision === null) {
            $player->getEffects()->add($effect);
        } elseif ($night_vision->getDuration() < 499) {
            $player->getEffects()->add($effect);
        }
    }
}