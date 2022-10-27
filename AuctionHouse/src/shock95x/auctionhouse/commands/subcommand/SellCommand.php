<?php

declare(strict_types = 1);
namespace shock95x\auctionhouse\commands\subcommand;

use DateTime;
use lazyperson710\core\packet\SendBroadcastMessage;
use lazyperson710\core\packet\SendMessage;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use shock95x\auctionhouse\AuctionHouse;
use shock95x\auctionhouse\database\storage\DataStorage;
use shock95x\auctionhouse\economy\EconomyProvider;
use shock95x\auctionhouse\event\ItemListedEvent;
use shock95x\auctionhouse\libs\CortexPE\Commando\args\IntegerArgument;
use shock95x\auctionhouse\libs\CortexPE\Commando\BaseSubCommand;
use shock95x\auctionhouse\libs\CortexPE\Commando\constraint\InGameRequiredConstraint;
use shock95x\auctionhouse\libs\CortexPE\Commando\exception\ArgumentOrderException;
use shock95x\auctionhouse\libs\SOFe\AwaitGenerator\Await;
use shock95x\auctionhouse\manager\CooldownManager;
use shock95x\auctionhouse\utils\Settings;
use shock95x\auctionhouse\utils\Utils;

class SellCommand extends BaseSubCommand {

    /**
     * @throws ArgumentOrderException
     */
    protected function prepare(): void {
        $this->setPermission("auctionhouse.command.sell");
        $this->registerArgument(0, new IntegerArgument("price"));
        $this->addConstraint(new InGameRequiredConstraint($this));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        assert($sender instanceof Player);
        Await::f2c(function () use ($sender, $args) {
            $item = $sender->getInventory()->getItemInHand();
            if ($item->isNull()) {
                SendMessage::Send($sender, "出品したいアイテムを持ってください", "Bazaar", false);
                return;
            }
            if ($sender->isCreative() && !Settings::allowCreativeSale()) {
                SendMessage::Send($sender, "クリエイティブモードではアイテムの出品は禁止されています", "Bazaar", false);
                return;
            }
            if (Utils::isBlacklisted($item)) {
                SendMessage::Send($sender, "このアイテムはバザーには出品できません", "Bazaar", false);
                return;
            }
            if (!isset($args["price"]) || !is_numeric($args["price"])) {
                SendMessage::Send($sender, "入力された価格が無効な値です", "Bazaar", false);
                return;
            }
            $price = $args["price"];
            $listingPrice = Settings::getListingPrice();
            $balance = yield $this->getEconomy()->getMoney($sender, yield) => Await::ONCE;
            if (($balance < $listingPrice) && $listingPrice != 0) {
                SendMessage::Send($sender, "入力された価格が無効な値です", "Bazaar", false);
                return;
            }
            $listingCount = yield DataStorage::getInstance()->getActiveCountByPlayer($sender, yield) => Await::ONCE;
            if ($listingCount >= (Utils::getMaxListings($sender))) {
                SendMessage::Send($sender, Utils::getMaxListings($sender) . "以上の出品をすることはできません", "Bazaar", false);
                return;
            }
            if ($price < Settings::getMinPrice() || ($price > Settings::getMaxPrice() && Settings::getMaxPrice() != -1)) {
                SendMessage::Send($sender, "入力した値では出品することは出来ません。出品可能金額は" . Settings::getMinPrice() . "円から" . Settings::getMaxPrice() . "円までとなります", "Bazaar", false);
                return;
            }
            if (Settings::getListingCooldown() != 0) {
                if (CooldownManager::inCooldown($sender)) {
                    $cooldown = CooldownManager::getCooldown($sender);
                    $endTime = (new DateTime())->diff((new DateTime())->setTimestamp($cooldown));
                    SendMessage::Send($sender, "現在クールダウン中です。残り" . $endTime->i . "分" . $endTime->s . "秒", "Bazaar", false);
                    return;
                }
                if (CooldownManager::setCooldown($sender)) {
                    $uuid = $sender->getUniqueId();
                    $this->getOwningPlugin()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use ($uuid) {
                        CooldownManager::removeCooldown($uuid->toString());
                    }), Settings::getListingCooldown() * 20);
                }
            }
            $event = new ItemListedEvent($sender, $item, $price);
            $event->call();
            if ($event->isCancelled()) return;
            $count = $item->getCount();
            Utils::removeItem($sender, $item);
            $listing = yield DataStorage::getInstance()->createListing($sender, $item->setCount($count), (int)$price, yield) => Await::ONCE;
            SendBroadcastMessage::Send("{$sender->getName()}が{$item->getName()}§r§aを{$count}個、{$listing->getPrice(true, Settings::formatPrice())}円で出品開始しました", "Bazaar");
            SoundPacket::Send($sender, 'note.harp');
        });
    }

    public function getEconomy(): ?EconomyProvider {
        /** @var AuctionHouse $plugin */
        $plugin = $this->getOwningPlugin();
        return $plugin->getEconomyProvider();
    }
}