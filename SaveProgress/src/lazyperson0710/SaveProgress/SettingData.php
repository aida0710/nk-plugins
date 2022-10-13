<?php

namespace lazyperson0710\SaveProgress;

class SettingData {

    public const DefaultData = [
        "Donation" => [
            1500 => false,
            3000 => false,//機能的解放の為trueになることはない
            5000 => false,//3000と同様
            8000 => false,
            10000 => false,
            15000 => false,
        ],
        //idea 現在行っているチュートリアルを強制スキップして次のチュートリアルに進めるようにする
        // Tutorial自体を強制的に終わらすことも可能
        // 現在のやること(Tutorial)はbossBarにて進行度を表示
        "tutorial" => [//idea 進行度 飛ばした場合は全部trueに
            "enableForm" => true,
            "progress" => [
                "コマンドを実行してformを表示してみよう",
                "天然資源1に行ってみよう",
                "石を64個採掘してみよう",
                "/shopを実行して石を売却してみよう",
                //ここらへんで貰ったアイテムをタップしたら楽にコマンドが実行できます！
                "ガチャを引いてみよう！",
                "道具を修繕しよう！",
                "/efからエフェクトを付与してみよう",
                "/bonusからログインボーナスを交換してみよう!",
                //idea パスワードを解除してロビーにワープしたら
                // 1: /wpコマンドを入力するか看板をタップしてワールドを移動してみよう
                // 2: 天然資源2でブロックを採掘してみよう
                // 3: ブロック(丸石)を15個採掘したら/shopコマンドを入力して売却を行ってみようという表示
                // 売却をしてるかの判定を行うために売却、購入時にイベント発生させたい
                // 4:
            ],
        ],
        "achievement" => [//note 通知してるかどうか, 達成してるかどうか
            "パスワードを解除する" => [
                "unLock" => false,
                "notification" => false,
            ],
            "落下ダメージを受ける" => [
                "unLock" => false,
                "notification" => false,
            ],
            "準備はOKだ……" => [
                "unLock" => false,
                "notification" => false,
            ],
            "おめでとう！" => [
                "unLock" => false,
                "notification" => false,
            ],
            "あなた大丈夫？" => [
                "unLock" => false,
                "notification" => false,
            ],
            "カイジ" => [
                "unLock" => false,
                "notification" => false,
            ],
            "You cheated!" => [
                "unLock" => false,
                "notification" => false,
            ],
            "カフェイン摂取" => [
                "unLock" => false,
                "notification" => false,
            ],
            "初心者レーサー" => [
                "unLock" => false,
                "notification" => false,
            ],
            "プロレーサー" => [
                "unLock" => false,
                "notification" => false,
            ],
            "ボクサー" => [
                "unLock" => false,
                "notification" => false,
            ],
            "お金持ち！" => [
                "unLock" => false,
                "notification" => false,
            ],
            "お前がナンバーワンだ……" => [
                "unLock" => false,
                "notification" => false,
            ],
            "石油王" => [
                "unLock" => false,
                "notification" => false,
            ],
            "ビルダー" => [
                "unLock" => false,
                "notification" => false,
            ],
            "初めの1歩" => [
                "unLock" => false,
                "notification" => false,
            ],
            "水色のゴミ？" => [
                "unLock" => false,
                "notification" => false,
            ],
            "実はお宝" => [
                "unLock" => false,
                "notification" => false,
            ],
            "ラッキーボーイ" => [
                "unLock" => false,
                "notification" => false,
            ],
            "ただ事じゃない……" => [
                "unLock" => false,
                "notification" => false,
            ],
            "範馬刃牙" => [
                "unLock" => false,
                "notification" => false,
            ],
            "範馬勇次郎" => [
                "unLock" => false,
                "notification" => false,
            ],
            "集中集中" => [
                "unLock" => false,
                "notification" => false,
            ],
            "あちち" => [
                "unLock" => false,
                "notification" => false,
            ],
            "あれ？誰もいない……" => [
                "unLock" => false,
                "notification" => false,
            ],
            "弓を鍛えし者" => [
                "unLock" => false,
                "notification" => false,
            ],
            "オートエイム" => [
                "unLock" => false,
                "notification" => false,
            ],
            "パルクーラー" => [
                "unLock" => false,
                "notification" => false,
            ],
            "魔法使い" => [
                "unLock" => false,
                "notification" => false,
            ],
            "剣豪" => [
                "unLock" => false,
                "notification" => false,
            ],
            "三刀流！" => [
                "unLock" => false,
                "notification" => false,
            ],
            "背中の傷は剣士の恥" => [
                "unLock" => false,
                "notification" => false,
            ],
            "もったいねぇ" => [
                "unLock" => false,
                "notification" => false,
            ],
            "建築家" => [
                "unLock" => false,
                "notification" => false,
            ],
            "匠の建築" => [
                "unLock" => false,
                "notification" => false,
            ],
            "建築ばか" => [
                "unLock" => false,
                "notification" => false,
            ],
            "神殺し" => [
                "unLock" => false,
                "notification" => false,
            ],
            "モデル" => [
                "unLock" => false,
                "notification" => false,
            ],
            "猛者殺し" => [
                "unLock" => false,
                "notification" => false,
            ],
            "ATM" => [
                "unLock" => false,
                "notification" => false,
            ],
            "モグラ" => [
                "unLock" => false,
                "notification" => false,
            ],
            "セールスマン" => [
                "unLock" => false,
                "notification" => false,
            ],
            "成功したセールスマン" => [
                "unLock" => false,
                "notification" => false,
            ],
            "運の無駄使い" => [
                "unLock" => false,
                "notification" => false,
            ],
            "おしゃべりさん" => [
                "unLock" => false,
                "notification" => false,
            ],
            "Japanese NINJA！" => [
                "unLock" => false,
                "notification" => false,
            ],
            "暑さ対策" => [
                "unLock" => false,
                "notification" => false,
            ],
            "いただきます" => [
                "unLock" => false,
                "notification" => false,
            ],
            "夏祭り！" => [
                "unLock" => false,
                "notification" => false,
            ],
            "超高級ブーツ" => [
                "unLock" => false,
                "notification" => false,
            ],
        ],
        //idea 実績の追加
        // 通知してるかどうかの進行に関しても必要かも ["実績名" => true,false]
        // 落下ダメージを受ける
        // 落下ダメージを無効化する
        // 落下ダメージで死ぬ
        // 死んだときに100万以上失う
        // 採掘数に関して10万, 80万, 100万, 250万, 300万など
        // 1日に100万以上の金額を稼ぐ 銀行で判定,24時に判定
        // 1日に100万以上の金額を失う
        //idea https://discord.com/channels/550022116158865468/993153749851848864/1025816856763838505
    ];
}