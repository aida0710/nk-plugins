<?php

declare(strict_types = 1);
namespace shock95x\auctionhouse\commands\subcommand;

use lazyperson710\core\packet\SoundPacket;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use shock95x\auctionhouse\commands\arguments\PlayerArgument;
use shock95x\auctionhouse\libs\CortexPE\Commando\BaseSubCommand;
use shock95x\auctionhouse\libs\CortexPE\Commando\constraint\InGameRequiredConstraint;
use shock95x\auctionhouse\libs\CortexPE\Commando\exception\ArgumentOrderException;
use shock95x\auctionhouse\menu\player\PlayerListingMenu;
use shock95x\auctionhouse\menu\type\AHMenu;
use shock95x\auctionhouse\utils\Locale;

class ListingsCommand extends BaseSubCommand {

    /**
     * @throws ArgumentOrderException
     */
    protected function prepare(): void {
        $this->setPermission("auctionhouse.command.listings");
        $this->registerArgument(0, new PlayerArgument("player", true));
        $this->addConstraint(new InGameRequiredConstraint($this));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        assert($sender instanceof Player);
        if (!isset($args["player"])) {
            $sender->sendMessage("§bBazaar §7>> §c引数を省略することはできません");
            SoundPacket::Send($sender, 'note.bass');
            return;
        }
        $player = strtolower($args["player"]);
        if (strtolower($sender->getName()) == $player) {
            Locale::sendMessage($sender, "player-listings-usage");
            return;
        }
        AHMenu::open(new PlayerListingMenu($sender, $args["player"]));
    }
}