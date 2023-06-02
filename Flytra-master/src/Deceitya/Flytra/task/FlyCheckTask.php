<?php

declare(strict_types = 0);

namespace Deceitya\Flytra\task;

use Deceitya\Flytra\Main;
use lazyperson710\core\packet\SendMessage\SendMessage;
use lazyperson710\core\packet\SendNoSoundMessage\SendNoSoundActionBarMessage;
use lazyperson710\core\packet\SendNoSoundMessage\SendNoSoundTip;
use onebone\economyapi\EconomyAPI;
use pocketmine\item\Durable;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use function array_key_exists;
use function floor;
use function in_array;
use function is_null;

class FlyCheckTask extends Task {

    static array $flyTask = [];

    public function onRun() : void {
        foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {//エリトラの耐久を無限にする
            Main::getInstance()->checkFly($onlinePlayer, $onlinePlayer->getWorld(), $onlinePlayer->getArmorInventory()->getChestplate());
            $chest = $onlinePlayer->getArmorInventory()->getChestplate();
            if ($chest->getId() === ItemIds::ELYTRA) {
                if ($chest instanceof Durable) {
                    if ($chest->getDamage() !== 0) {
                        $chest->setDamage(0);
                        $chest->setUnbreakable(true);
                        $onlinePlayer->getArmorInventory()->setChestplate($chest);
                    }
                }
            }
        }
        if (empty(self::$flyTask)) {
            return;
        }
        foreach (self::$flyTask as $playerName => $list) {
            if (is_null(Server::getInstance()->getPlayerExact($playerName))) {
                unset(self::$flyTask[$playerName]);
            }
            $player = Server::getInstance()->getPlayerExact($playerName);
            Main::getInstance()->checkFly($player, $player->getWorld(), $player->getArmorInventory()->getChestplate());
            if (self::$flyTask[$player->getName()]['Flag'] === false) {
                $this->reduceMoney($player);
                if ($this->exists($player) === false) return;
                self::$flyTask[$player->getName()]['Flag'] = true;
                EconomyAPI::getInstance()->reduceMoney($player->getName(), 1500);
                SendNoSoundTip::Send($player, 'お金を1500円消費しました', 'FlyTask', true);
            }
            if ($list['Mode'] === 'limited') {
                $this->limited($player);
            } else {
                $this->unLimited($player);
            }
        }
    }

    public function reduceMoney(Player $player) {
        if (EconomyAPI::getInstance()->myMoney($player->getName()) < 1500) {
            unset(self::$flyTask[$player->getName()]);
            Main::getInstance()->checkFly($player, $player->getWorld(), $player->getArmorInventory()->getChestplate());
            SendMessage::Send($player, 'お金が足りないためfly機能が自動的に停止しました', 'FlyTask', false);
        }
    }

    public function exists(Player $player) : bool {
        return array_key_exists($player->getName(), self::$flyTask);
    }

    public function limited(Player $player) {
        --self::$flyTask[$player->getName()]['TimeLeft'];
        switch (self::$flyTask[$player->getName()]['TimeLeft']) {
            case 5:
            case 3:
            case 2:
            case 1:
                SendMessage::Send($player, 'FlyTask終了まで残り' . self::$flyTask[$player->getName()]['TimeLeft'] . '秒です', 'FlyTask', false);
                $this->checkTimeLeft($player);
                break;
            case 0:
                unset(self::$flyTask[$player->getName()]);
                Main::getInstance()->checkFly($player, $player->getWorld(), $player->getArmorInventory()->getChestplate());
                SendNoSoundActionBarMessage::Send($player, 'FlyTaskが正常に処理されました', 'FlyTask', true);
                SendMessage::Send($player, 'FlyTaskが終了しました', 'FlyTask', true);
                return;
            default:
                $this->checkTimeLeft($player);
        }
        if (self::$flyTask[$player->getName()]['TimeLeft'] % 60 === 0) {
            $this->reduceMoney($player);
            if ($this->exists($player) === false) return;
        }
    }

    public function checkTimeLeft(Player $player) {
        $minutes = self::$flyTask[$player->getName()]['TimeLeft'] / 60;
        $minutes = floor($minutes);
        $seconds = self::$flyTask[$player->getName()]['TimeLeft'] % 60;
        if (in_array($player->getWorld()->getFolderName(), Main::$worlds, true)) {
            SendNoSoundActionBarMessage::Send($player, "このワールドでは飛行を使用できません\n残り時間 - {$minutes}分{$seconds}秒", 'FlyTask', false);
        } else {
            SendNoSoundActionBarMessage::Send($player, "残り時間 - {$minutes}分{$seconds}秒", 'FlyTask', true);
        }
    }

    public function unLimited(Player $player) {
        ++self::$flyTask[$player->getName()]['TimeLeft'];
        if (self::$flyTask[$player->getName()]['TimeLeft'] === 60) {
            self::$flyTask[$player->getName()]['TimeLeft'] = 0;
            EconomyAPI::getInstance()->reduceMoney($player->getName(), 1500);
            if ($this->exists($player) === false) return;
            SendNoSoundActionBarMessage::Send($player, 'FlyTaskが正常に処理されました', 'FlyTask', true);
            SendNoSoundTip::Send($player, 'お金を1500円消費しました', 'FlyTask', true);
            $this->reduceMoney($player);
            if ($this->exists($player) === false) return;
        } elseif (in_array($player->getWorld()->getFolderName(), Main::$worlds, true)) {
            SendNoSoundActionBarMessage::Send($player, "このワールドでは飛行を使用できません\n次の料金発生まで - " . 60 - self::$flyTask[$player->getName()]['TimeLeft'] . '秒', 'FlyTask', false);
        } else {
            SendNoSoundActionBarMessage::Send($player, '次の料金発生まで - ' . 60 - self::$flyTask[$player->getName()]['TimeLeft'] . '秒', 'FlyTask', true);
        }
    }
}
