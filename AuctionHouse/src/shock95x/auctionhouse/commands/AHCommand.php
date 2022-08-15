<?php

declare(strict_types = 1);
namespace shock95x\auctionhouse\commands;

use lazyperson710\core\packet\SendForm;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use shock95x\auctionhouse\commands\subcommand\CategoryCommand;
use shock95x\auctionhouse\commands\subcommand\ExpiredCommand;
use shock95x\auctionhouse\commands\subcommand\FormCommand;
use shock95x\auctionhouse\commands\subcommand\ListingsCommand;
use shock95x\auctionhouse\commands\subcommand\ReloadCommand;
use shock95x\auctionhouse\commands\subcommand\SellCommand;
use shock95x\auctionhouse\commands\subcommand\ShopCommand;
use shock95x\auctionhouse\form\AuctionMain;
use shock95x\auctionhouse\libs\CortexPE\Commando\BaseCommand;

class AHCommand extends BaseCommand {

    protected function prepare(): void {
        $this->registerSubCommand(new ShopCommand("shop", "Shows AH shop menu"));
        $this->registerSubCommand(new SellCommand("sell", "Sell item in hand to the AH"));
        $this->registerSubCommand(new CategoryCommand("category", "Opens category menu"));
        $this->registerSubCommand(new ListingsCommand("listings", "Shows player listings"));
        $this->registerSubCommand(new ExpiredCommand("expired", "Shows expired listings"));
        $this->registerSubCommand(new ReloadCommand("reload", "Reload plugin configuration files"));
        $this->registerSubCommand(new FormCommand("form", "オークションの基本機能をformUIで開けます"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        if (count($args) == 0 && $sender instanceof Player) {
            SendForm::Send($sender, (new AuctionMain($sender)));
        }
    }
}
