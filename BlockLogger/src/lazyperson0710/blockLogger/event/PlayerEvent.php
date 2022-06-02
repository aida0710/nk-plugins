<?php

declare(strict_types=1);
namespace lazyperson0710\blockLogger\event;

use deceitya\miningtools\event\CountBlockEvent;
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

    ///**
    // * @priority MONITOR
    // * @param CountBlockEvent $event
    // */
    //public function onMiningBreak(CountBlockEvent $event) {
    //    var_dump($event->getBlockPosition());
    //    $this->checkLog($event, "MiningTools");
    //}
    private function checkLog(BlockBreakEvent|BlockPlaceEvent|CountBlockEvent $event, string $type): void {
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
        //var_dump($this->getBlockLogTemp());
        /*$cls = $this->main->getDatabase();
        if ($this->main->isOn($player)) {
            $message = $cls->checklog($event, $type);
            $player->sendTip($message);
            $event->cancel();
        } else {
            $cls->registerlog($event, $type);
        }*/
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

    public function refreshBlockLogTemp(): void {
        $this->blockLogTemp = [];
    }

    public static function getInstance(): PlayerEvent {
        return self::$playerEvent;
    }
}