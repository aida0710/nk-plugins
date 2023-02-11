<?php

declare(strict_types = 0);
namespace deceitya\repair\command;

use Deceitya\MiningLevel\MiningLevelAPI;
use deceitya\repair\form\RepairForm;
use Error;
use Exception;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Durable;
use pocketmine\player\Player;

class RepairCommand extends Command {

	public function __construct() {
		parent::__construct('repair', 'どこでも修繕formを開く(80レベル以上)');
	}

	/**
	 * @throws Exception
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if (!($sender instanceof Player)) {
			$sender->sendMessage('サーバー内で実行してください');
			return;
		}
		$player = $sender;
		$item = $player->getInventory()->getItemInHand();
		if (MiningLevelAPI::getInstance()->getLevel($sender) >= 80) {
			if (RepairForm::checkItem($player) === false) {
				return;
			}
			$level = 5;
			foreach ($item->getEnchantments() as $enchant) {
				$level += 8 + $enchant->getLevel();
			}
			$mode = 'command';
			if (!$item instanceof Durable) throw new Error('道具以外のアイテムが指定されました');
			SendForm::Send($player, (new RepairForm($level, $item, $mode)));
		} else {
			SendMessage::Send($sender, 'Repairコマンドは80レベル以上でないと開くことが出来ません。通常はかなとこをスニークタップすることで修繕出来ます', 'Repair', false);
		}
	}
}
