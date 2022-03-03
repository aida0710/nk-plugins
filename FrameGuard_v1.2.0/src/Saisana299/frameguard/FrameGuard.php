<?php

namespace Saisana299\frameguard;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class FrameGuard extends PluginBase {

    public $frame;
    public Config $config;

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        if (!file_exists($this->getDataFolder())) @mkdir($this->getDataFolder(), 0744, true);
        $this->config = new Config($this->getDataFolder() . "Frames.yml", Config::YAML);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if (!$sender instanceof Player) {
            $sender->sendMessage("§bFrameLock §7>> §aゲーム内で実行してください");
            return true;
        }
        switch (strtolower($label)) {
            case "lockfr":
                $name = $sender->getName();
                if (!isset($this->frame[$name])) {
                    $this->frame[$name]["type"] = "add";
                    $sender->sendMessage("§bFrameLock §7>> §a保護モードを有効にしました\n額縁をタップしてください\n再度コマンドを使うと保護モードを無効にできます");
                } elseif ($this->frame[$name]["type"] === "delete") {
                    $this->frame[$name]["type"] = "add";
                    $sender->sendMessage("§bFrameLock §7>> §a保護モードを有効にしました\n額縁をタップしてください\n再度コマンドを使うと保護モードを無効にできます");
                } else {
                    unset($this->frame[$name]);
                    $sender->sendMessage("§bFrameLock §7>> §a保護モードを無効にしました");
                }
                break;
            case "unlockfr":
                $name = $sender->getName();
                if (!isset($this->frame[$name])) {
                    $this->frame[$name]["type"] = "delete";
                    $sender->sendMessage("§bFrameLock §7>> §a保護解除モードを有効にしました\n額縁をタップしてください\n再度コマンドを使うと保護解除モードを無効にできます");
                } elseif ($this->frame[$name]["type"] === "add") {
                    $this->frame[$name]["type"] = "delete";
                    $sender->sendMessage("§bFrameLock §7>> §a保護解除モードを有効にしました\n額縁をタップしてください\n再度コマンドを使うと保護解除モードを無効にできます");
                } else {
                    unset($this->frame[$name]);
                    $sender->sendMessage("§bFrameLock §7>> §a保護解除モードを無効にしました");
                    break;
                }
        }
        return true;
    }

    public function onDisable(): void {
        $this->config->save();
    }
}