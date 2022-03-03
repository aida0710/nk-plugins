<?php

namespace Deceitya\Flytra;

use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;
use pocketmine\world\World;

class FlytraPlugin extends PluginBase implements Listener {

    /** @var string[] */
    private array $worlds = [];

    public function onEnable(): void {
        $this->reloadConfig();
        $this->worlds = $this->getConfig()->getAll();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function checkFly(Player $player, World $world, Item $item) {
        if ($player->isSurvival()) {
            if ($item->getId() === ItemIds::ELYTRA) {
                if (Server::getInstance()->isOp($player->getName())) {
                    $player->setAllowFlight(true);
                } elseif (in_array($world->getFolderName(), $this->worlds)) {
                    $player->setAllowFlight(false);
                    $player->setFlying(false);
                } else {
                    $player->setAllowFlight(true);
                }
            } else {
                $player->setAllowFlight(false);
                $player->setFlying(false);
            }
        }
    }

    public function onEntityTeleport(EntityTeleportEvent $event) {
        $player = $event->getEntity();
        if ($player instanceof Player) {
            $this->checkFly($player, $event->getTo()->getWorld(), $player->getArmorInventory()->getChestplate());
        }
    }

    public function onPlayerJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        $player->getArmorInventory()->getListeners()->add(new FlytraInventoryListener($this));
        $this->checkFly($player, $player->getWorld(), $player->getArmorInventory()->getChestplate());
    }

    public function onPlayerRespawn(PlayerRespawnEvent $event) {
        $player = $event->getPlayer();
        $this->getScheduler()->scheduleDelayedTask(new ClosureTask(
            function () use ($player): void {
                $this->checkFly($player, $player->getWorld(), $player->getArmorInventory()->getChestplate());
            }
        ), 20);
    }
}
