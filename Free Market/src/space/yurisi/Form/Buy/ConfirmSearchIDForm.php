<?php

declare(strict_types=1);
namespace space\yurisi\Form\Buy;

use onebone\economyapi\EconomyAPI;
use pocketmine\form\Form;
use pocketmine\item\Item;
use pocketmine\player\Player;
use pocketmine\Server;
use space\yurisi\Config\YamlConfig;

class ConfirmSearchIDForm implements Form {

    private int $id;

    private mixed $money;

    private array $data;

    public function __construct(int $id, $money) {
        $this->id = $id;
        $this->money = $money;
    }

    public function handleResponse(Player $player, $data): void {
        $cls = new YamlConfig();
        if ($data) {
            if ($cls->exists($this->data["id"])) {
                if ($this->data["player"] == $player->getName()) {
                    $player->sendMessage("§bMarket §7>> §c自分自身が出品したアイテムは購入することが出来ません");
                    return;
                }
                $item = Item::nbtDeserialize(unserialize($this->data["nbt"]));
                if ($player->getInventory()->canAddItem($item)) {
                    EconomyAPI::getInstance()->reduceMoney($player->getName(), $this->data["price"]);
                    EconomyAPI::getInstance()->addMoney($this->data["player"], $this->data["price"]);
                    $player->getInventory()->addItem($item);
                    $cls = new YamlConfig();
                    $cls->removeItem($this->data["id"]);
                    $player->sendMessage("§bMarket §7>> §a{$this->data["player"]}から{$item->getName()}/{$item->getId()}:{$item->getMeta()}を{$item->getCount()}個、{$this->data["price"]}円で買い取りました");
                    $target = Server::getInstance()->getPlayerExact($this->data["player"]);
                    if ($target instanceof Player) {
                        $target->sendMessage("§bMarket §7>> §a{$player->getName()}がItemId {$item->getId()}:{$item->getMeta()}を購入し{$this->data["price"]}円入りました。MarketId {$this->id}");
                    }
                    return;
                }
                $player->sendMessage("§bMarket §7>> §cインベントリの空きが無いため購入がキャンセルされました");
                return;
            }
            $player->sendForm(new SearchIDForm());
        } else {
            $buttons = [];
            if ($cls->getAllMarket() == null) {
                $player->sendMessage("§bMarket §7>> §c出品されていたアイテムが存在しませんでした");
                return;
            }
            foreach ($cls->getAllMarket() as $id) {
                $buttons[] = $cls->getMarketData($id);
            }
            $buttons_reverse = array_reverse($buttons);
            $player->sendForm(new ResultSearchIDForm($buttons_reverse, "1ページ"));
        }
    }

    public function jsonSerialize() {
        $cls = new YamlConfig();
        $this->data = $cls->getMarketData($this->id);
        $item = Item::nbtDeserialize(unserialize($this->data["nbt"]));
        $name = $item->getName() . "§r";
        return [
            'type' => 'modal',
            'title' => "Free Market",
            'content' => "現在の所持金 : {$this->money}\n\nItemName {$name}\nItemId {$item->getId()}:{$item->getMeta()}\nCount {$item->getCount()} | Price {$this->data["price"]}\nMarketId {$this->data["id"]}",
            'button1' => "上記のアイテムを購入する",
            'button2' => "最新の出品アイテムに戻る"
        ];
    }
}
