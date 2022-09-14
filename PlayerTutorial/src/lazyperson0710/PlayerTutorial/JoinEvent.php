<?php

namespace lazyperson0710\PlayerSetting;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class JoinEvent implements Listener {

    /**
     * @param PlayerJoinEvent $event
     * @return void
     */
    public function onJoin(PlayerJoinEvent $event): void{
        $saveData[$event->getPlayer()->getName()] = [
            "tutorial" => [//todo 進行度 飛ばした場合は全部trueに
            ],
            "explanation" => [
                "miningTools" => [

                ]
            ],

        ];
    }

}