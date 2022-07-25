<?php

namespace lazyperson0710\WorldManagement\task;

use Deceitya\MiningLevel\MiningLevelAPI;
use lazyperson0710\WorldManagement\database\WorldManagementAPI;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class WorldLevelCheckTask extends Task {

    public function onRun(): void {
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            if (Server::getInstance()->getPlayerExact($player->getName()) === null) return;
            $worldApi = WorldManagementAPI::getInstance();
            if (MiningLevelAPI::getInstance()->getLevel($player) < $worldApi->getMiningLevelLimit($player->getWorld()->getFolderName())) {
                $player->sendMessage("§bWorld §7>> §c移動した先のワールドはまだ解放されていない為ロビーに戻されました");
                Server::getInstance()->dispatchCommand($player, "warp lobby");
            }
        }
    }

}