<?php

declare(strict_types=1);
namespace lazyperson0710\blockLogger\event;

use deceitya\miningtools\event\MiningToolsBreakEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;

class PlayerEvent implements Listener {

    public array $blockLogTemp = [];

    private static PlayerEvent $playerEvent;

    public function __construct() {
        self::$playerEvent = $this;
    }

    /**
     * @priority MONITOR
     * @param BlockBreakEvent $event
     */
    public function onBreak(BlockBreakEvent $event) {
        $this->checkLog($event, "Break");
    }

    /**
     * @priority MONITOR
     * @param BlockPlaceEvent $event
     */
    public function onPlace(BlockPlaceEvent $event) {
        $this->checkLog($event, "Place");
    }

    /**
     * @priority MONITOR
     * @param MiningToolsBreakEvent $event
     */
    public function countBlock(MiningToolsBreakEvent $event) {
        $this->checkLog($event, "MiningTools");
    }

    /**
     * @param BlockBreakEvent|BlockPlaceEvent|MiningToolsBreakEvent $event
     * @param string $type
     * @return void
     */
    private function checkLog(BlockBreakEvent|BlockPlaceEvent|MiningToolsBreakEvent $event, string $type): void {
        $position = $event->getBlock()->getPosition()->asPosition();
        $date = date("Y/m/d");
        $time = date("H:i:s");
        $player = $event->getPlayer();
        $log[] = [
            "name" => $player->getName(),
            "type" => $type,
            "world" => $position->getWorld()->getFolderName(),
            "x" => $position->getFloorX(),
            "y" => $position->getFloorY(),
            "z" => $position->getFloorZ(),
            "blockName" => $event->getBlock()->getName(),
            "blockId" => $event->getBlock()->getId(),
            "blockMeta" => $event->getBlock()->getMeta(),
            "date" => $date,
            "time" => $time
        ];
        $this->setBlockLogTemp($log);
    }

    /**
     * @return array
     */
    public function getBlockLogTemp(): array {
        return $this->blockLogTemp;
    }

    /**
     * @param array $blockLogTemp
     */
    public function setBlockLogTemp(array $blockLogTemp): void {
        $blockLogTemp = array_merge($blockLogTemp, $this->blockLogTemp);
        $this->blockLogTemp = $blockLogTemp;
    }

    /**
     * @return void
     */
    public function refreshBlockLogTemp(): void {
        $this->blockLogTemp = [];
    }

    /**
     * @return PlayerEvent
     */
    public static function getInstance(): PlayerEvent {
        return self::$playerEvent;
    }
}