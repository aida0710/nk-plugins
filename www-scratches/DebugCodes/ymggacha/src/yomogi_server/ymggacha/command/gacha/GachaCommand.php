<?php

declare(strict_types=1);
namespace ymggacha\src\yomogi_server\ymggacha\command\gacha;

use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use ymggacha\src\yomogi_server\ymggacha\command\CommandMessages;
use ymggacha\src\yomogi_server\ymggacha\command\CommandPermissions;
use ymggacha\src\yomogi_server\ymggacha\command\gacha\sub\RollGachaSubCommand;
use ymggacha\src\yomogi_server\ymggacha\form\GachaListForm;
use function strtolower;

class GachaCommand extends BaseCommand {

	private const NAME = 'gacha';
	private const DESCRIPTION = 'がちゃがちゃ';
	private const ALIASES = ['gatya'];

	public function __construct(PluginBase $plugin) {
		parent::__construct($plugin, self::NAME, self::DESCRIPTION, self::ALIASES);
		$this->setPermission(CommandPermissions::PUBLIC);
	}

	protected function prepare() : void {
		foreach (\ymggacha\src\yomogi_server\ymggacha\gacha\GachaMap::getAll() as $name => $gacha) {
			if (!$gacha instanceof \ymggacha\src\yomogi_server\ymggacha\gacha\IInFormRollableGacha) continue;
			$this->registerSubCommand(new RollGachaSubCommand(strtolower($name), $gacha));
		}
	}

	/**
	 * @param array<string, string> $args
	 */
	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {
		if (!$sender instanceof Player) {
			$sender->sendMessage(CommandMessages::EXECUTED_NON_PLAYER);
			return;
		}
		(new GachaListForm())->send($sender);
	}
}
