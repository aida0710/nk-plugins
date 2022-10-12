<?php

namespace lazyperson710\edit\form\player;

use bbo51dog\bboform\element\Dropdown;
use bbo51dog\bboform\form\CustomForm;
use lazyperson0710\PlayerSetting\form\SelectSettingForm;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\player\Player;
use pocketmine\Server;

class EditSettingPlayer extends CustomForm {

    private Dropdown $players;

    public function __construct() {
        $names = null;
        foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {
            $name = $onlinePlayer->getName();
            $names[] .= $name;
        }
        $this->players = new Dropdown("設定を変更したいプレイヤーを選択してください", $names);
        $this
            ->setTitle("Player Edit")
            ->addElements(
                $this->players,
            );
    }

    public function handleSubmit(Player $player): void {
        $targetName = $this->players->getSelectedOption();
        if (!Server::getInstance()->getPlayerByPrefix($targetName)) {
            $player->sendMessage("§bPlayerEdit §7>> §cプレイヤーが存在しない為、処理を中断しました");
            SoundPacket::Send($player, 'note.bass');
            return;
        }
        $target = Server::getInstance()->getPlayerByPrefix($targetName);
        SendForm::Send($player, new SelectSettingForm($target));
    }

}