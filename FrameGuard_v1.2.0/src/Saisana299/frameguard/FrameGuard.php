<?php

declare(strict_types = 0);
namespace Saisana299\frameguard;

use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use function file_exists;
use function mkdir;
use function strtolower;

class FrameGuard extends PluginBase {

	public $frame;
	public Config $config;

	public function onEnable() : void {
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		if (!file_exists($this->getDataFolder())) @mkdir($this->getDataFolder(), 0744, true);
		$this->config = new Config($this->getDataFolder() . 'Frames.yml', Config::YAML);
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool {
		if (!$sender instanceof Player) {
			$sender->sendMessage('サーバー内で実行してください');
			return true;
		}
		switch (strtolower($label)) {
			case 'lockfr':
				$name = $sender->getName();
				if (!isset($this->frame[$name])) {
					$this->frame[$name]['type'] = 'add';
					SendMessage::Send($sender, "保護モードを有効にしました\n額縁をタップしてください\n再度コマンドを使うと保護モードを無効にできます", 'FrameLock', true);
				} elseif ($this->frame[$name]['type'] === 'delete') {
					$this->frame[$name]['type'] = 'add';
					SendMessage::Send($sender, "保護モードを有効にしました\n額縁をタップしてください\n再度コマンドを使うと保護モードを無効にできます", 'FrameLock', true);
				} else {
					unset($this->frame[$name]);
					SendMessage::Send($sender, '保護モードを無効にしました', 'FrameLock', true);
				}
				break;
			case 'unlockfr':
				$name = $sender->getName();
				if (!isset($this->frame[$name])) {
					$this->frame[$name]['type'] = 'delete';
					SendMessage::Send($sender, "保護解除モードを有効にしました\n額縁をタップしてください\n再度コマンドを使うと保護解除モードを無効にできます", 'FrameLock', true);
				} elseif ($this->frame[$name]['type'] === 'add') {
					$this->frame[$name]['type'] = 'delete';
					SendMessage::Send($sender, "保護解除モードを有効にしました\n額縁をタップしてください\n再度コマンドを使うと保護解除モードを無効にできます", 'FrameLock', true);
				} else {
					unset($this->frame[$name]);
					SendMessage::Send($sender, '保護解除モードを無効にしました', 'FrameLock', true);
					break;
				}
		}
		return true;
	}

	public function onDisable() : void {
		$this->config->save();
	}
}
