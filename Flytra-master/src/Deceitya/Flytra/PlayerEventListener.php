<?php

declare(strict_types = 0);

namespace Deceitya\Flytra;

use Deceitya\Flytra\task\FlyCheckTask;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerGameModeChangeEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\inventory\ArmorInventory;
use pocketmine\inventory\Inventory;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use function array_key_exists;

class PlayerEventListener implements Listener {

    public function onSlotChange(Inventory $inventory, int $slot) : void {
        if ($inventory instanceof ArmorInventory && $slot === ArmorInventory::SLOT_CHEST) {
            $holder = $inventory->getHolder();
            if ($holder instanceof Player) {
                Main::getInstance()->checkFly($holder, $holder->getWorld(), $inventory->getChestplate());
            }
        }
    }

    public function onContentChange(Inventory $inventory, array $oldContents) : void {
    }

    public function onEntityTeleport(EntityTeleportEvent $event) : void {
        $player = $event->getEntity();
        if ($player instanceof Player) {
            Main::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(
                function () use ($player) : void {
                    Main::getInstance()->checkFly($player, $player->getWorld(), $player->getArmorInventory()->getChestplate());
                }
            ), 20);
        }
    }

    public function onPlayerJoin(PlayerJoinEvent $event) : void {
        $player = $event->getPlayer();
        $player->getArmorInventory()->getListeners()->add(new PlayerEventListener());
        Main::getInstance()->checkFly($player, $player->getWorld(), $player->getArmorInventory()->getChestplate());
    }

    public function onQuit(PlayerQuitEvent $event) : void {
        $player = $event->getPlayer();
        if (array_key_exists($player->getName(), FlyCheckTask::$flyTask)) {
            unset(FlyCheckTask::$flyTask[$player->getName()]);
        }
    }

    public function onPlayerRespawn(PlayerRespawnEvent $event) : void {
        $player = $event->getPlayer();
        Main::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(
            function () use ($player) : void {
                Main::getInstance()->checkFly($player, $player->getWorld(), $player->getArmorInventory()->getChestplate());
            }
        ), 20);
    }

    public function changeGameMode(PlayerGameModeChangeEvent $event) : void {
        $player = $event->getPlayer();
        Main::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(
            function () use ($player) : void {
                Main::getInstance()->checkFly($player, $player->getWorld(), $player->getArmorInventory()->getChestplate());
            }
        ), 20);
    }
}
