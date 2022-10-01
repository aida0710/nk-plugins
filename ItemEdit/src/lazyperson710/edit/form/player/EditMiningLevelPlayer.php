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
                $this->upExp,
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
        //todo 有効かどうかの処理を書いてない為記述してください
        $input = false;
        $type = [
            "level" => $this->level,
            "exp" => $this->exp,
            "upExp" => $this->upExp,
        ];
        foreach ($type as $key => $value) {
            if (empty($value->getValue())) {
                continue;
            }
            $input = true;
            if ($output = $this->checkValue($key, $value->getValue())) {
                if ($output === true) {
                    continue;
                } else {
                    $player->sendMessage("§bPlayerEdit §7>> §c{$output}");
                    return;
                }
            }
        }
        if (!$input) {
            $player->sendMessage("§bPlayerEdit §7>> §c一つでも値を入力してください");
            return;
        }
        MiningLevelAPI::getInstance()->setLevel($player, $level);
        if ($player->getName() === $target->getName()) {
            $player->sendMessage("§bPlayerEdit §7>> §a経験値レベルを{$level}に設定しました");
        } else {
            $player->sendMessage("§bPlayerEdit §7>> §a経験値レベルを{$player->getName()}が{$level}に設定しました");
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