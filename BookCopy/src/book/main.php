<?php

namespace book;

use onebone\economyapi\EconomyAPI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener {

    public function onEnable(): void {
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML, array('手数料' => 'on',
            '経済' => 'EA',
            '金額' => '50'));
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        switch ($command->getName()) {
            case"book":
                if (!$sender instanceof Player) {
                    $sender->sendMessage("§bBook §7>> §cゲーム内で実行してください");
                    break;
                }
                $player = $sender;
                $item = $player->getInventory()->getItemInHand();
                $id = $item->getID();
                $ids = "387";
                if ($id == $ids) {
                    $cname = $item->getTitle();
                    $cname .= "";
                    $item->setTitle($cname);
                    if (!$sender->getInventory()->canAddItem($item)) {
                        $sender->sendMessage("§bBook §7>> §cインベントリを開けてください");
                        break;
                    }
                    if ($this->config->get("手数料") == "on") {
                        if ($this->config->get("経済") == "EA") {
                            $this->EA = EconomyAPI::getInstance();
                            $name = $sender->getName();
                            $cmdmoney = $this->EA->myMoney($name);
                            $money = $this->config->get("金額");
                            if ($money > $cmdmoney) {
                                $sender->sendMessage("§bBook §7>> §cお金が足りませんでした");
                                break;
                            }
                            $this->EA->reduceMoney($name, $money);
                            $player->getInventory()->addItem($item);
                            $sender->sendMessage("§bBook §7>> §a50円を消費して本を複製しました");
                            break;
                        }

                    }
                    $player->getInventory()->addItem($item);
                    break;
                } else {
                    $sender->sendMessage("§bBook §7>> §c手に持っているアイテムは本ではありません");
                    break;
                }
        }
        return true;
    }
}