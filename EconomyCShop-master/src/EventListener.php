<?php declare(strict_types=1);
namespace famima65536\EconomyCShop;

use famima65536\EconomyCShop\application\exception\DuplicateShopException;
use famima65536\EconomyCShop\application\ShopApplicationService;
use famima65536\EconomyCShop\model\Coordinate;
use famima65536\EconomyCShop\model\exception\InvalidProductException;
use famima65536\EconomyCShop\model\Product;
use famima65536\EconomyCShop\repository\IShopRepository;
use famima65536\EconomyCShop\service\ShopService;
use famima65536\EconomyCShop\utils\ChestPairingHelper;
use famima65536\EconomyCShop\utils\economy\EconomyWrapper;
use famima65536\EconomyCShop\utils\SignParser;
use pocketmine\block\Chest;
use pocketmine\block\tile\Chest as TileChest;
use pocketmine\block\utils\SignText;
use pocketmine\block\WallSign;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use pocketmine\Server;
use pocketmine\world\Position;

class EventListener implements Listener {

    public function __construct(
        private ShopApplicationService $shopApplicationService,
        private ShopService            $shopService,
        private IShopRepository        $shopRepository,
        private EconomyWrapper         $economyWrapper,
        //private Config                 $message
    ) {
    }

    public function onSignChange(SignChangeEvent $event) {
        $oldText = $event->getOldText();
        $newText = $event->getNewText();
        for ($i = 0; $i < SignText::LINE_COUNT; ++$i) {
            if ($oldText->getLine($i) !== $newText->getLine($i))
                return;
        }
        $this->onEndSignEdit($event);
    }

    /**
     * @notHandler
     * @param SignChangeEvent $event
     */
    public function onEndSignEdit(SignChangeEvent $event) {
        $signBlock = $event->getSign();
        $player = $event->getPlayer();
        if (!$signBlock instanceof WallSign) {
            return;
        }
        $signText = $event->getNewText();
        try {
            $product = SignParser::getInstance()->parse($signText);
        } catch (InvalidProductException $exception) {
            //$player->sendMessage($this->message->getNested('create-shop.invalid-product', 'create-shop.invalid-product'));
            $player->sendMessage("§bChestShop §7>> §c指定された数値が無効です。必ず価格または数量を1以上の数値にしてください");
            return;
        }
        if ($product === null) {
            return;
        }
        assert($product instanceof Product);
        $mainchest = $signBlock->getSide(Facing::opposite($signBlock->getFacing()));
        if (!$mainchest instanceof Chest) {
            return;
        }
        $chestPosition = $mainchest->getPosition();
        $tileChest = $chestPosition->getWorld()->getTile($chestPosition);
        assert($tileChest instanceof TileChest);
        $subchest = null;
        if ($tileChest->isPaired()) {
            $subchest = $tileChest->getPair()->getBlock();
            assert($subchest instanceof Chest);
        }
        try {
            $this->shopApplicationService->createShop($player, $product, $signBlock, $mainchest, $subchest);
        } catch (DuplicateShopException $exception) {
            //$player->sendMessage($this->message->getNested('create-shop.duplicate', 'create-shop.duplicate'));
            $player->sendMessage("§bChestShop §7>> §cこのチェストは既にチェストショップとして機能しているものがある為チェストショップを作成することは出来ません");
            return;
        }
        $shopSignText = new SignText([
            "{$player->getName()}",
            "価格 : {$product->getPrice()}",
            "数量 : {$product->getAmount()}",
            $product->getItem()->getVanillaName()
        ]);
        $event->setNewText($shopSignText);
        //$player->sendMessage($this->message->getNested('create-shop.success', 'create-shop.success'));
        //stoneを1個100円で販売を開始しました
        $player->sendMessage("§bChestShop §7>> §a{$product->getItem()->getVanillaName()}を{$product->getAmount()}個、{$product->getPrice()}円で販売を開始しました");
    }

    public function onBlockBreak(BlockBreakEvent $event) {
        $block = $event->getBlock();
        $player = $event->getPlayer();
        if ($block instanceof Chest) {
            $position = $block->getPosition();
            $world = $position->getWorld()->getFolderName();
            $coordinate = Coordinate::fromPosition($position);
            if (!$this->shopService->existShopChest($world, $coordinate)) {
                return;
            }
            $shop = $this->shopRepository->findByChest($world, $coordinate);
            if ($shop->getMainChest()->equals($coordinate)) {
                $event->cancel();
                //$player->sendMessage($this->message->getNested("break-shop-chest.shop-is-still-open", "break-shop-chest.shop-is-still-open"));
                $player->sendMessage("§bChestShop §7>> §cチェストショップとして機能している為、チェストを破壊することは出来ません");
                return;
            }
            if (!Server::getInstance()->isOp($player->getName())) {
                if ($shop->getOwner() !== $player->getName()) {
                    $event->cancel();
                    //$player->sendMessage($this->message->getNested("break-shop-chest.player-is-not-owner", "break-shop-chest.player-is-not-owner"));
                    $player->sendMessage("§bChestShop §7>> §c作成者でない為、チェストショップを破壊することが出来ませんでした。作成者:{$shop->getOwner()}");
                    return;
                }
            }
            $this->shopApplicationService->removeSubChest($shop);
            return;
        }
        if ($block instanceof WallSign) {
            $position = $block->getPosition();
            $world = $position->getWorld()->getFolderName();
            $coordinate = Coordinate::fromPosition($position);
            $shop = $this->shopRepository->findBySign($world, $coordinate);
            if ($shop === null) {
                return;
            }
            if (!Server::getInstance()->isOp($player->getName())) {
                if ($shop->getOwner() !== $player->getName() and !$player->hasPermission('economy-c-shop.force-close-shop')) {
                    $event->cancel();
                    //$player->sendMessage($this->message->getNested("break-shop-sign.player-is-not-owner", "break-shop-sign.player-is-not-owner"));
                    $player->sendMessage("§bChestShop §7>> §c作成者でない為、チェストショップを破壊することが出来ませんでした。作成者:{$shop->getOwner()}");
                    return;
                }
            }
            $this->shopApplicationService->destroyShop($shop);
            //$player->sendMessage($this->message->getNested("break-shop-sign.success", "break-shop-sign.success"));
            $player->sendMessage("§bChestShop §7>> §aチェストショップを正常に破壊しました");
            return;
        }
    }

