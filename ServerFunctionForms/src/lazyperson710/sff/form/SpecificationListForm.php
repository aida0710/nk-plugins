<?php

namespace lazyperson710\sff\form;

class SpecificationListForm extends FunctionListForm {

    public function __construct() {
        $this
            ->setTitle("Specification Form")
            ->setText("見たいコンテンツを選択してください")
            ->addFunction("Specification Form", "サーバーのメッセージに関して", "サーバーのメッセージの色は基本4色で統一されています\n\n§e黄色 -> システムの全体メッセージ\n§c赤 -> エラーやなど§gオレンジ -> Tutorialメッセージ\n§a黄緑 -> その他ほぼ全て\n\n§rになっています。")
            ->addFunction("Specification Form", "空腹値に関して", "lobbyとruleのワールドでは食べ物を食べれない代わりに空腹値の減少しないようになっています\n他のワールドでは通常通り減りますのでご注意ください")
            ->addFunction("Specification Form", "サーバーの再起動に関して", "サーバー再起動は24時間に一度行われます\n再起動の際に同時にワールドバックアップも行います")
            ->addFunction("Specification Form", "定期的なエンティティ削除に関して", "エンティティ削除は3分に一度行われます\nもし、アイテムが消えても返却などはできないためご注意ください")
            ->addFunction("Specification Form", "クラフトが禁止されているアイテムに関して", "アイテムのクラフトをできなくしているものがあります\nリストは作成しませんが基本防具関係\nまたは、バグを引き起こす可能性のあるアイテム。\n他の機能を持たせてしまっているブロックなどはクラフト不可にさせていただいております。")
            ->addFunction("Specification Form", "ワープした際に埋まる現象に関して", "ワープした際に埋まる現象が一部報告されていますがこれはクライアント側(スマホなど)の処理速度不足かネットの速度不足によるものだと思われ、サーバー側から修正することは難しいと考えています。\nお手数をお掛けし申し訳ございません。");
    }
}