<?php

namespace deceitya\miningtools\command;

use Deceitya\MiningLevel\MiningLevelAPI;
use deceitya\miningtools\tools\upgrade\expansion\ExpansionToolForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use pocketmine\Server;

class ExpansionMiningToolCommand extends Command {

    public const NETHERITE_AXE = 746;

    public function __construct() {
        parent::__construct("mt3", "NetheriteMiningTool");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($sender) < 120) {
            $sender->sendMessage("§bMiningToolShop §7>> §cレベル120以上でないと開けません");
            Server::getInstance()->dispatchCommand($sender, "mt");
        }
        $namedTag = $sender->getInventory()->getItemInHand()->getNamedTag();
        if ($namedTag->getTag('MiningTools_3') === null && $namedTag->getTag('MiningTools_Expansion') === null) {
            $sender->sendMessage("§bMiningTools §7>> §cこのアイテムはアップグレードに対応していません");
        }
        if ($sender->getInventory()->getItemInHand()->getId() === ItemIds::DIAMOND_PICKAXE || $sender->getInventory()->getItemInHand()->getId() === ItemIds::DIAMOND_SHOVEL) {
            $sender->sendMessage("§bMiningTools §7>> §cDiamondMiningToolsはアップグレードに対応していません");
            return;
        }
        if ($sender->getInventory()->getItemInHand()->getId() === ItemIds::DIAMOND_AXE || $sender->getInventory()->getItemInHand()->getId() === self::NETHERITE_AXE) {
            $sender->sendMessage("§bMiningTools §7>> §cMiningTools Axeはアップグレードに対応していません");
            return;
        }
        if ($namedTag->getTag('MiningTools_Expansion') !== null && $namedTag->getInt('MiningTools_Expansion') === 3) {
            $sender->sendMessage("§bMiningTools §7>> §cこのアイテムは最上位ツールの為、以降のアップグレードに対応していません");
            return;
        }
        $sender->sendForm(new ExpansionToolForm($sender));
    }

}