    /**
     * @priority LOWEST
     */
    public function onPlayerInteract(PlayerInteractEvent $event) {
        $block = $event->getBlock();
        $player = $event->getPlayer();
        if ($block instanceof Chest) {
            $position = $block->getPosition();
            $world = $position->getWorld()->getFolderName();
            $coordinate = Coordinate::fromPosition($position);
            if (!$this->shopService->existShopChest($world, $coordinate)) {
                return;
            }
            $shop = $this->shopRepository->findByChest($world, $coordinate);
            if (Server::getInstance()->isOp($player->getName())) {
                return;
            }
            if ($shop->getOwner() !== $player->getName()) {
                $event->cancel();
                //$player->sendMessage($this->message->getNested("open-shop-chest.player-is-not-owner", "open-shop-chest.player-is-not-owner"));
                $player->sendMessage("§bChestShop §7>> §cこのチェストショップは{$shop->getOwner()}が管理しているため開くことが出来ません");
                return;
            }
        }
        if ($block instanceof WallSign) {
            $position = $block->getPosition();
            $world = $position->getWorld()->getFolderName();
            $coordinate = Coordinate::fromPosition($position);
            $shop = $this->shopRepository->findBySign($world, $coordinate);
            if ($shop === null) {
                return;
            }
            if ($shop->getOwner() === $player->getName()) {
                //$player->sendMessage($this->message->getNested("buy-item.player-is-owner", "buy-item.player-is-owner"));
                $player->sendMessage("§bChestShop §7>> §c自身のチェストショップからアイテムを購入することは出来ません");
                return;
            }
            $product = $shop->getProduct();
            if (!$player->getInventory()->canAddItem($product->getItem())) {
                //$player->sendMessage($this->message->getNested("buy-item.inventory-full", "buy-item.inventory-full"));
                $player->sendMessage("§bChestShop §7>> §cインベントリに空きがない為、アイテムを購入することが出来ませんでした");
                return;
            }
            $mainchestCoordinate = $shop->getMainChest();
            $tileChest = $block->getPosition()->getWorld()->getTile(new Position($mainchestCoordinate->getX(), $mainchestCoordinate->getY(), $mainchestCoordinate->getZ(), null));
            assert($tileChest instanceof TileChest);
            /** @var Item[] $remain */
            $remain = $tileChest->getInventory()->removeItem($product->getItem());
            if (count($remain) > 0) {
                $item = $product->getItem();
                $item->setCount($item->getCount() - $remain[0]->getCount());
                $tileChest->getInventory()->addItem($item);
                //$player->sendMessage($this->message->getNested("buy-item.chest-empty", "buy-item.chest-empty"));
                $player->sendMessage("§bChestShop §7>> §cチェストショップにアイテムがありません。作成者の{$shop->getOwner()}に連絡してください");
                $online = Server::getInstance()->getPlayerExact($shop->getOwner());
                $online?->sendTip("§bChestShop §7>> §cチェストショップの在庫が無くなりました。物品 : {$product->getItem()} | 報告者 : {$player->getName()} | Position X.{$player->getPosition()->getFloorX()}, Y.{$player->getPosition()->getFloorY()}, Z.{$player->getPosition()->getFloorZ()}, World.{$player->getPosition()->getWorld()->getFolderName()}");
                return;
            }
            if (!$this->economyWrapper->transfer($player->getName(), $shop->getOwner(), $product->getPrice())) {
                $tileChest->getInventory()->addItem($product->getItem());
                //$player->sendMessage($this->message->getNested("buy-item.transaction-fail", "buy-item.transaction-fail"));
                $player->sendMessage("§bChestShop §7>> §c購入する為に必要なお金がありません。必要な金額:{$product->getPrice()}円");
                return;
            }
            $player->getInventory()->addItem($product->getItem());
            //$player->sendMessage($this->message->getNested("buy-item.success", "buy-item.success"));
            $player->sendMessage("§bChestShop §7>> §a{$shop->getOwner()}から{$product->getItem()}を{$product->getPrice()}円で{$product->getAmount()}個購入しました");
            $online = Server::getInstance()->getPlayerExact($shop->getOwner());
            $online?->sendTip("§bChestShop §7>> §a{$player->getName()}から{$product->getItem()}を{$product->getPrice()}円で{$product->getAmount()}個購入されました");
        }
    }

    /**
     * @priority MONITOR
     * @param BlockPlaceEvent $event
     */
    public function onBlockPlace(BlockPlaceEvent $event) {
        $block = $event->getBlock();
        if (!$block instanceof Chest) {
            return;
        }
        $position = $block->getPosition();
        $pair = ChestPairingHelper::getPairedChest($block);
        if ($pair === null) {
            return;
        }
        $shop = $this->shopRepository->findByChest($position->getWorld()->getFolderName(), Coordinate::fromPosition($pair->getPosition()));
        if ($shop !== null) {
            $this->shopApplicationService->addSubChest($shop, $block);
        }
    }
}
