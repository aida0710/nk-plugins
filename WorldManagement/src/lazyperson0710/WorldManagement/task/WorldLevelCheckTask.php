<?php

declare(strict_types = 0);

namespace lazyperson0710\WorldManagement\task;

use Deceitya\MiningLevel\MiningLevelAPI;
use lazyperson0710\WorldManagement\database\WorldManagementAPI;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class WorldLevelCheckTask extends Task {

    public function onRun() : void {
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            $worldApi = WorldManagementAPI::getInstance();
            if (MiningLevelAPI::getInstance()->getLevel($player) < $worldApi->getMiningLevelLimit($player->getWorld()->getFolderName())) {
                Server::getInstance()->dispatchCommand($player, 'warp lobby');
                SendMessage::Send($player, '移動した先のワールドはまだ解放されていない為ロビーに戻されました', 'World', false);
            }
        }
    }

}
