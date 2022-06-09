<?php

namespace Deceitya\MyWarp\Form;

use Deceitya\MyWarp\Database;
use pocketmine\form\Form;
use pocketmine\player\Player;

class ListForm implements Form {

    private array $warps = [];

    public function __construct(Player $player) {
        foreach (Database::getInstance()->getWarpPositions($player) as $pos) {
            $this->warps[] = $pos['name'];
        }
    }

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
        $pos = Database::getInstance()->getWarpPMMPPosition($player, $this->warps[$data]);
        if ($pos !== null) {
            $player->teleport($pos);
        } else {
            $player->sendMessage('§bMyWarp §7>> §cワープ地点が見つかりませんでした');
        }
    }

    public function jsonSerialize() {
        $form = [
            'type' => 'form',
            'title' => 'MyWarp',
            'content' => '選択してください',
            'buttons' => []
        ];
        foreach ($this->warps as $pos) {
            $form['buttons'][] = ['text' => $pos];
        }
        return $form;
    }
}
