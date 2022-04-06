<?php

namespace lazyperson710\sff\form;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\form\SimpleForm;
use Deceitya\MiningLevel\MiningLevelAPI;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\Server;

class PlayerForm extends SimpleForm {

    private string $playerName;

    public function __construct(Player $player, string $playerName) {
        $this->playerName = $playerName;
        if (Server::getInstance()->getPlayerByPrefix($playerName)) {
            $player = Server::getInstance()->getPlayerByPrefix($playerName);
            $effectIds = null;
            foreach ($player->getEffects()->all() as $effect) {
                $effectName = $effect->getType()->getName();
                $effectLevel = $effect->getEffectLevel();
                if (is_string($effectName)) {
                    $effectIds .= "{$effectName} Lv.{$effectLevel}, ";
                } else {
                    $effectTranslate = Server::getInstance()->getLanguage()->translate($effectName);
                    $effectIds .= "{$effectTranslate} Lv.{$effectLevel},　";
                }
            }
            if (is_null($effectIds)) {
                $effectIds = "なし";
            }
            $position = $player->getPosition();
            $playerPosition = "X, {$position->getFloorX()} Y, {$position->getFloorY()} Z, {$position->getFloorZ()} \nWorld : {$position->getWorld()->getDisplayName()}";
            $date = date("Y/m/d - H:i:s");
            $life = "{$player->getHealth()}/{$player->getMaxHealth()}";
            $gameMode = match ($player->getGamemode()) {
                GameMode::SURVIVAL() => "Survival",
                GameMode::CREATIVE() => "Creative",
                GameMode::ADVENTURE() => "Adventure",
                GameMode::SPECTATOR() => "Spectator",
                default => "例外が発生しました",
            };
            $miningLevel = MiningLevelAPI::getInstance()->getLevel($playerName);
            $miningExp = MiningLevelAPI::getInstance()->getExp($playerName);
            $miningLevelUpExp = MiningLevelAPI::getInstance()->getLevelUpExp($playerName);
            $progress = ($miningExp / $miningLevelUpExp) * 100;
            $progress = floor($progress);
        } else {
            $player->sendMessage("§bPlayerInfo §7>> §cプレイヤーが存在しない為、正常にformを送信できませんでした");
            return;
        }
        $this
            ->setTitle("Player info")
            ->setText("§l{$player->getName()}の情報§r\n\nCoordinate : $playerPosition\n\nLife $life - Mode {$gameMode}\nMiningLevel Lv.{$miningLevel}, Exp.{$miningExp}, LevelUpExp.{$miningLevelUpExp} / Progress:{$progress}％\n\n現在付与されているエフェクト\n{$effectIds}\n\n最終更新時刻\n{$date}")
            ->addElements(new Button("情報を更新"));
    }

    public function handleSubmit(Player $player): void {
        if (!Server::getInstance()->getPlayerByPrefix($this->playerName)) {
            $player->sendMessage("§bPlayerInfo §7>> §cプレイヤーが存在しない為、正常にformを送信できませんでした");
            return;
        }
        $player->sendForm(new PlayerForm($player, $this->playerName));
    }
}