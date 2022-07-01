<?php

namespace bbo51dog\limitedworld\task;

use bbo51dog\limitedworld\Main;
use bbo51dog\limitedworld\WorldProperty;
use pocketmine\scheduler\Task;
use pocketmine\scheduler\TaskScheduler;
use pocketmine\Server;
use pocketmine\world\World;

class CheckPositionTask extends Task {

    private TaskScheduler $scheduler;

    /** @var WorldProperty[] */
    private array $properties;

    /**
     * @param TaskScheduler $scheduler
     * @param WorldProperty[] $properties
     */
    public function __construct(TaskScheduler $scheduler, array $properties) {
        $this->scheduler = $scheduler;
        $this->properties = $properties;
    }

    /**
     * @inheritDoc
     */
    public function onRun(): void {
        foreach ($this->properties as $property) {
            $world = Server::getInstance()->getWorldManager()->getWorldByName($property->getWorldName());
            if (!$world instanceof World) {
                continue;
            }
            foreach ($world->getPlayers() as $player) {
                if (!$property->inSafeArea($player->getPosition())) {
                    $player->sendMessage("§bWorldBorder §7>> §cワールドの上限を越えています。" . Main::TELEPORT_INTERVAL . "秒以内にセーフエリアに戻ってください\n戻らなかった場合、強制的にテレポートされます");
                    $this->scheduler->scheduleDelayedTask(new PlayerTeleportTask($player, $property), Main::TELEPORT_INTERVAL * 20);
                }
            }
        }
    }
}