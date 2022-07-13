<?php

namespace Yomogisystem;

use Deceitya\MiningLevel\MiningLevelAPI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\world\Position;

class Really extends PluginBase implements Listener {

    public function onEnable(): void {
        if (!file_exists($this->getDataFolder())) {
            mkdir($this->getDataFolder(), 0755, true);
        }
        $this->warp = new Config($this->getDataFolder() . "WarpPoint.yml", Config::YAML, []);
    }//Enable終了

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("§bWarp §7>> §cこのコマンドはゲーム内でしか実行できません!!");
            return true;
        }
        switch (strtolower($command->getName())) {
            case "warp":
                if (!isset($args[0])) {
                    $sender->sendMessage("§bWarp §7>> §cpointを設定してください");
                    return true;
                }
                $name = $this->ad($args[0]);
                if ($name !== false) {
                    $x = $this->warp->getAll()[$name]["X"];
                    $y = $this->warp->getAll()[$name]["Y"];
                    $z = $this->warp->getAll()[$name]["Z"];
                    $point = $this->warp->getAll()[$name]["world"];
                    $mining = $this->warp->getAll()[$name]["mining"];
                    if (Server::getInstance()->getWorldManager()->isWorldLoaded($point)) {//レベルオブジェクトかを条件分岐
                        if (MiningLevelAPI::getInstance()->getLevel($sender->getName()) >= (int)$mining) {
                            $world = $this->getServer()->getWorldManager()->getWorldByName($point);
                            $pos = new Position($x, $y, $z, $world);
                            $sender->teleport($pos);
                            $sender->sendTip("§bWarp §7>> §a{$name}にワープしました");
                        } else {
                            $sender->sendMessage("§bWarp §7>> §c{$point}にはレベルが足りないため移動できませんでした。要求レベル->{$mining}");
                            return true;
                        }
                    } else {
                        $sender->sendMessage("§bWarp §7>> §c{$point}には移動出来ませんでした");
                        return true;
                    }
                } else {
                    $sender->sendMessage("§bWarp §7>> §c{$args[0]}という名前のワープ地点は存在しない、あるいは権限が不足しています");
                    return true;
                }
                break;
            case "warpset":
                if (!Server::getInstance()->isOp($sender->getName())) {
                    $sender->sendMessage("§bWarp §7>> §c権限が不足している為、実行できませんでした");
                    return true;
                }
                if (!isset($args[0])) {
                    $sender->sendMessage("§bWarp §7>> §cpointを設定してください");
                    return true;
                }
                if (!isset($args[1])) {
                    $sender->sendMessage("§bWarp §7>> §clevelを指定してください");
                    return true;
                }
                if (stripos($args[0], "§") === false) {
                    if (!$this->warp->exists($args[0])) {
                        $world = $sender->getWorld();
                        $wname = $world->getFolderName();
                        $x = $sender->getPosition()->getFloorX();
                        $y = $sender->getPosition()->getFloorY();
                        $z = $sender->getPosition()->getFloorZ();
                        $this->warp->set($args[0], ["X" => $x, "Y" => $y, "Z" => $z, "world" => $wname, "mining" => $args[1]]);
                        $this->warp->save();
                        $sender->sendMessage("§bWarp §7>> §a{$args[0]}という名前の地点を新しく作成しました");
                        return true;
                    } else {
                        $sender->sendMessage("§bWarp §7>> §c{$args[0]}という名前の地点は既に存在します");
                        return true;
                    }
                } else {
                    $sender->sendMessage("§bWarp §7>> §cPoint名に色文字は使えません");
                }
                break;
            case "warpdel":
                if (!Server::getInstance()->isOp($sender->getName())) {
                    $sender->sendMessage("§bWarp §7>> §c権限が不足している為、実行できませんでした");
                    return true;
                }
                if (!isset($args[0])) {
                    $sender->sendMessage("§bWarp §7>> §cpointを設定してください");
                    return true;
                }
                $name = $this->ad($args[0]);
                if ($name !== false) {
                    $this->warp->remove($name);
                    $this->warp->save();
                    $sender->sendMessage("§bWarp §7>> §a" . $args[0] . "という名前の地点を削除しました");
                    return true;
                } else {
                    $sender->sendMessage("§bWarp §7>> §a§c" . $args[0] . "という名前の地点は存在しません");
                    return true;
                }
            case "warplist":
                if (!Server::getInstance()->isOp($sender->getName())) {
                    $sender->sendMessage("§bWarp §7>> §c権限が不足している為、実行できませんでした");
                    return true;
                }
                $wl = $this->warp->getAll();
                if (count($wl) == 0) {
                    $sender->sendMessage("§bWarp §7>> §cpointが存在しません");
                    return true;
                }
                $j = 0;
                $list = null;
                foreach ($wl as $key => $value) {
                    $list .= $key . ",";
                    $j++;
                }
                $sender->sendMessage("WarpList {$j}\n{$list}");
                return true;
        }
        return true;
    }

    public function ad($namedate) {
        if (!$this->warp->exists($namedate)) {
            foreach ($this->warp->getAll() as $name => $date) {
                $count = mb_stripos($name, $namedate);
                if ($count === 0) {
                    return $name;
                }
            }
            return false;
        } else {
            return $namedate;
        }
    }
}
