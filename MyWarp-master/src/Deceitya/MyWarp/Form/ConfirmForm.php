<?php

namespace Deceitya\MyWarp\Form;

use Deceitya\MyWarp\Database;
use lazyperson710\core\packet\SendMessage;
use pocketmine\form\Form;
use pocketmine\player\Player;

class ConfirmForm implements Form {

    private Player $player;
    private string $target;

    public function __construct(Player $player, string $target) {
        $this->player = $player;
        $this->target = $target;
    }

    public function handleResponse(Player $player, $data): void {
        if (!$data) {
            return;
        }
        Database::getInstance()->removeWarpPosition($player, $this->target);
        SendMessage::Send($player, "ワープ地点を削除しました", "MyWarp", true);
    }

    public function jsonSerialize() {
        $warp = Database::getInstance()->getWarpPosition($this->player, $this->target);
        return [
            'type' => 'modal',
            'title' => 'MyWarp',
            'content' => "{$this->target}を本当に削除しますか？\n\n" . "{$warp['name']}\nワールド ： {$warp['world']}\n作成時刻 ： {$warp['created_at']}\n座標 ： x. {$warp['x']} / y. {$warp['y']} / z. {$warp['z']}",
            "button1" => 'はい',
            "button2" => 'いいえ',
        ];
    }
}