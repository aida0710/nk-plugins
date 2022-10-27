<?php

namespace lazyperson710\edit\form\player;

use bbo51dog\bboform\element\Dropdown;
use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\element\Toggle;
use bbo51dog\bboform\form\CustomForm;
use Deceitya\MiningLevel\MiningLevelAPI;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\player\Player;
use pocketmine\Server;

class SetMiningLevelPlayer extends CustomForm {

    private Dropdown $players;

    private Toggle $enableLevel;
    private Toggle $enableExp;
    private Toggle $enableUpExp;

    private Input $level;
    private Input $exp;
    private Input $upExp;

    public function __construct() {
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
        $this->exp = new Input("設定したい経験値を入力してください", "1");
        $this->upExp = new Input("設定したい次のレベルまでの経験値を入力してください", "80");
        $this
            ->setTitle("Player Edit")
            ->addElements(
                $this->players,
                new Label("設定したい項目を有効化してください\n有効化していない項目は数値を入力しても反映されません"),
                $this->enableLevel,
                $this->enableExp,
                $this->enableUpExp,
                new Label("設定した項目の数値を入力してください\n必ず1以上で入力してください"),
                $this->level,
                $this->exp,
                $this->upExp,
            );
    }

    public function handleSubmit(Player $player): void {
        $targetName = $this->players->getSelectedOption();
        if (!Server::getInstance()->getPlayerByPrefix($targetName)) {
            SendMessage::Send($player, "プレイヤーが存在しない為、処理を中断しました", "PlayerEdit", false);
            return;
        }
        $target = Server::getInstance()->getPlayerByPrefix($targetName);
        $level = $this->level->getValue();
        $exp = $this->exp->getValue();
        $upExp = $this->upExp->getValue();
        $input = false;
        $type = [
            "level" => $level,
            "exp" => $exp,
            "upExp" => $upExp,
        ];
        foreach ($type as $key => $value) {
            if (empty($value)) {
                match ($key) {
                    "level" => $level = false,
                    "exp" => $exp = false,
                    "upExp" => $upExp = false,
                };
                continue;
            }
            $input = true;
            $output = $this->checkValue($key, $value);
            if ($output === true) {
                continue;
            } else {
                SendMessage::Send($player, $output, "PlayerEdit", false);
                return;
            }
        }
        if (!$input) {
            SendMessage::Send($player, "最低一つは値を入力してください", "PlayerEdit", false);
            return;
        }
        if ($this->enableLevel->getValue() === true) {
            if ($level !== false) {
                MiningLevelAPI::getInstance()->setLevel($target, $level);
                if ($player->getName() === $target->getName()) {
                    SendMessage::Send($player, "レベルを{$level}に設定しました", "PlayerEdit", true);
                } else {
                    SendMessage::Send($player, "レベルを{$level}に設定しました", "PlayerEdit", true);
                    SendMessage::Send($target, "レベルを{$player->getName()}が{$level}に設定しました", "PlayerEdit", true);
                }
            }
        }
        if ($this->enableExp->getValue() === true) {
            if ($exp !== false) {
                MiningLevelAPI::getInstance()->setExp($target, $exp);
                if ($player->getName() === $target->getName()) {
                    SendMessage::Send($player, "経験値を{$exp}に設定しました", "PlayerEdit", true);
                } else {
                    SendMessage::Send($player, "経験値を{$exp}に設定しました", "PlayerEdit", true);
                    SendMessage::Send($target, "経験値を{$player->getName()}が{$exp}に設定しました", "PlayerEdit", true);
                }
            }
        }
        if ($this->enableUpExp->getValue() === true) {
            if ($upExp !== false) {
                MiningLevelAPI::getInstance()->setLevelUpExp($target, $upExp);
                if ($player->getName() === $target->getName()) {
                    SendMessage::Send($player, "次のレベルまでの経験値を{$upExp}に設定しました", "PlayerEdit", true);
                } else {
                    SendMessage::Send($player, "次のレベルまでの経験値を{$upExp}に設定しました", "PlayerEdit", true);
                    SendMessage::Send($target, "次のレベルまでの経験値を{$player->getName()}が{$upExp}に設定しました", "PlayerEdit", true);
                }
            }
        }
    }

    private function checkValue(string $key, string $value): bool|string {
        $type = match ($key) {
            "level" => "レベル",
            "exp" => "現在の経験値",
            "upExp" => "次のレベルまでの経験値",
        };
        if (!is_numeric($value)) {
            return "{$type}入力では数字以外の文字列を入力しないでください";
        }
        $value = (int)$value;
        if ($value < 0) {
            return "{$type}入力では0以上の数値で入力してください";
        }
        if ($value > 15000) {
            return "{$type}入力では15000以下の数値で入力してください";
        }
        return true;
    }

}