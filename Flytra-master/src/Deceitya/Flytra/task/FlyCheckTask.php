<?php

namespace Deceitya\Flytra\task;

use Deceitya\Flytra\Main;
use onebone\economyapi\EconomyAPI;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class FlyCheckTask extends Task {

    static array $flyTask = [];

    public function onRun(): void {
        if (empty(self::$flyTask)) {
            return;
        }
        foreach (self::$flyTask as $playerName => $list) {
            if (is_null(Server::getInstance()->getPlayerExact($playerName))) {
                unset(self::$flyTask[$playerName]);
            }
            $player = Server::getInstance()->getPlayerExact($playerName);
            Main::getInstance()->checkFly($player, $player->getWorld(), $player->getArmorInventory()->getChestplate());
            if (self::$flyTask[$player->getName()]["Flag"] === false) {
                $this->reduceMoney($player);
                if ($this->exists($player) === false) return;
                self::$flyTask[$player->getName()]["Flag"] = true;
                EconomyAPI::getInstance()->reduceMoney($player->getName(), 1500);
                $player->sendMessage("§bFlyTask §7>> §aお金を1500円消費しました");
            }
            if ($list["Mode"] === "limited") {
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
            $player->sendMessage("§bFlyTask §7>> §cお金が足りないためfly機能が自動的に停止しました");
        }
    }

    public function exists(Player $player): bool {
        return array_key_exists($player->getName(), self::$flyTask);
    }

    public function limited(Player $player) {
        --self::$flyTask[$player->getName()]["TimeLeft"];
        switch (self::$flyTask[$player->getName()]["TimeLeft"]) {
            case 5:
            case 3:
            case 2:
            case 1:
                $player->sendMessage("§bFlyTask §7>> §aFlyTask終了まで残り" . self::$flyTask[$player->getName()]["TimeLeft"] . "秒です");
                $this->checkTimeLeft($player);
                break;
            case 0:
                unset(self::$flyTask[$player->getName()]);
                Main::getInstance()->checkFly($player, $player->getWorld(), $player->getArmorInventory()->getChestplate());
                $player->sendActionBarMessage("§bFlyTask §7>> §a正常に処理されました");
                $player->sendMessage("§bFlyTask §7>> §aFlyTaskが終了しました");
                return;
            default:
                $this->checkTimeLeft($player);
        }
        if (self::$flyTask[$player->getName()]["TimeLeft"] % 60 === 0) {
            $this->reduceMoney($player);
            if ($this->exists($player) === false) return;
        }
    }

    public function checkTimeLeft(Player $player) {
        $minutes = self::$flyTask[$player->getName()]["TimeLeft"] / 60;
        $minutes = floor($minutes);
        $seconds = self::$flyTask[$player->getName()]["TimeLeft"] % 60;
        if (in_array($player->getWorld()->getFolderName(), Main::$worlds)) {
            $player->sendActionBarMessage("§bFlyTask §7>> §cこのワールドでは飛行を使用できません\n残り時間 - {$minutes}分{$seconds}秒");
        } else {
            $player->sendActionBarMessage("§bFlyTask §7>> §a残り時間 - {$minutes}分{$seconds}秒");
        }
    }

    public function unLimited(Player $player) {
        ++self::$flyTask[$player->getName()]["TimeLeft"];
        if (self::$flyTask[$player->getName()]["TimeLeft"] === 60) {
            self::$flyTask[$player->getName()]["TimeLeft"] = 0;
            EconomyAPI::getInstance()->reduceMoney($player->getName(), 1500);
            if ($this->exists($player) === false) return;
            $player->sendActionBarMessage("§bFlyTask §7>> §a正常に処理されました");
            $player->sendMessage("§bFlyTask §7>> §aお金を1500円消費しました");
            $this->reduceMoney($player);
            if ($this->exists($player) === false) return;
        } elseif (in_array($player->getWorld()->getFolderName(), Main::$worlds)) {
            $player->sendActionBarMessage("§bFlyTask §7>> §cこのワールドでは飛行を使用できません\n次の料金発生まで - " . 60 - self::$flyTask[$player->getName()]["TimeLeft"] . "秒");
        } else {
            $player->sendActionBarMessage("§bFlyTask §7>> §a次の料金発生まで - " . 60 - self::$flyTask[$player->getName()]["TimeLeft"] . "秒");
        }
    }
}