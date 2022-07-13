<?php

namespace Deceitya\BonusConvert\Form;

use Deceitya\BonusConvert\Convert;
use pocketmine\form\Form;
use pocketmine\player\Player;

class ConfirmForm implements Form {

    /** @var Convert */
    private $convert;
    /** @var int */
    private $index;

    public function __construct(Convert $convert, int $index) {
        $this->convert = $convert;
        $this->index = $index;
    }

    public function handleResponse(Player $player, $data): void {
        if (!$this->convert->playerCanConvert($player)) {
            $player->sendMessage('§bBonus §7>> §cログインボーナスが足りません。交換するにはインベントリにある必要があります。');
            return;
        }
        if ($data) {
            $this->convert->convert($player, $this->index);
            $item = $this->convert->getItems()[$this->index];
            $name = $item->getCustomName() === '' ? $item->getName() : $item->getCustomName();
            $player->sendMessage("§bBonus §7>> §aログインボーナス{$this->convert->getNeedCount()}個と{$name}を交換しました");
        }
    }

    public function jsonSerialize() {
        $item = $this->convert->getItems()[$this->index];
        $name = $item->getCustomName() === '' ? $item->getName() : $item->getCustomName();
        return [
            'type' => 'modal',
            'title' => 'LoginBonus',
            'content' => "ログインボーナス{$this->convert->getNeedCount()}個と{$name}を交換しますか？",
            'button1' => 'はい',
            'button2' => 'いいえ',
        ];
    }
}
