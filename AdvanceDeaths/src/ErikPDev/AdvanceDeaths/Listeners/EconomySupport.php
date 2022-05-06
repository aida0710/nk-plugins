<?php

namespace ErikPDev\AdvanceDeaths\Listeners;

use onebone\economyapi\EconomyAPI;
use pocketmine\event\Listener;
use pocketmine\player\Player;

class EconomySupport implements Listener {

    private $plugin;

    public function __construct($plugin) {
        $this->plugin = $plugin;
    }

    private function ModifyMoney(Player $player, $PlayerMoney, $OptionA) {
        if ($OptionA == "lose") {
            EconomyAPI::getInstance()->reduceMoney($player, $PlayerMoney);
        }
        if ($OptionA == "gain") {
            EconomyAPI::getInstance()->addMoney($player, $PlayerMoney);
        }
    }
}