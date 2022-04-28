<?php

namespace lazyperson0710\EffectItems\tools\task;

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
            ##AllTools
            if ($getNameTag->getTag('AbyssTools') !== null) {//AbyssTools
                $effect = new EffectInstance(VanillaEffects::BLINDNESS(), 500, 0, false);
                $vanillaEffect = VanillaEffects::BLINDNESS();
                $this->addEffect($player, $effect, $vanillaEffect);
            }
            if ($getNameTag->getTag('AlchemyTools') !== null) {//GaleTools
                $effect = new EffectInstance(VanillaEffects::SPEED(), 60, 0, false);
                $vanillaEffect = VanillaEffects::SPEED();
                $this->addEffect($player, $effect, $vanillaEffect);
            }
            if ($getNameTag->getTag('ToyTools') !== null) {//ToyTools
                $effect = new EffectInstance(VanillaEffects::HASTE(), 60, 0, false);
                $vanillaEffect = VanillaEffects::HASTE();
                $this->addEffect($player, $effect, $vanillaEffect);
            }
            if ($getNameTag->getTag('HeavyTools') !== null) {//HeavyTools
                $effect = new EffectInstance(VanillaEffects::SLOWNESS(), 60, 0, false);
                $vanillaEffect = VanillaEffects::SLOWNESS();
                $this->addEffect($player, $effect, $vanillaEffect);
                $effect = new EffectInstance(VanillaEffects::MINING_FATIGUE(), 60, 0, false);
                $vanillaEffect = VanillaEffects::MINING_FATIGUE();
                $this->addEffect($player, $effect, $vanillaEffect);
            }
            if ($getNameTag->getTag('NightVisionTools') !== null) {//NightVisionTools
                $effect = new EffectInstance(VanillaEffects::NIGHT_VISION(), 500, 0, false);
                $vanillaEffect = VanillaEffects::NIGHT_VISION();
                $this->addEffect($player, $effect, $vanillaEffect);
                return;
            }
            if ($getNameTag->getTag('FireResistanceTools') !== null) {//FireResistanceTools
                $effect = new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 60, 0, false);
                $vanillaEffect = VanillaEffects::FIRE_RESISTANCE();
                $this->addEffect($player, $effect, $vanillaEffect);
                return;
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