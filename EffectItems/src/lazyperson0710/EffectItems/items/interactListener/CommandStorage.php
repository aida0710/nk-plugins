<?php

declare(strict_types = 1);
namespace lazyperson0710\EffectItems\items\interactListener;

use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SendMessage\SendMessage;
use lazyperson710\core\packet\SendMessage\SendTip;
use lazyperson710\sff\form\CommandStorageForm;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\Item;
use pocketmine\Server;

class CommandStorage {

	public static function execution(PlayerItemUseEvent|PlayerInteractEvent $event, Item $item) : void {
		$event->cancel();
		$player = $event->getPlayer();
		if ($player->isSneaking()) {
			SendForm::Send($player, (new CommandStorageForm()));
		} elseif ($item->getName() === 'コマンド記憶装置') {
			SendMessage::Send($player, 'スニークしながらタップすることでコマンドを設定することが出来ます', 'CmdStorage', true);
		} else {
			Server::getInstance()->dispatchCommand($player, $item->getName());
			SendTip::Send($player, "{$item->getName()}を実行しました", 'CmdStorage', true);
		}
	}
}
