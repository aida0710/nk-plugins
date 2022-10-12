<?php

namespace Deceitya\ChestLock\Form;

use Deceitya\ChestLock\Main;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\form\Form;
use pocketmine\player\Player;

class ModeForm implements Form {

    private Main $plugin;

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
        if ($data === 3) {
            $this->plugin->removeStat($player);
            return;
        }
        $this->plugin->setStat($player, $data);
        $player->sendMessage('§bChestLock §7>> §aチェストをタップして下さい。');
        SoundPacket::Send($player, 'note.harp');
    }

    public function jsonSerialize() {
        return [
            'type' => 'form',
            'title' => 'ChestLock Menu',
            'content' => '選択してください',
            'buttons' => [
                [
                    'text' => 'チェストをロック',
                ],
                [
                    'text' => 'チェストのロックを解除',
                ],
                [
                    'text' => 'ロックされたチェストの情報を見る',
                ],
                [
                    'text' => '選択を解除する',
                ],
            ],
        ];
    }
}
