<?php

namespace lazyperson710\sff\form\police;

use bbo51dog\bboform\element\Dropdown;
use bbo51dog\bboform\form\CustomForm;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\Position;

class PlayerTeleportSelectForm extends CustomForm {

    private Dropdown $dropdown;

    public function __construct(Player $player) {
        $names = null;
        foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {
            $name = $onlinePlayer->getName();
            if (Server::getInstance()->isOp($name)) {
                continue;
            }
            if ($player->getName() === $name) {
                continue;
            }
            $names[] .= $name;
        }
        if (is_null($names)) {//こんなことは存在しないけど一応条件分岐だけ(上記のコメントアウトを消したら必要になります)
            $names[] .= "表示可能なプレイヤーが存在しません";
        }
        $this->dropdown = new Dropdown("テレポートしたいプレイヤーを選択してください\nテレポートにはスペクテイターモードである必要があります", $names);
        $this
            ->setTitle("Police System")
            ->addElement($this->dropdown);
    }

    public function handleSubmit(Player $player): void {
        $playerName = $this->dropdown->getSelectedOption();
        if (!Server::getInstance()->getPlayerByPrefix($playerName)) {
            $player->sendMessage("§bPolice §7>> §cプレイヤーが存在しない為、正常に座標を取得できませんでした");
            SoundPacket::Send($player, 'note.bass');
            return;
        }
        if ($player->isSpectator()) {
            $position = Server::getInstance()->getPlayerByPrefix($playerName)->getPosition();
            $pos = new Position($position->getX(), $position->getY(), $position->getZ(), $position->getWorld());
            Server::getInstance()->getLogger()->info("Police >> {$player->getName()}が{$playerName}にテレポートしました");
            $player->teleport($pos);
        } else {
            SendForm::Send($player, (new PoliceMainForm($player, "\n§cテレポートはスペクテイターモード時のみ使用可能です")));
            SoundPacket::Send($player, 'note.bass');
        }
    }
}