<?php

namespace lazyperson0710\EffectItems;

use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class ItemsScheduler extends Task {

    public function onRun(): void {
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            if ($player->getInventory()->getItemInHand()->getId() === 1501) {//疾風のつるはし
                $effect = new EffectInstance(VanillaEffects::SPEED(), 60, 7, false);
                $player->getEffects()->add($effect);
                return;
            }
            if ($player->getInventory()->getItemInHand()->getId() === 1502) {//窓一郎の炯眼
                $night_vision = $player->getEffects()->get(VanillaEffects::NIGHT_VISION());
                if ($night_vision === null) {
                    $effect = new EffectInstance(VanillaEffects::NIGHT_VISION(), 500, 0, false);
                    $player->getEffects()->add($effect);
                    return;
                } elseif ($night_vision->getDuration() < 499) {
                    $effect = new EffectInstance(VanillaEffects::NIGHT_VISION(), 500, 0, false);
                    $player->getEffects()->add($effect);
                }
                return;
            }
            if ($player->getInventory()->getItemInHand()->getId() === 1503) {//おもちゃのつるはし
                $night_vision = $player->getEffects()->get(VanillaEffects::HASTE());
                if ($night_vision === null) {
                    $effect = new EffectInstance(VanillaEffects::HASTE(), 60, 0, false);
                    $player->getEffects()->add($effect);
                    return;
                } elseif ($night_vision->getDuration() < 59) {
                    $effect = new EffectInstance(VanillaEffects::HASTE(), 60, 0, false);
                    $player->getEffects()->add($effect);
                }
                return;
            }
            if ($player->getInventory()->getItemInHand()->getId() === 1504) {//重いつるはし
                $effect = new EffectInstance(VanillaEffects::SLOWNESS(), 60, 0, false);
                $player->getEffects()->add($effect);
                $effect = new EffectInstance(VanillaEffects::MINING_FATIGUE(), 60, 0, false);
                $player->getEffects()->add($effect);
                return;
            }
            if ($player->getInventory()->getItemInHand()->getId() === 1505) {//満福つるはし
                $effect = new EffectInstance(VanillaEffects::SATURATION(), 60, 0, false);
                $player->getEffects()->add($effect);
                return;
            }
            if ($player->getInventory()->getItemInHand()->getId() === 1506) {//暗夜行山
                $effect = new EffectInstance(VanillaEffects::BLINDNESS(), 60, 0, false);
                $player->getEffects()->add($effect);
                return;
            }
            if ($player->getInventory()->getItemInHand()->getId() === 1507) {//溶岩泳者の友
                $effect = new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 60, 0, false);
                $player->getEffects()->add($effect);
                return;
            }
        }
    }
}