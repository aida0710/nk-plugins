<?php

declare(strict_types = 1);
namespace brokiem\simplelay;

use pocketmine\block\Opaque;
use pocketmine\block\Slab;
use pocketmine\block\Stair;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\InteractPacket;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\world\Position;

class EventListener implements Listener {

    private SimpleLay $plugin;

    public function __construct(SimpleLay $plugin) {
        $this->plugin = $plugin;
    }

    public function onPlayerJoin(PlayerJoinEvent $event): void {
        $this->plugin->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use ($event): void {
            foreach ($this->plugin->sittingData as $playerName => $data) {
                $sittingPlayer = $this->plugin->getServer()->getPlayerExact($playerName);
                if ($sittingPlayer !== null) {
                    $block = $sittingPlayer->getWorld()->getBlock($sittingPlayer->getPosition()->add(0, -0.3, 0));
                    if ($block instanceof Stair or $block instanceof Slab) {
                        $pos = $block->getPosition()->add(0.5, 1.5, 0.5);
                    } elseif ($block instanceof Opaque) {
                        $pos = $block->getPosition()->add(0.5, 2.1, 0.5);
                    } else {
                        return;
                    }
                    $this->plugin->setSit($sittingPlayer, [$event->getPlayer()], new Position($pos->x, $pos->y, $pos->z, $sittingPlayer->getWorld()), $this->plugin->sittingData[strtolower($sittingPlayer->getName())]['eid']);
                }
            }
        }), 30);
    }

    public function onPlayerQuit(PlayerQuitEvent $event): void {
        $player = $event->getPlayer();
        if ($this->plugin->isSitting($player)) {
            $this->plugin->unsetSit($player);
        }
    }

    public function onTeleport(EntityTeleportEvent $event): void {
        $entity = $event->getEntity();
        if ($entity instanceof Player) {
            if ($this->plugin->isSitting($entity)) {
                $this->plugin->unsetSit($entity);
            }
        }
    }

    public function onDeath(PlayerDeathEvent $event): void {
        $player = $event->getPlayer();
        if ($this->plugin->isSitting($player)) {
            $this->plugin->unsetSit($player);
        }
    }

    public function onMove(PlayerMoveEvent $event): void {
        $player = $event->getPlayer();
        if ($this->plugin->isSitting($player)) {
            $this->plugin->optimizeRotation($player);
        }
    }

    public function onBlockBreak(BlockBreakEvent $event): void {
        $block = $event->getBlock();
        if ($block instanceof Stair or $block instanceof Slab) {
            $pos = $block->getPosition()->add(0.5, 1.5, 0.5);
        } elseif ($block instanceof Opaque) {
            $pos = $block->getPosition()->add(0.5, 2.1, 0.5);
        } else {
            return;
        }
        foreach ($this->plugin->sittingData as $playerName => $data) {
            if ($pos->equals($data["pos"])) {
                $sittingPlayer = $this->plugin->getServer()->getPlayerExact($playerName);
                if ($sittingPlayer !== null) {
                    $this->plugin->unsetSit($sittingPlayer);
                }
            }
        }
    }

    public function onDataPacketReceive(DataPacketReceiveEvent $event): void {
        $packet = $event->getPacket();
        $player = $event->getOrigin()->getPlayer();
        if ($player === null) {
            return;
        }
        if ($packet instanceof InteractPacket and $packet->action === InteractPacket::ACTION_LEAVE_VEHICLE && $this->plugin->isSitting($player)) {
            $this->plugin->unsetSit($player);
        }
    }
}