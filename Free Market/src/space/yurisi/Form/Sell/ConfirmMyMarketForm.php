<?php

declare(strict_types=1);
namespace space\yurisi\Form\Sell;

use pocketmine\form\Form;
use pocketmine\item\Item;
use pocketmine\player\Player;
use space\yurisi\Config\YamlConfig;

class ConfirmMyMarketForm implements Form {

    private array $data;

    public function __construct(array $data) {
        $this->data = $data;
    }

    public function handleResponse(Player $player, $data): void {
        if (!is_numeric($data)) return;
        $player->sendForm(new SelectMyMarketForm($this->data[$data]));
    }

    public function jsonSerialize() {
        $button = array();
        foreach ($this->data as $data) {
            $item = YamlConfig::getItem($data["id"]);
            if ($item instanceof Item) {
                $name = $item->getName() . "§r";
                $ans = $data["public"] ? "非公開" : "公開";
                $button[] = ['text' => "{$name} | Count {$item->getCount()} | {$ans}\nPrice {$data["price"]} | ItemId {$item->getId()}:{$item->getMeta()} | {$data["player"]} | MarketId {$data["id"]}"];
            } else {
                return [];
            }
        }
        return [
            "type" => 'form',
            "title" => "Free Market",
            "content" => "",
            "buttons" => $button,
        ];
    }
}