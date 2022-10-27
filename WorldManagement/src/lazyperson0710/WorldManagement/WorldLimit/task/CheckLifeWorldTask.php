<?php

namespace lazyperson0710\WorldManagement\WorldLimit\task;

use lazyperson0710\WorldManagement\database\WorldCategory;
use lazyperson0710\WorldManagement\WorldLimit\WorldProperty;
use lazyperson710\core\packet\SendMessage;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class CheckLifeWorldTask extends Task {

    /** @var WorldProperty[] */
    private array $properties;

    /**
     * @param WorldProperty[] $properties
     */
    public function __construct(array $properties) {
        $this->properties = $properties;
    }

    /**
     * @inheritDoc
     */
    public function onRun(): void {
        foreach ($this->properties as $property) {
            $world = Server::getInstance()->getWorldManager()->getWorldByName($property->getWorldName());
            foreach ($world->getPlayers() as $player) {
                if (in_array($world->getFolderName(), WorldCategory::Nature) || in_array($world->getFolderName(), WorldCategory::MiningWorld) || in_array($world->getFolderName(), WorldCategory::Nether) || in_array($world->getFolderName(), WorldCategory::End)) return;
                if (!$property->inSafeArea($player->getPosition())) {
                    Server::getInstance()->dispatchCommand($player, "warp lobby");
                    SendMessage::Send($player, "範囲外に行こうとする試みは許可されていません", "WorldBorder", false);
                }
            }
        }
    }
}