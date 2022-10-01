<?php
namespace lazyperson710\edit\form\player;

use bbo51dog\bboform\element\Dropdown;
use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\form\CustomForm;
use pocketmine\player\Player;
use pocketmine\Server;

class AddEffectPlayer extends CustomForm {

    private Dropdown $players;
    private Input $level;

    public function __construct(Player $player) {
        $names = null;
        foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {
            $name = $onlinePlayer->getName();
            $names[] .= $name;
        }
        $this->players = new Dropdown("プレイヤーを選択してください", $names);
        $this->level = new Input("設定したいレベル値を入力してください", "1");
        $this
            ->setTitle("Player Edit")
            ->addElements(
                $this->players,
                $this->level,
            );
    }

    public function handleSubmit(Player $player): void {
        $players = $this->players->getSelectedOption();
        $expLevel = $this->level->getValue();
        if (!Server::getInstance()->getPlayerByPrefix($players)) {
            $player->sendMessage("§bPlayerEdit §7>> §cプレイヤーが存在しない為、処理を中断しました");
            return;
        }
        $target = Server::getInstance()->getPlayerByPrefix($players);
        if (!is_numeric($expLevel)) {
            $player->sendMessage("§bPlayerEdit §7>> §c経験値量は数値で入力してください");
            return;
        }
        if ($expLevel < 0) {
            $player->sendMessage("§bPlayerEdit §7>> §c経験値レベルは0以上の数値で入力してください");
            return;
        }
        if ($expLevel > 1241258) {
            $player->sendMessage("§bPlayerEdit §7>> §c経験値レベルは1241258以下の数値で入力してください");
            return;
        }
        $target->getXpManager()->setXpLevel($expLevel);
        if ($player->getName() === $target->getName()) {
            $player->sendMessage("§bPlayerEdit §7>> §a経験値レベルを{$expLevel}に設定しました");
        } else {
            $player->sendMessage("§bPlayerEdit §7>> §a経験値レベルを{$player->getName()}が{$expLevel}に設定しました");
        }
    }

}