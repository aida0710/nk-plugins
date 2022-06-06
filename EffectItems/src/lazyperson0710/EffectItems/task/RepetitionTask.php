<?php

namespace lazyperson0710\EffectItems\task;

use pocketmine\entity\effect\Effect;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class RepetitionTask extends Task {

    public function onRun(): void {
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            $getNameTag = $player->getInventory()->getItemInHand()->getNamedTag();
            ##pickaxe
            if ($getNameTag->getTag('ObsidianBreaker') !== null) {//ObsidianBreaker
                $effect = new EffectInstance(VanillaEffects::HASTE(), 12, 255, false);
                $vanillaEffect = VanillaEffects::HASTE();
                $this->addEffect($player, $effect, $vanillaEffect);
                return;
            }
            if ($getNameTag->getTag('GlowstoneBreaker') !== null) {//GlowstoneBreaker
                $effect = new EffectInstance(VanillaEffects::HASTE(), 12, 255, false);
                $vanillaEffect = VanillaEffects::HASTE();
                $this->addEffect($player, $effect, $vanillaEffect);
                return;
            }
            ##その他
            if ($getNameTag->getTag('SpeedRod') !== null) {//SpeedRod
                $effect = new EffectInstance(VanillaEffects::SPEED(), 12, 30, false);
                $vanillaEffect = VanillaEffects::SPEED();
                $this->addEffect($player, $effect, $vanillaEffect);
            }
        }
    }

    public function addEffect(Player $player, EffectInstance $effect, Effect $vanillaEffects) {
        $effectInstance = $player->getEffects()->get($vanillaEffects);
        if ($effectInstance === null) {
            $player->getEffects()->add($effect);
        } elseif ($effectInstance->getDuration() < 499) {
            $player->getEffects()->add($effect);
        }
    }
}