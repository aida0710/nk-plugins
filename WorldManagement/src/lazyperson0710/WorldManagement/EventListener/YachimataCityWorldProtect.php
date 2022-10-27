<?php

namespace lazyperson0710\WorldManagement\EventListener;

use lazyperson0710\WorldManagement\database\WorldCategory;
use lazyperson710\core\packet\SendMessage\SendTip;
use pocketmine\block\VanillaBlocks;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\VanillaItems;

class YachimataCityWorldProtect implements Listener {

    /**
     * @param BlockBreakEvent $event
     * @return void
     * @priority Low
     */
    public function onBreak(BlockBreakEvent $event) {
        if (in_array($event->getPlayer()->getPosition()->getWorld()->getFolderName(), WorldCategory::UniqueAgricultureWorld)) {
            $blocks = [
                VanillaBlocks::WHEAT()->getId(),
                VanillaBlocks::POTATOES()->getId(),
                VanillaBlocks::CARROTS()->getId(),
                VanillaBlocks::BEETROOTS()->getId(),
            ];
            if (!in_array($event->getBlock()->getId(), $blocks)) {
                SendTip::Send($event->getPlayer(), "現在のワールドでは{$event->getBlock()->getName()}の破壊は許可されていません", "Protect", false);
                $event->cancel();
            }
        }
    }

    /**
     * @param BlockPlaceEvent $event
     * @return void
     * @priority Low
     */
    public function onPlace(BlockPlaceEvent $event) {
        if (in_array($event->getPlayer()->getPosition()->getWorld()->getFolderName(), WorldCategory::UniqueAgricultureWorld)) {
            $items = [
                VanillaItems::WHEAT_SEEDS()->getId(),
                VanillaItems::POTATO()->getId(),
                VanillaItems::CARROT()->getId(),
                VanillaItems::BEETROOT_SEEDS()->getId(),
            ];
            if (!in_array($event->getPlayer()->getInventory()->getItemInHand()->getId(), $items)) {
                SendTip::Send($event->getPlayer(), "現在のワールドでは{$event->getPlayer()->getInventory()->getItemInHand()->getName()}の設置は許可されていません", "Protect", false);
                $event->cancel();
            }
        }
    }

    public function onInteract(PlayerInteractEvent $event) {
        if (in_array($event->getBlock()->getPosition()->getWorld()->getFolderName(), WorldCategory::UniqueAgricultureWorld)) {
            $items = [
                VanillaItems::WHEAT_SEEDS()->getId(),
                VanillaItems::POTATO()->getId(),
                VanillaItems::CARROT()->getId(),
                VanillaItems::BEETROOT_SEEDS()->getId(),
            ];
            if (!in_array($event->getPlayer()->getInventory()->getItemInHand()->getId(), $items)) {
                SendTip::Send($event->getPlayer(), "現在のワールドでは{$event->getPlayer()->getInventory()->getItemInHand()->getName()}の使用は許可されていません", "Protect", false);
                $event->cancel();
            }
        }
    }

}