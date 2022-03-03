<?php
declare(strict_types=1);

namespace shelly7w7\VanillaCoordinates\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\GameRulesChangedPacket;
use pocketmine\network\mcpe\protocol\types\BoolGameRule;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class CoordinateCommand extends Command {

	public function __construct() {
		parent::__construct("coordinates", "左上の座標を表示 [on/off]", "/coordinates");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args): void {

		if(!$sender instanceof Player) {
			$sender->sendMessage("サーバー内で実行してください");
			return;
		}

		if(count($args) < 1) {
			$sender->sendMessage(TextFormat::RED . "§bCoordinates §7>> §c引数が無効です。/coordinates　on/offを使用してください");
			return;
		}

		switch($args[0]) {
			case "on":
				$pk = new GameRulesChangedPacket();
				$pk->gameRules = ["showcoordinates" => new BoolGameRule(true, false)];
				$sender->getNetworkSession()->sendDataPacket($pk);
				$sender->sendMessage("§bCoordinates §7>> §a座標を表示しました");
				break;

			case "off":
				$pk = new GameRulesChangedPacket();
				$pk->gameRules = ["showcoordinates" => new BoolGameRule(false, false)];
				$sender->getNetworkSession()->sendDataPacket($pk);
				$sender->sendMessage("§bCoordinates §7>> §a座標を表示を削除しました");
				break;

			default:
				$sender->sendMessage(TextFormat::RED . "§bCoordinates §7>> §c引数が無効です。/coordinates　on/offを使用してください");
				return;
		}
	}
}