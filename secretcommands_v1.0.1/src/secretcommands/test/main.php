<?php

declare(strict_types=1);
namespace secretcommands\test;

use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use function array_flip;
use function explode;
use function substr;

class main extends PluginBase implements Listener {

	public $oponlycommand;
	public $secretCommand;
	public $list = [];

	public function onEnable() : void {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveResource("opOnlyCommand.yml");
		$this->saveResource("disableCommand.yml");
		$this->saveResource("secretCommand.yml");
		$this->oponlycommand = new Config($this->getDataFolder() . "opOnlyCommand.yml", Config::YAML);
		$this->secretCommand = new Config($this->getDataFolder() . "secretCommand.yml", Config::YAML);
		$this->list = array_flip($this->oponlycommand->getAll());
		$this->getScheduler()->scheduleDelayedTask(new ClosureTask(function () : void {
			$config = new Config($this->getDataFolder() . "disableCommand.yml", Config::YAML);
			$commandmap = $this->getServer()->getCommandMap();
			foreach ($config->getAll() as $value) {
				$command = $commandmap->getCommand($value);
				if ($command === null) {
					continue;
				}
				$commandmap->unregister($command);
			}
		}), 1);
	}

	public function onDataPacketSend(DataPacketSendEvent $event) : void {
		foreach ($event->getPackets() as $packet) {
			if ($packet instanceof AvailableCommandsPacket) {
				foreach ($this->secretCommand->getAll() as $name) {
					unset($packet->commandData[$name]);
				}
				foreach ($event->getTargets() as $networkSession) {
					if (!$this->getServer()->isOp($networkSession->getPlayer()->getName())) {
						foreach ($this->oponlycommand->getAll() as $name) {
							unset($packet->commandData[$name]);
						}
					}
				}
			}
		}
	}

	public function PlayerCommandPreprocess(PlayerCommandPreprocessEvent $event) : void {
		$player = $event->getPlayer();
		$message = $event->getMessage();
		if ($message === '' || $message[0] !== '/') return;
		$array = explode(" ", $message);
		$label = substr($array[0], 1);
		if (!$this->getServer()->isOp($event->getPlayer()->getName()) && isset($this->list[$label])) {
			SendMessage::Send($player, $this->getServer()->getLanguage()->translateString(TextFormat::RED . "%commands.generic.notFound"), "System", false);
			$event->cancel();
		}
	}
}
