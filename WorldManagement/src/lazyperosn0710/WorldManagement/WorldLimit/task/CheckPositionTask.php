<?php

namespace lazyperson0710\WorldManagement\WorldLimit\task;

use lazyperson0710\WorldManagement\Main;
use lazyperson0710\WorldManagement\WorldLimit\WorldProperty;
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
                    $player->sendMessage("§bWorldBorder §7>> §cワールドの上限を越えています。" . Main::TELEPORT_INTERVAL . "秒以内にセーフエリアに戻ってください\n§7>> §c戻らなかった場合、強制的にテレポートされます");
                    $this->scheduler->scheduleDelayedTask(new PlayerTeleportTask($player, $property), Main::TELEPORT_INTERVAL * 20);
                }
            }
        }
    }
}