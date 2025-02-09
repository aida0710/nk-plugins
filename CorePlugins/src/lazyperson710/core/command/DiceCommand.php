<?php

declare(strict_types = 0);

namespace lazyperson710\core\command;

use lazyperson0710\PlayerSetting\object\PlayerSettingPool;
use lazyperson0710\PlayerSetting\object\settings\normal\DiceMessageSetting;
use lazyperson710\core\packet\SendMessage\SendMessage;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use function array_unique;
use function mt_rand;
use function preg_match;

class DiceCommand extends Command {

    public function __construct() {
        parent::__construct('dice', '指定した範囲で乱数を発生させます');
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void {
        if (!($sender instanceof Player)) {
            $sender->sendMessage('サーバー内で実行してください');
            return;
        }
        if (PlayerSettingPool::getInstance()->getSettingNonNull($sender)->getSetting(DiceMessageSetting::getName())?->getValue() !== true) {
            SendMessage::Send($sender, 'DiceMessageSettingがOFFになっている為、コマンドを実行できません', 'Dice', false);
            SendMessage::Send($sender, '/settingから設定を変更が可能です', 'Dice', false);
            return;
        }
        if (!isset($args[0])) {
            SendMessage::Send($sender, '使い方：/dice <範囲の最小値> <範囲の最大値> <連続で回す回数指定> <当たりを指定(無制限)>', 'Dice', false);
            return;
        }
        if (!isset($args[1])) {
            SendMessage::Send($sender, '使い方：/dice <範囲の最小値> <範囲の最大値> <連続で回す回数指定> <当たりを指定(無制限)>', 'Dice', false);
            return;
        }
        if (!preg_match('/^[0-9]+$/', $args[0])) {
            SendMessage::Send($sender, '整数のみが使用可能です', 'Dice', false);
            return;
        } elseif ($args[0] >= 10000) {
            SendMessage::Send($sender, '10,000以上の数字は使用することが出来ません', 'Dice', false);
            return;
        }
        if (!preg_match('/^[0-9]+$/', $args[1])) {
            SendMessage::Send($sender, '整数のみが使用可能です', 'Dice', false);
            return;
        } elseif ($args[1] >= 10000) {
            SendMessage::Send($sender, '10,000以上の数字は使用することが出来ません', 'Dice', false);
            return;
        }
        $user = $sender->getName();
        if ($args[0] > $args[1]) {
            SendMessage::Send($sender, "2つ目の引数を1つ目の引数より大きくすることはできません -> {$args[0]} > {$args[1]}", 'Dice', false);
        } elseif (!isset($args[2])) {//連続で回す数が指定されてなかったら
            SoundPacket::Send($sender, 'note.harp');
            $rand = mt_rand($args[0], $args[1]);
            foreach (Server::getInstance()->getOnlinePlayers() as $player) {
                if (PlayerSettingPool::getInstance()->getSettingNonNull($player)->getSetting(DiceMessageSetting::getName())?->getValue() === true) {
                    SendMessage::Send($player, 'ダイス結果 | ' . $rand . ' - プレイヤー | ' . $user . ' - 範囲 | ' . $args[0] . '~' . $args[1], 'Dice', true);
                }
            }
            Server::getInstance()->getLogger()->notice('§bDice §7>> §aダイス結果 | ' . $rand . ' - プレイヤー | ' . $user . ' - 範囲 | ' . $args[0] . '~' . $args[1]);
        } else {//連続で回す数が指定されていたら
            if (!preg_match('/^[0-9]+$/', $args[2])) {
                SendMessage::Send($sender, '整数のみが使用可能です', 'Dice', false);
                return;
            }
            if ($args[2] >= 10) {
                SendMessage::Send($sender, '10以上の数字は引数3では使用することが出来ません', 'Dice', false);
                return;
            }
            if (!isset($args[3])) {//当たり番号が指定されてなかったら
                SoundPacket::Send($sender, 'note.harp');
                for ($i = 1; $i <= $args[2]; $i++) {
                    $rand = mt_rand($args[0], $args[1]);
                    foreach (Server::getInstance()->getOnlinePlayers() as $player) {
                        if (PlayerSettingPool::getInstance()->getSettingNonNull($player)->getSetting(DiceMessageSetting::getName())?->getValue() === true) {
                            SendMessage::Send($player, "結果 -> {$rand} - {$i}回 - Player " . $user . ' - 範囲 ' . $args[0] . '~' . $args[1], 'Dice', true);
                        }
                    }
                    Server::getInstance()->getLogger()->notice("§bDice §7>> §a結果 -> {$rand} - {$i}回 - Player " . $user . ' - 範囲 ' . $args[0] . '~' . $args[1]);
                }
            } else {
                $number = $args[2];
                $min = $args[0];
                $max = $args[1];
                $count = 1;
                unset($args[0], $args[1], $args[2]);
                $result = array_unique($args);
                SoundPacket::Send($sender, 'note.harp');
                for ($i = 1; $i <= $number; $i++) {
                    $rand = mt_rand($min, $max);
                    foreach ($result as $lucky) {
                        if ((int) $lucky === $rand) {
                            $count++;
                            foreach (Server::getInstance()->getOnlinePlayers() as $player) {
                                if (PlayerSettingPool::getInstance()->getSettingNonNull($player)->getSetting(DiceMessageSetting::getName())?->getValue() === true) {
                                    SendMessage::Send($player, "結果(当たりのみ) -> {$rand} - {$i}回 - Player " . $user . ' - 範囲 ' . $min . '~' . $max, 'Dice', true);
                                }
                            }
                            Server::getInstance()->getLogger()->notice("§bDice §7>> §e結果(当たりのみ) -> {$rand} - {$i}回 - Player " . $user . ' - 範囲 ' . $min . '~' . $max);
                        }
                    }
                }
                if ($count === 1) {
                    foreach (Server::getInstance()->getOnlinePlayers() as $player) {
                        if (PlayerSettingPool::getInstance()->getSettingNonNull($player)->getSetting(DiceMessageSetting::getName())?->getValue() === true) {
                            SendMessage::Send($player, '結果(当たりのみ) -> 当たり無し - Player ' . $user . ' - 範囲 ' . $min . '~' . $max, 'Dice', true);
                        }
                    }
                    Server::getInstance()->getLogger()->notice('§bDice §7>> §e結果(当たりのみ) -> 当たり無し - Player ' . $user . ' - 範囲 ' . $min . '~' . $max);
                }
            }
        }
    }
}
