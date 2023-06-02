<?php

declare(strict_types = 0);

namespace Deceitya\MiningLevel\Form;

use Deceitya\MiningLevel\MiningLevelAPI;
use pocketmine\form\Form;
use pocketmine\player\Player;

class RankForm implements Form {

    public function handleResponse(Player $player, $data) : void {
    }

    public function jsonSerialize() {
        $res = '';
        $ranking = MiningLevelAPI::getInstance()->genRanking();
        foreach ($ranking as $data) {
            $res .= "[{$data['rank']}位] {$data['name']}さん / {$data['level']}レベル\n";
        }
        return [
            'type' => 'custom_form',
            'title' => 'LevelRanking',
            'content' => [
                [
                    'type' => 'label',
                    'text' => $res,
                ],
            ],
        ];
    }
}
