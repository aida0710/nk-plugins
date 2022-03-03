<?php

declare(strict_types=1);
namespace space\yurisi\Form\Buy;

use pocketmine\form\Form;
use pocketmine\player\Player;
use space\yurisi\Config\YamlConfig;

//アイテムIDから検索==========
class SearchIDForm implements Form {

    private string $label;

    public function __construct(string $label = "") {
        $this->label = $label;
    }

    public function handleResponse(Player $player, $data): void {
        if ($data == false) return;
        if (!is_numeric($data[1])) {
            $player->sendForm(new self("§cアイテムのidは整数でのみ入力できます"));
            return;
        }
        if (!is_numeric($data[2])) {
            $player->sendForm(new self("§cアイテムのmeta値は整数でのみ入力できます"));
            return;
        }
        $id = floor((int)$data[1]);
        $damage = floor((int)$data[2]);
        $cls = new YamlConfig();
        $ary = $cls->getMarketItem((int)$id, (int)$damage);
        if ($ary == null) {
            $player->sendForm(new self("§c指定されたアイテムは見つかりませんでした"));
            return;
        }
        foreach ($ary as $id) {
            $buttons[] = $cls->getMarketData($id);
        }
        $player->sendForm(new ResultSearchIDForm($buttons));
    }

    public function jsonSerialize() {
        $content[] = [
            "type" => "label",
            "text" => $this->label
        ];
        $content[] = [
            "type" => "input",
            "text" => "アイテムのidを入力してください"
        ];
        $content[] = [
            "type" => "input",
            "text" => "アイテムのmeta値を入力してください",
            "default" => "0",
        ];
        return [
            'type' => 'custom_form',
            'title' => 'Free Market',
            'content' => $content
        ];
    }
}