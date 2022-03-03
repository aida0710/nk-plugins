<?php

namespace lazyperson710\core;

use pocketmine\scheduler\Task;
use pocketmine\Server;

class TaskScheduler extends Task {

    public function onRun(): void {
        foreach (Server::getInstance()->getWorldManager()->getWorlds() as $world) {
            switch ($world->getFolderName()) {
                case "lobby":
                case "resource":
                case "event-1":
                case "tos":
                    Server::getInstance()->getWorldManager()->getWorldByName($world->getFolderName())->setTime(18000);
                    Server::getInstance()->getWorldManager()->getWorldByName($world->getFolderName())->stopTime();
                    break;
                default:
                    Server::getInstance()->getWorldManager()->getWorldByName($world->getFolderName())->setTime(6000);
                    Server::getInstance()->getWorldManager()->getWorldByName($world->getFolderName())->stopTime();
                    break;
            }
        }
    }
}