<?php

namespace lazyperson710\sff\listener;

use lazyperson710\sff\form\CommandStorageForm;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\ItemFactory;
use pocketmine\Server;

class CommandStorageListener implements Listener {

    public function onInteract(PlayerInteractEvent $event) {
        $player = $event->getPlayer();
        $inHand = $player->getInventory()->getItemInHand();
        if ($inHand->getId() === 383) {
            if ($inHand->getMeta() === 35) {
                if ($player->isSneaking()) {
                    $player->sendForm(new CommandStorageForm);
                } elseif ($inHand->getName() === "コマンド記憶装置") {
                    $player->sendMessage("§bCmdStorage §7>> §cスニークしながらタップすることでコマンドを設定することが出来ます");
                } else {
                    Server::getInstance()->dispatchCommand($player, $inHand->getName());
                    $player->sendTip("§bCmdStorage §7>> §a{$inHand->getName()}を実行しました");
                }
            }
        }
    }

    public function onUes(PlayerItemUseEvent $event) {
        $player = $event->getPlayer();
        $inHand = $player->getInventory()->getItemInHand();
        if ($inHand->getId() === 383) {
            if ($inHand->getMeta() === 35) {
                if ($player->isSneaking()) {
                    $player->sendForm(new CommandStorageForm);
                } elseif ($inHand->getName() === "コマンド記憶装置") {
                    $player->sendMessage("§bCmdStorage §7>> §cスニークしながらタップすることでコマンドを設定することが出来ます");
                } else {
                    Server::getInstance()->dispatchCommand($player, $inHand->getName());
                    $player->sendTip("§bCmdStorage §7>> §a{$inHand->getName()}を実行しました");
                }
            }
            if ($inHand->getMeta() === 110) {
                $player->getInventory()->removeItem(ItemFactory::getInstance()->get(383, 110, 1));
                $effect = new EffectInstance(VanillaEffects::HASTE(), 3 * 20 * 60, 4, false);
                $player->getEffects()->add($effect);
                $player->sendMessage("§bEffect §7>> §a採掘速度上昇Lv.5を３分間付与しました");
            }
        }
    }

}