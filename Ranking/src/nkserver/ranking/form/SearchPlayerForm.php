<?php

declare(strict_types = 1);
namespace nkserver\ranking\form;

use nkserver\ranking\libs\form\CustomForm;
use nkserver\ranking\object\PlayerDataPool;
use pocketmine\form\Form;
use pocketmine\player\Player;

class SearchPlayerForm extends CustomForm {

    public function __construct() {
        $players = PlayerDataPool::getAllPlayers();
        $this->addDropdown('target', 'どのプレイヤーの統計を確認しますか?', 0, ...$players);
        $this->submit = function (Player $player, array $data) use ($players) {
            if (!isset($data['target'])) return;
            $player->sendForm(self::getStatistics((string)$players[$data['target']], $this));
        };
    }

    public static function getStatistics(string $target, ?Form $before = null): StatisticsForm {
        return new StatisticsForm($before, $target);
    }
}