<?php

declare(strict_types = 0);

namespace lazyperson0710\EffectItems\task;

use lazyperson710\core\packet\AddEffectPacket;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class RepetitionTask extends Task {

    public function onRun() : void {
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            $getNameTag = $player->getInventory()->getItemInHand()->getNamedTag();
            ##pickaxe
            if ($getNameTag->getTag('ObsidianBreaker') !== null) {//ObsidianBreaker
                $effect = new EffectInstance(VanillaEffects::HASTE(), 12, 255, false);
                AddEffectPacket::Add($player, $effect, VanillaEffects::HASTE());
                return;
            }
            if ($getNameTag->getTag('GlowstoneBreaker') !== null) {//GlowstoneBreaker
                $effect = new EffectInstance(VanillaEffects::HASTE(), 12, 255, false);
                AddEffectPacket::Add($player, $effect, VanillaEffects::HASTE());
                return;
            }
            ##その他
            if ($getNameTag->getTag('SpeedRod') !== null) {//SpeedRod
                $effect = new EffectInstance(VanillaEffects::SPEED(), 12, 30, false);
                AddEffectPacket::Add($player, $effect, VanillaEffects::SPEED());
            }
        }
    }
}
