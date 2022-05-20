<?php

declare(strict_types=1);
namespace space\yurisi\Event;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use space\yurisi\SimpleLogger;

class PlayerEvent implements Listener {

    /** @var SimpleLogger */
    private SimpleLogger $main;

    public function __construct(SimpleLogger $main) {
        $this->main = $main;
    }

    /**
     * @priority MONITOR
     * @param BlockBreakEvent $event
     */
    public function onBreak(BlockBreakEvent $event) {
        $this->checkLog($event, "b");
    }

    private function checkLog(BlockBreakEvent|BlockPlaceEvent $event, string $eventType) {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $x = $block->getPosition()->getFloorX();
        $y = $block->getPosition()->getFloorY();
        $z = $block->getPosition()->getFloorZ();
        $world = $block->getPosition()->getWorld()->getFolderName();
        $cls = $this->main->getDB();
        if ($this->main->isOn($player)) {
            $cls->checklog($x, $y, $z, $world, $player);
            $event->cancel();
        } else {
            $id = $block->getId();
            $meta = $block->getMeta();
            $cls->registerlog($x, $y, $z, $world, $id, $meta, $player, $eventType);
        }
        return true;
    }

    /**
     * @priority MONITOR
     * @param BlockPlaceEvent $event
     */
    public function onPlace(BlockPlaceEvent $event) {
        $this->checkLog($event, "p");
    }
}