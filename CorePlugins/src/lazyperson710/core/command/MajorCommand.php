<?php

declare(strict_types = 1);
namespace lazyperson710\core\command;

use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;

class MajorCommand extends Command {

	public function __construct() {
		parent::__construct('major', 'ブロック間を簡単に計測できるアイテムを付与します');
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if (!($sender instanceof Player)) {
			$sender->sendMessage('サーバー内で実行してください');
			return;
		}
		$major = ItemFactory::getInstance()->get(ItemIds::FLINT);
		$major->setCustomName('Major');
		$major->setLore([
			'lore1' => 'ブロック間を簡単に測定出来ます',
			'lore2' => '一度目のタップで始点を設定し、二度目以降のタップで終点を設定できます',
			'lore3' => 'また、スニークしながらタップすることで設定したポイントを削除することが出来ます',
		]);
		if ($sender->getInventory()->contains($major)) {
			SendMessage::Send($sender, '既にMajorがインベントリに存在する為、処理が中断されました', 'Major', false);
			return;
		}
		if ($sender->getInventory()->canAddItem($major)) {
			$sender->getInventory()->addItem($major);
			SendMessage::Send($sender, 'Majorを一つ配布しました。使いかたはアイテム説明をご覧ください', 'Major', true);
		} else {
			SendMessage::Send($sender, 'インベントリが満タンの為、majorを配布できませんでした', 'Major', false);
		}
	}

}
