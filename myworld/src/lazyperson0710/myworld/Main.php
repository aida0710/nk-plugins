<?php

namespace lazyperson0710\myworld;

use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class Main extends PluginBase {
    public function onEnable(): void {
        foreach (Server::getInstance()->getWorldManager() as $world){
            if (){
                //todo このプラグインで生成されたワールドをわざわざ意味もなくlogに出力
                Server::getInstance()->getLogger()->info($CreateWorld);
            }
        }
    }
}