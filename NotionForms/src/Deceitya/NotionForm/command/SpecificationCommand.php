<?php

namespace Deceitya\NotionForm\command;

use Deceitya\MiningLevel\MiningLevelAPI;
use deceitya\miningtools\tools\diamond\DiamondToolForm;
use Deceitya\NotionForm\Form\StartForm;
use Deceitya\NotionForm\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;

class SpecificationCommand extends Command {

    public function __construct() {
        parent::__construct("specification", "鯖の仕様を説明しています");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        $sender->sendForm(new StartForm(Main::$specification));
    }

}