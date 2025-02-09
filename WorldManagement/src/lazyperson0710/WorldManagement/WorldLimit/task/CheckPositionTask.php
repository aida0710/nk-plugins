<?php

declare(strict_types = 0);

namespace lazyperson0710\WorldManagement\WorldLimit\task;

use lazyperson0710\WorldManagement\database\WorldCategory;
use lazyperson0710\WorldManagement\Main;
use lazyperson0710\WorldManagement\WorldLimit\WorldProperty;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\scheduler\Task;
use pocketmine\scheduler\TaskScheduler;
use pocketmine\Server;
use function in_array;

class CheckPositionTask extends Task {

    private TaskScheduler $scheduler;

    /** @var WorldProperty[] */
    private array $properties;

    /**
     * @param WorldProperty[] $properties
     */
    public function __construct(TaskScheduler $scheduler, array $properties) {
        $this->scheduler = $scheduler;
        $this->properties = $properties;
    }

    /**
     * @inheritDoc
     */
    public function onRun() : void {
        foreach ($this->properties as $property) {
            $world = Server::getInstance()->getWorldManager()->getWorldByName($property->getWorldName());
            if (in_array($world->getFolderName(), WorldCategory::Nature, true) || in_array($world->getFolderName(), WorldCategory::MiningWorld, true) || in_array($world->getFolderName(), WorldCategory::Nether, true) || in_array($world->getFolderName(), WorldCategory::End, true)) {
                foreach ($world->getPlayers() as $player) {
                    if (!$property->inSafeArea($player->getPosition())) {
                        SendMessage::Send($player, 'ワールドの上限を越えています。' . Main::TELEPORT_INTERVAL . "秒以内にセーフエリアに戻ってください\n§7>> §c戻らなかった場合、強制的にテレポートされます", 'WorldBorder', false);
                        $this->scheduler->scheduleDelayedTask(new PlayerTeleportTask($player, $property), Main::TELEPORT_INTERVAL * 20);
                    }
                }
            }
        }
    }
}
