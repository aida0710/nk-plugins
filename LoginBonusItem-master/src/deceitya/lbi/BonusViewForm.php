<?php

namespace deceitya\lbi;

use deceitya\lbi\libs\jojoe77777\formapi\ModalForm;
use pocketmine\player\Player;

class BonusViewForm extends ModalForm {

    public function __construct(Player $player) {
        parent::__construct(null);
        /** @var array<int, int> $data id => item count */
        $data = Main::$lastBonusDateConfig->get($player->getName());
        $itemdata = Main::getInstance()->getConfig()->getAll();
        $items = [];
        foreach ($data as $id => $count) {
            $item = Main::decodeItem($itemdata[$id]);
            $name = "アイテムid: " . $item->getId();
            if ($item->getMeta() !== 0) {
                $name .= ", ダメージ値: " . $item->getMeta();
            }
            $items[$name] = ($items[$name] ?? 0) + $count;//念の為
        }
        $result = "現在、受け取り可能なボーナスは下記の通りです。\n";
        foreach ($items as $name => $count) {
            $result .= $name . ", 個数: " . $count . "\n";
        }
        $this->setContent($result);
        $this->setButton1("受け取る");
        $this->setButton2("受け取らない");
    }

    public function handleResponse(Player $player, $data): void {
        if ($data === null || $data === false) {
            $player->sendMessage("§bLogin §7>> §aキャンセルしました！");
            return;
        }
        Main::check($player);
    }
}
