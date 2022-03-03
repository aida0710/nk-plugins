<?php

declare(strict_types=1);
namespace space\yurisi\Form\Buy;

use onebone\economyapi\EconomyAPI;
use pocketmine\form\Form;
use pocketmine\item\Item;
use pocketmine\player\Player;
use space\yurisi\Config\YamlConfig;

class ResultSearchIDForm implements Form {

    private array $button;

    private string $content;

    private int $page;

    private int $count;

    private array $id;

    public function __construct(array $button, string $content = "", $page = 0) {
        $this->button = $button;
        $this->content = $content;
        $this->page = $page;
        $this->count = 0;
        $this->id = [];
    }

    public function handleResponse(Player $player, $data): void {
        if (!is_numeric($data)) return;
        $page = $this->page;
        $backpage = $page - 1;
        $nextpage = $page + 1;
        $page_low = $page * 10;
        $page_max = $page_low + 10;
        if ($data === $this->count + 1) {
            if (isset($this->button[$page_max + 1])) {
                $player->sendForm(new self($this->button, $nextpage . "ページ", $nextpage));
            } else {
                $player->sendForm(new self($this->button, $backpage . "ページ", $backpage));
            }
            return;
        }
        if ($data === $this->count + 2) {
            $player->sendForm(new self($this->button, $backpage . "ページ", $backpage));
            return;
        }
        $cls = new YamlConfig();
        $market = $cls->getMarketData($this->id[$data]);
        if ($market["price"] > EconomyAPI::getInstance()->myMoney($player->getName())) {
            $player->sendForm(new self($this->button, $content = "お金が足りませんでした"));
            return;
        }
        $player->sendForm(new ConfirmSearchIDForm($this->id[$data], EconomyAPI::getInstance()->myMoney($player->getName())));
    }

    public function jsonSerialize() {
        $button = [];
        $count = 0;
        $page = $this->page;
        $page_low = $page * 10;
        $page_max = $page_low + 10;
        for ($i = $page_low; $i < $page_max; $i++) {
            if (!isset($this->button[$i])) continue;
            if ($i !== $page_low) $count++;
            $data = $this->button[$i];
            $item = Item::nbtDeserialize(unserialize($data["nbt"]));
            $name = $item->getName() . "§r";
            $this->id[] = $data["id"];
            $button[] = ['text' => "{$name} | Count {$item->getCount()}\nPrice {$data["price"]} | ItemId {$item->getId()}:{$item->getMeta()} | {$data["player"]} | MarketId:{$data["id"]}"];
        }
        $this->count = $count;
        if (isset($this->button[$page_max + 1])) $button[] = ['text' => "次のページに行く"];
        if (isset($this->button[$page_low - 1])) $button[] = ['text' => "前のページに行く"];
        return [
            "type" => 'form',
            "title" => "Free Market",
            "content" => $this->content,
            "buttons" => $button,
        ];
    }
}
