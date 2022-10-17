<?php

class Main {

    public const DefaultData = [
        "Donation" => [
            1500 => false,
            3000 => false,
            5000 => false,
            8000 => false,
            10000 => false,
            15000 => false,
        ],
        "tutorial" => [
            "enableForm" => true,
            "progress" => [
                "コマンドを実行してformを表示してみよう" => false,
                "天然資源1に行ってみよう" => false,
                "石を64個採掘してみよう" => false,
                "/shopを実行して石を売却してみよう" => false,
                "ガチャを引いてみよう！" => false,
                "道具を修繕しよう！" => false,
                "/efからエフェクトを付与してみよう" => false,
                "/bonusからログインボーナスを交換してみよう!" => false,
            ],
        ],
        "achievement" => [
            "パスワードを解除する" => [
                "unLock" => false,
                "notification" => false,
            ],
            "パスワードを解除する34" => [
                "unLock" => false,
                "notification" => false,
            ],
        ],
    ];

    public const PlayerData = [
        "Donation" => [
            1500 => false,
            3000 => false,
            //5000 => false,
            8000 => false,
            10000 => false,
            //15000 => false,
        ],
        "tutorial" => [
            "enableForm" => true,
            "progress" => [
                "コマンドを実行してformを表示してみよう" => false,
                "天然資源1に行ってみよう" => false,
                "石を64個採掘してみよう" => false,
                "/shopを実行して石を売却してみよう" => false,
                "ガチャを引いてみよう！" => false,
                "道具を修繕しよう！" => false,
                "/efからエフェクトを付与してみよう" => false,
                "/bonusからログインボーナスを交換してみよう!" => false,
            ],
        ],
        "achievement" => [
            "パスワードを解除する" => [
                "unLock" => false,
                "notification" => false,
            ],
            //"パスワードを解除する34" => [
            //    "unLock" => false,
            //    "notification" => false,
            //],
        ],
    ];

    public function main(string $type, array $content): void {
        $player = self::PlayerData;
        $default = self::DefaultData;
    }
}

(new Main())->main();

