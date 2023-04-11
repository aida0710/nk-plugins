<?php

declare(strict_types = 1);

namespace ymggacha\src\yomogi_server\ymggacha\command\gacha\sub;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use ymggacha\src\yomogi_server\ymggacha\command\CommandMessages;
use ymggacha\src\yomogi_server\ymggacha\command\CommandPermissions;
use ymggacha\src\yomogi_server\ymggacha\form\GachaForm;
use ymggacha\src\yomogi_server\ymggacha\gacha\IInFormRollableGacha;
use function is_numeric;

class RollGachaSubCommand extends BaseSubCommand {

	private const ROLL_COUNT = 'rollCnt';

	private IInFormRollableGacha $gacha;

	public function __construct(string $name, IInFormRollableGacha $gacha) {
		$this->gacha = $gacha;
		$this->setPermission(CommandPermissions::PUBLIC);
		parent::__construct($name, $gacha->getDescription(), []);
	}

	/**
	 * @throws ArgumentOrderException
	 */
	protected function prepare() : void {
		$this->registerArgument(0, new IntegerArgument(self::ROLL_COUNT, true));
	}

	/**
	 * @param array<string, mixed> $args
	 */
	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {
		if (!$sender instanceof Player) {
			$sender->sendMessage(CommandMessages::EXECUTED_NON_PLAYER);
			return;
		}
		$rollCnt = $args[self::ROLL_COUNT] ?? null;
		if ($rollCnt === null) {
			(new GachaForm($this->gacha))->send($sender);
			return;
		}
		if (!is_numeric($rollCnt)) {
			$sender->sendMessage(CommandMessages::INVALID_FORMAT_ROLL_CNT);
			return;
		}
		$rollCnt = (int) $rollCnt;
		if ($rollCnt < 1 || $rollCnt > 10) {
			$sender->sendMessage(CommandMessages::INVALID_ROLL_CNT);
			return;
		}
		$this->gacha->roll($sender, $rollCnt);
	}
}
