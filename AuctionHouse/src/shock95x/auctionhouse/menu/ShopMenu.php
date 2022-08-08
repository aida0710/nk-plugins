<?php

declare(strict_types = 1);
namespace shock95x\auctionhouse\menu;

use DateTime;
use pocketmine\inventory\Inventory;
use pocketmine\item\Item;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use shock95x\auctionhouse\database\storage\DataStorage;
use shock95x\auctionhouse\libs\SOFe\AwaitGenerator\Await;
use shock95x\auctionhouse\menu\type\PagingMenu;
use shock95x\auctionhouse\utils\Locale;
use shock95x\auctionhouse\utils\Settings;
use shock95x\auctionhouse\utils\Utils;

class ShopMenu extends PagingMenu {

    public const INDEX_STATS = 49;
    public const INDEX_LISTINGS = 45;
    public const INDEX_EXPIRED = 46;

    private int $selling = 0;
    private int $expired = 0;
    private int $total = 0;

    public function __construct(Player $player) {
        $this->setName(Locale::get($player, "menu-name"));
        parent::__construct($player, false);
    }

    protected function init(DataStorage $storage): void {
        Await::f2c(function () use ($storage) {
            $this->setListings(yield $storage->getActiveListings(yield, (45 * $this->page) - 45) => Await::ONCE);
            $this->selling = yield $storage->getActiveCountByPlayer($this->player, yield) => Await::ONCE;
            $this->expired = yield $storage->getExpiredCountByPlayer($this->player, yield) => Await::ONCE;
            $this->total = yield $storage->getActiveListingCount(yield) => Await::ONCE;
            $this->pages = (int)ceil($this->total / 45);
        }, fn() => parent::init($storage));
    }

    public function renderButtons(): void {
        parent::renderButtons();
        $stats = Utils::getButtonItem($this->player, "stats", "main-stats", ["{PAGE}", "{MAX}", "{TOTAL}"], [$this->page, $this->pages, $this->total]);
        $howto = Utils::getButtonItem($this->player, "howto", "sell-description");
        $info = Utils::getButtonItem($this->player, "info", "main-description");
        $listings = Utils::getButtonItem($this->player, "player_listings", "view-listed-items", ["{SELLING}"], [$this->selling]);
        $expired = Utils::getButtonItem($this->player, "expired_listings", "view-expired-items", ["{EXPIRED}"], [$this->expired]);
        $items = [
            self::INDEX_STATS => $stats,
            self::INDEX_LISTINGS => $listings,
            self::INDEX_EXPIRED => $expired,
            52 => $howto,
            53 => $info,
        ];
        foreach ($items as $slot => $item) $this->getInventory()->setItem($slot, $item);
    }

    public function renderListings(): void {
        foreach ($this->getListings() as $index => $listing) {
            $item = clone $listing->getItem();
            $endTime = (new DateTime())->diff((new DateTime())->setTimestamp($listing->getEndTime()));
            $listedItem = Locale::get($this->player, "listed-item");
            $lore = str_ireplace(["{PRICE}", "{SELLER}", "{D}", "{H}", "{M}"], [$listing->getPrice(true, Settings::formatPrice()), $listing->getSeller(), $endTime->days, $endTime->h, $endTime->i], preg_filter('/^/', TextFormat::RESET, $listedItem));
            $lore = Settings::allowLore() ? [...$item->getLore(), ...$lore] : $lore;
            $item->setLore($lore);
            $this->getInventory()->setItem($index, $item);
        }
        parent::renderListings();
    }

    public function handle(Player $player, Item $itemClicked, Inventory $inventory, int $slot): bool {
        switch ($slot) {
            case self::INDEX_LISTINGS:
                self::open(new ListingsMenu($this->player), false);
                return false;
            case self::INDEX_EXPIRED:
                self::open(new ExpiredMenu($this->player), false);
                return false;
        }
        $this->openListing($slot, $itemClicked);
        return parent::handle($player, $itemClicked, $inventory, $slot);
    }
}