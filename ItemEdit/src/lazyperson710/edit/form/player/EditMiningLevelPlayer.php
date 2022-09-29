<?php

namespace lazyperson710\edit\form\player;

use bbo51dog\bboform\element\Dropdown;
use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\element\Toggle;
use bbo51dog\bboform\form\CustomForm;
use Deceitya\MiningLevel\MiningLevelAPI;
use pocketmine\player\Player;
use pocketmine\Server;

class EditMiningLevelPlayer extends CustomForm {

    private Dropdown $players;

    private Toggle $enableLevel;
    private Toggle $enableExp;
    private Toggle $enableUpExp;

    private Input $level;
    private Input $exp;
    private Input $upExp;

    public function __construct(Player $player) {
        $names = null;
        foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {
            $name = $onlinePlayer->getName();
            $names[] .= $name;
        }
        $this->players = new Dropdown("プレイヤーを選択してください", $names);
        $this->enableLevel = new Toggle("レベルの設定を有効化する", false);
        $this->enableExp = new Toggle("経験値の設定を有効化する", false);
        $this->enableUpExp = new Toggle("次のレベルまでの経験値の設定を有効化する", false);
        $this->level = new Input("設定したいレベル値を入力してください", "1");
        $this->exp = new Input("設定したい経験値を入力してください", "0");
        $this->upExp = new Input("設定したい次のレベルまでの経験値を入力してください", "80");
        $this
            ->setTitle("Player Edit")
            ->addElements(
                $this->players,
                new Label("設定したい項目を有効化してください\n有効化していない項目は数値を入力しても反映されません"),
                $this->enableLevel,
                $this->enableExp,
                $this->enableUpExp,
                new Label("設定した項目の数値を入力してください"),
                $this->level,
                $this->exp,
                $this->upExp
            );
    }

    public function handleSubmit(Player $player): void {
        $players = $this->players->getSelectedOption();
        $level = $this->level->getValue();
        if (!Server::getInstance()->getPlayerByPrefix($players)) {
            $player->sendMessage("§bPlayerEdit §7>> §cプレイヤーが存在しない為、処理を中断しました");
            return;
        }
        $target = Server::getInstance()->getPlayerByPrefix($players);
        if (!is_numeric($level)) {
            $player->sendMessage("§bPlayerEdit §7>> §cマイニングレベルは数値で入力してください");
            return;
        }
        if ($level < 0) {
            $player->sendMessage("§bPlayerEdit §7>> §cマイニングレベルは0以上の数値で入力してください");
            return;
        }
        if ($level > 5000) {
            $player->sendMessage("§bPlayerEdit §7>> §cマイニングレベルは5000以下の数値で入力してください");
            return;
        }
        MiningLevelAPI::getInstance()->setLevel($player, $level);
        if ($player->getName() === $target->getName()) {
            $player->sendMessage("§bPlayerEdit §7>> §a経験値レベルを{$level}に設定しました");
        } else {
            $player->sendMessage("§bPlayerEdit §7>> §a経験値レベルを{$player->getName()}が{$level}に設定しました");
        }
    }

    private function checkValue(string $value): string|bool {
        if (!is_numeric($value)) {
            return "数値で入力してください";
        }
        if (!is_int($value)) {
            return "整数で入力してください";
        }
        if ($value < 0) {
            return "0以上の数値で入力してください";
        }
        if ($value > 15000) {
            return "15000以下の数値で入力してください";
        }
        return true;
    }

}