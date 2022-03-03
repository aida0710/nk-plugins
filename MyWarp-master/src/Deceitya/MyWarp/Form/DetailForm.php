<?php

namespace Deceitya\MyWarp\Form;

use Deceitya\MyWarp\Database;
use pocketmine\form\Form;
use pocketmine\player\Player;

class DetailForm implements Form {

    private Player $player;

    public function __construct(Player $player) {
        $this->player = $player;
    }

    public function handleResponse(Player $player, $data): void {
    }

    public function jsonSerialize() {
        $form = [
            'type' => 'custom_form',
            'title' => 'MyWarp',
            'content' => []
        ];
        foreach (Database::getInstance()->getWarpPositions($this->player) as $warp) {
            $form['content'][] = [
                'type' => 'label',
                'text' => "> {$warp['name']}§r\nワールド ： {$warp['world']}\n作成時刻 ： {$warp['created_at']}\n座標 ： x. {$warp['x']} / y. {$warp['y']} / z. {$warp['z']}"
            ];
        }
        return $form;
    }
}
