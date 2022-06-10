<?php
declare(strict_types=1);

namespace shock95x\auctionhouse\commands\subcommand;

use shock95x\auctionhouse\libs\CortexPE\Commando\BaseSubCommand;
use shock95x\auctionhouse\libs\CortexPE\Commando\constraint\ConsoleRequiredConstraint;
use pocketmine\command\CommandSender;
use shock95x\auctionhouse\database\legacy\LegacyConverter;
use shock95x\auctionhouse\libs\SOFe\AwaitGenerator\Await;

class ConvertCommand extends BaseSubCommand {

	protected function prepare(): void {
		$this->addConstraint(new ConsoleRequiredConstraint($this));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
		Await::f2c(function () use ($sender) {
			$converter = LegacyConverter::getInstance();
			if (yield $converter->isLegacy()) {
				$sender->sendMessage("Starting conversion...");
				yield $converter->convert();
				$sender->sendMessage("Conversion done!");
			}
		});
	}
}