<?php

namespace Deceitya\Flytra;

use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\inventory\ArmorInventory;
use pocketmine\inventory\Inventory;
use pocketmine\item\Item;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;

class PlayerEventListener implements Listener {

    public function onSlotChange(Inventory $inventory, int $slot, Item $oldItem): void {
        if ($inventory instanceof ArmorInventory && $slot === ArmorInventory::SLOT_CHEST) {
            $holder = $inventory->getHolder();
            if ($holder instanceof Player) {
                Main::getInstance()->checkFly($holder, $holder->getWorld(), $inventory->getChestplate());
            }
        }
    }

    public function onEntityTeleport(EntityTeleportEvent $event) {
        $player = $event->getEntity();
        if ($player instanceof Player) {
            Main::getInstance()->checkFly($player, $event->getTo()->getWorld(), $player->getArmorInventory()->getChestplate());
        }
    }

    public function onPlayerJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        $player->getArmorInventory()->getListeners()->add(new PlayerEventListener());
        Main::getInstance()->checkFly($player, $player->getWorld(), $player->getArmorInventory()->getChestplate());
    }

    public function onPlayerRespawn(PlayerRespawnEvent $event) {
        $player = $event->getPlayer();
        Main::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(
            function () use ($player): void {
                Main::getInstance()->checkFly($player, $player->getWorld(), $player->getArmorInventory()->getChestplate());
            }
        ), 20);
    }

    public function onContentChange(Inventory $inventory, array $oldContents): void {
    }
}