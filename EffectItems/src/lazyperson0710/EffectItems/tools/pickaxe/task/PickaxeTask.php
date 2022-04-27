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

class PickaxeTask extends Task {

    public function onRun(): void {
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            $getNameTag = $player->getInventory()->getItemInHand()->getNamedTag();
            if ($getNameTag->getTag('AbyssPickaxe') !== null) {//AbyssPickaxe
                $effect = new EffectInstance(VanillaEffects::NIGHT_VISION(), 500, 0, false);
                $vanillaEffect = VanillaEffects::NIGHT_VISION();
                $this->addEffect($player, $effect, $vanillaEffect);
            }
            if ($getNameTag->getTag('AlchemyPickaxe') !== null) {//GalePickaxe
                $effect = new EffectInstance(VanillaEffects::SPEED(), 500, 0, false);
                $vanillaEffect = VanillaEffects::SPEED();
                $this->addEffect($player, $effect, $vanillaEffect);
            }
            if ($getNameTag->getTag('ToyPickaxe') !== null) {//ToyPickaxe
                $effect = new EffectInstance(VanillaEffects::HASTE(), 500, 0, false);
                $vanillaEffect = VanillaEffects::HASTE();
                $this->addEffect($player, $effect, $vanillaEffect);
            }
            if ($getNameTag->getTag('HeavyPickaxe') !== null) {//HeavyPickaxe
                $effect = new EffectInstance(VanillaEffects::SLOWNESS(), 60, 0, false);
                $vanillaEffect = VanillaEffects::SLOWNESS();
                $this->addEffect($player, $effect, $vanillaEffect);
                $effect = new EffectInstance(VanillaEffects::MINING_FATIGUE(), 60, 0, false);
                $vanillaEffect = VanillaEffects::MINING_FATIGUE();
                $this->addEffect($player, $effect, $vanillaEffect);
            }
            if ($getNameTag->getTag('BlindnessPickaxe') !== null) {//BlindnessPickaxe
                $effect = new EffectInstance(VanillaEffects::BLINDNESS(), 60, 0, false);
                $vanillaEffect = VanillaEffects::BLINDNESS();
                $this->addEffect($player, $effect, $vanillaEffect);
                return;
            }
            if ($getNameTag->getTag('FireResistancePickaxe') !== null) {//FireResistancePickaxe
                $effect = new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 60, 0, false);
                $vanillaEffect = VanillaEffects::FIRE_RESISTANCE();
                $this->addEffect($player, $effect, $vanillaEffect);
                return;
            }
        }
    }

    public function addEffect(Player $player, EffectInstance $effect, Effect $vanillaEffects){
        $effectInstance = $player->getEffects()->get($vanillaEffects);
        if ($effectInstance === null) {
            $player->getEffects()->add($effect);
        } elseif ($effectInstance->getDuration() < 499) {
            $player->getEffects()->add($effect);
        }
    }
}