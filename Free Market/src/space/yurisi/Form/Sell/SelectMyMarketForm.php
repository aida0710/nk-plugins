<?php

declare(strict_types=1);
namespace space\yurisi\Form\Sell;

use pocketmine\form\Form;
use pocketmine\item\Item;
use pocketmine\player\Player;
use space\yurisi\Config\YamlConfig;

class SelectMyMarketForm implements Form {

    private array $data;

    public function __construct(array $data) {
        $this->data = $data;
    }

    public function handleResponse(Player $player, $data): void {
        if (!is_numeric($data)) return;
        $cls = new YamlConfig();
        switch ($data) {
            case 0:
                $item = YamlConfig::getItem($this->data["id"]);
                if ($item instanceof Item) {
                    if ($player->getInventory()->canAddItem($item)) {
                        $cls->removeItem($this->data["id"]);
                        $player->sendMessage("§bMarket §7>> §a出品を取り消しました。MarketId:{$this->data["id"]}");
                        $player->getInventory()->addItem($item);
                        return;
                    }
                }
                $player->sendMessage("§bMarket §7>> §cインベントリの空きが無いため、出品の取り消しができませんでした");
                return;
        }
        if ($cls->getMarketPlayer($player->getName()) == null) {
            $player->sendMessage("§bMarket §7>> §c出品されたデータが存在しませんでした");
            return;
        }
        $player->sendForm(new ConfirmMyMarketForm($cls->getMarketPlayer($player->getName())));
    }

    public function jsonSerialize() {
        $item = YamlConfig::getItem($this->data["id"]);
        if ($item instanceof Item) {
            $name = $item->getName() . "§r";
            $ans = $this->data["public"] ? "非公開" : "公開";
            $content = "ItemName {$name}\nItemId {$item->getId()}:{$item->getMeta()}\nCount {$item->getCount()} | Price {$this->data["price"]}\nMarketId {$this->data["id"]}\n\n公開設定 - {$ans}";
            return [
                "type" => 'form',
                "title" => "Free Market",
                "content" => $content,
                "buttons" => [
                    ['text' => "出品を取り消す"],
                    ['text' => "戻る"]
                ],
            ];
        }
        return [];
    }
}
