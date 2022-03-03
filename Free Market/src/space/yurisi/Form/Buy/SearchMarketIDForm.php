<?php

declare(strict_types=1);
namespace space\yurisi\Form\Buy;

use pocketmine\form\Form;
use pocketmine\player\Player;
use space\yurisi\Config\YamlConfig;

//フリマから検索==========
class SearchMarketIDForm implements Form {

    private string $label;

    public function __construct(string $label = "") {
        $this->label = $label;
    }

    public function handleResponse(Player $player, $data): void {
        if ($data == false) return;
        if (!is_numeric($data[1])) {
            $player->sendForm(new self("§cフリーマーケットのIDは整数でのみ入力できます"));
            return;
        }
        $id = floor((int)$data[1]);
        $cls = new YamlConfig();
        $ary[] = $cls->getMarketData((int)$id);
        if (empty($ary[0])) {
            $player->sendForm(new self("§c指定されたアイテムは見つかりませんでした"));
            return;
        }
        $player->sendForm(new ResultSearchIDForm($ary));
    }

    public function jsonSerialize() {
        $content[] = [
            "type" => "label",
            "text" => $this->label
        ];
        $content[] = [
            "type" => "input",
            "text" => "MarketIdを入力してください"
        ];
        return [
            'type' => 'custom_form',
            'title' => 'Free Market',
            'content' => $content
        ];
    }
}