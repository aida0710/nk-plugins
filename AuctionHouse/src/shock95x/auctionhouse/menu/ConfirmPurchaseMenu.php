<?php

declare(strict_types = 1);
namespace shock95x\auctionhouse\menu;

use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\inventory\Inventory;
use pocketmine\item\Item;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use pocketmine\world\sound\FizzSound;
use Ramsey\Uuid\Uuid;
use shock95x\auctionhouse\AHListing;
use shock95x\auctionhouse\AuctionHouse;
use shock95x\auctionhouse\database\storage\DataStorage;
use shock95x\auctionhouse\event\AuctionEndEvent;
use shock95x\auctionhouse\event\ItemPurchasedEvent;
use shock95x\auctionhouse\libs\muqsit\invmenu\InvMenu;
use shock95x\auctionhouse\libs\SOFe\AwaitGenerator\Await;
use shock95x\auctionhouse\menu\type\AHMenu;
use shock95x\auctionhouse\utils\Locale;
use shock95x\auctionhouse\utils\Settings;
use shock95x\auctionhouse\utils\Utils;

class ConfirmPurchaseMenu extends AHMenu {

    public const INDEX_CONFIRM = [11];
    public const INDEX_CANCEL = [15];

    protected static string $inventoryType = InvMenu::TYPE_CHEST;

    public function __construct(Player $player, AHListing $listing) {
        $this->setListings([$listing]);
        $this->setName(Locale::get($player, "purchase-menu-name"));
        parent::__construct($player);
    }

    public function renderButtons(): void {
        foreach (self::INDEX_CONFIRM as $x) {
            $confirmItem = Utils::getButtonItem($this->player, "confirm_purchase", "purchase-confirm");
            $this->getInventory()->setItem($x, $confirmItem);
        }
        foreach (self::INDEX_CANCEL as $x) {
            $cancelItem = Utils::getButtonItem($this->player, "cancel_purchase", "purchase-cancel");
            $this->getInventory()->setItem($x, $cancelItem);
        }
    }

    public function renderListings(): void {
        if (!isset($this->getListings()[0])) return;
        $listing = $this->getListings()[0];
        $item = clone $listing->getItem();
        $info = Locale::get($this->player, "purchase-item");
        $lore = str_ireplace(["{PRICE}", "{SELLER}"], [$listing->getPrice(true, Settings::formatPrice()), $listing->getSeller()], preg_filter('/^/', TextFormat::RESET, $info["lore"]));
        $lore = Settings::allowLore() ? [...$item->getLore(), ...$lore] : $lore;
        $this->getInventory()->setItem(13, $item->setLore($lore));
    }

    public function handle(Player $player, Item $itemClicked, Inventory $inventory, int $slot): bool {
        if (in_array($slot, self::INDEX_CANCEL)) {
            $player->removeCurrentWindow();
            SendMessage::Send($player, "出品されたアイテムの購入をキャンセルしました", "Bazaar", false);
            return false;
        }
        if (!in_array($slot, self::INDEX_CONFIRM)) return false;
        Await::f2c(function () use ($player) {
            $storage = DataStorage::getInstance();
            /** @var ?AHListing $listing */
            $listing = yield $storage->getListingById($this->getListings()[0]?->getId(), yield) => Await::ONCE;
            if ($listing == null || $listing->isExpired()) {
                SendMessage::Send($player, "選択したアイテムはもう出品されていません", "Bazaar", false);
                return;
            }
            if ($listing->getSellerUUID() == $player->getUniqueId()) {
                $player->removeCurrentWindow();
                SendMessage::Send($player, "自身で出品したアイテムは購入できません", "Bazaar", false);
                return;
            }
            $economy = AuctionHouse::getInstance()->getEconomyProvider();
            $balance = yield $economy->getMoney($player, yield) => Await::ONCE;
            if ($balance < $listing->getPrice()) {
                $player->removeCurrentWindow();
                SendMessage::Send($player, "所持金が足りませんでした", "Bazaar", false);
                return;
            }
            $item = $listing->getItem();
            if (!$player->getInventory()->canAddItem($item)) {
                $player->removeCurrentWindow();
                SendMessage::Send($player, "インベントリに購入しようとしたアイテムが入らない為キャンセルされました", "Bazaar", false);
                return;
            }
            $event = new ItemPurchasedEvent($player, $listing);
            $event->call();
            if ($event->isCancelled()) return;
            $storage->removeListing($listing);
            $res = yield $economy->subtractMoney($player, $listing->getPrice(), yield) => Await::ONCE;
            if (!$res) {
                SendMessage::Send($player, "トランザクションに失敗しました", "Bazaar", false);
                return;
            }
            $res = yield $economy->addMoney($listing->getSeller(), $listing->getPrice(), yield) => Await::ONCE;
            if (!$res) {
                $economy->addMoney($player, $listing->getPrice());
                SendMessage::Send($player, "トランザクションに失敗しました", "Bazaar", false);
                return;
            }
            $player->removeCurrentWindow();
            $player->getInventory()->addItem($item);
            SendMessage::Send($player, $item->getName() . "(x" . $item->getCount() . ")を" . $listing->getPrice(true, Settings::formatPrice()) . "円で購入しました", "Bazaar", true);
            $seller = AuctionHouse::getInstance()->getServer()->getPlayerByUUID(Uuid::fromString($listing->getSellerUUID()));
            $seller?->getWorld()->addSound($seller?->getPosition(), new FizzSound(), [$seller]);
            if (!is_null($seller)) SendMessage::Send($seller, $item->getName() . "(x" . $item->getCount() . ")を" . $listing->getPrice(true, Settings::formatPrice()) . "円で" . $player->getName() . "が購入しました", "Bazaar", true);
            (new AuctionEndEvent($listing, AuctionEndEvent::PURCHASED, $player))->call();
        });
        return true;
    }
}