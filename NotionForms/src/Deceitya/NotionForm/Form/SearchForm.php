<?php

namespace Deceitya\NotionForm\Form;

use pocketmine\form\Form;
use pocketmine\player\Player;

class SearchForm implements Form {

    public $heading = [];
    /** @var string $error */
    public $error = "";
    /** @var string[] $error */
    public $default = [];
    private array $file;

    public function __construct(array $file, string $error = "", array $default = []) {
        $this->error = $error;
        $this->default = $default;
        $this->file = $file;
    }

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
        if ($data[4] === true) {
            $player->sendForm(new StartForm($this->file));
            return;
        }
        unset($data[0]);
        unset($data[4]);
        foreach ($this->file as $id => $c) {
            foreach ($data as $search) {
                if ($search === "") {
                    continue;
                }
                if (isset($this->heading[$id])) {
                    continue;
                }
                if (($result = strpos($c["title"], $search)) !== false) {
                    $this->heading[$id] = $this->createHeading($result, $c["title"], $search) . "\n" . $this->strcut($c["text"], 0, 20) . "§r";
                    continue;
                }
                if (($result = strpos($c["text"], $search)) !== false) {
                    $this->heading[$id] = $c["title"] . "\n" . $this->createHeading($result, $c["text"], $search);
                }
            }
        }
        if (count($this->heading) === 0) {
            $message = "§eはるか彼方まで検索したのですが、残念ながら見つかりませんでした。§r\n";
            $player->sendForm(new self($this->file, "\n" . $message, $data));
            return;
        }
        $player->sendForm(new SearchResultForm($this->file, $this->heading, $data));
    }

    //https://paiza.io/projects/0xkeR4gN6pQvi4n5Eqh3qQ
    public function createHeading(int $result, string $text, string $search): string {
        $text = $this->convertEOL($text);
        $array = $this->InjectDecoration($result, $text, $search);
        $color = $array[0];
        $text = $array[1];
        $start = $result - 10;
        if ($start < 0) {
            return $this->strcut($text, 0, 20) . "§8";
        }
        return $color . $this->strcut($text, $start, 20) . "§8";
    }

    public function convertEOL($string, $to = "\n"): string {
        return strtr($string, array(
            "\r\n" => $to,
            "\r" => $to,
            "\n" => $to,
        ));
    }

    public function InjectDecoration(int $result, string $text, string $search): array {
        $color = "§8";
        $tmp = substr($text, 0, $result);
        $beforeColor = strrchr($tmp, "§");
        if ($beforeColor !== false) {
            $color = "§" . ($beforeColor[2] ?? "8");
        }
        $text = substr_replace($text, $color, $result + strlen($search), 0);
        $text = substr_replace($text, $color !== "§e" ? "§e" : "§g", $result, 0);
        return [$color, $text];
    }

    public function strcut(string $text, int $offset, int $length): string {
        return mb_str_split(mb_strcut($text, $offset), $length)[0];
    }

    public function jsonSerialize() {
        return [
            'type' => 'custom_form',
            'title' => 'NotionForm',
            'content' => [
                [
                    'type' => 'label',
                    'text' => $this->error . "検索したいキーワードを入力してください",
                ],
                [
                    'type' => 'input',
                    'text' => '検索ワード',
                    'placeholder' => '',
                    'default' => $this->default[1] ?? ''
                ],
                [
                    'type' => 'input',
                    'text' => '検索ワード1',
                    'placeholder' => '',
                    'default' => $this->default[2] ?? ''
                ],
                [
                    'type' => 'input',
                    'text' => '検索ワード2',
                    'placeholder' => '',
                    'default' => $this->default[3] ?? ''
                ],
                [
                    "type" => "toggle",
                    "text" => "戻る",
                ]
            ],
        ];
    }

}