<?php

namespace Deceitya\Gatya\Command;

use Deceitya\Gatya\Form\SeriesForm;
use Deceitya\Gatya\Series\SeriesFactory;
use Deceitya\Gatya\Utils\MessageContainer;
use Exception;
use lazyperson0710\ticket\TicketAPI;
use onebone\economyapi\EconomyAPI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;

class GatyaCommand extends Command {

    public function __construct() {
        parent::__construct("gatya");
        $this->setPermission('gatya.command.gatya');
        $this->setDescription(MessageContainer::get('command.gatya.description'));
        $this->setUsage(MessageContainer::get('command.gatya.usage'));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            return;
        }
        if (count($args) < 1) {
            $sender->sendForm(new SeriesForm());
            return;
        }
        try {
            $series = SeriesFactory::getSeries(array_shift($args));
            $count = array_shift($args) ?? 1;
            $api = EconomyAPI::getInstance();
            for ($i = 0; $i < $count; $i++) {
                $eventTicket = ItemFactory::getInstance()->get(1, 0, $series->getCost());
                if ($series->isTicket()) {
                    if (!TicketAPI::getInstance()->containsTicket($sender, $series->getCost())) {
                        $sender->sendMessage(MessageContainer::get('command.gatya.no_ticket'));
                        return;
                    }
                } elseif ($series->isEventTicket()) {
                    if (!$sender->getInventory()->contains($eventTicket)) {
                        $sender->sendMessage(MessageContainer::get('command.gatya.no_eventTicket'));
                        return;
                    }
                } else {
                    if ($api->myMoney($sender) < $series->getCost()) {
                        $sender->sendMessage(MessageContainer::get('command.gatya.no_money'));
                        return;
                    }
                }
                $item = $series->getItem(mt_rand(0, 10000) / 100);
                if (empty($sender->getInventory()->addItem($item))) {
                    $sender->sendMessage(MessageContainer::get('command.gatya.result', $item->getCustomName() ?: $item->getName()));
                    if ($series->isTicket()) {
                        TicketAPI::getInstance()->reduceTicket($sender, $series->getCost());
                    } elseif ($series->isEventTicket()) {
                        $sender->getInventory()->removeItem($eventTicket);
                    } else {
                        $api->reduceMoney($sender, $series->getCost());
                    }
                } else {
                    $sender->sendMessage(MessageContainer::get('command.gatya.no_space'));
                    return;
                }
            }
            return;
        } catch (Exception $e) {
            $sender->sendMessage(MessageContainer::get('command.gatya.no_series'));
            return;
        }
    }
}
