<?php

namespace lazyperson710\sff\form;

class FunctionServerListForm extends FunctionListForm {

    public function __construct() {
        $this
            ->setTitle("Function Form")
            ->setText("見たいコンテンツを選択してください")
            ->addFunction("Function Form", "レポート機能に関して", "レポート機能はサーバー内からformを使用してサーバー管理者に直接要望や報告などをする際に利用することを想定された機能です\n\n\nコマンドは/reportを使います")
            ->addFunction("Function Form", "ログインボーナスに関して", "ログインボーナスはその名の通りログインすると一日一回まで「login bouns」というアイテム(石臼)が配布されます\nこのアイテムは/bonusコマンドにて特定のアイテムに交換することが可能になっています\n\nもし、インベントリに空きがなかった場合は/bonusitemというコマンドから取得可能です。\n")
            ->addFunction("Function Form", "アイテムの修繕機能に関して", "つるはし等のアイテムを修繕したいときはかなとこをしゃがみながらタップすことで出てくるUIから修繕機能が利用可能です\n\n修繕に必要なコストはバニラ経験値で支払う形式になっておりコスト計算はエンチャント一種類につき５＋エンチャント1レベルにつき8レベルになっています\n\nまた、コマンドからでもformを出すことが出来ます\n\n修繕時に消滅する確率に関しては修繕する画面で見ることが出来ます")
            ->addFunction("Function Form", "チェストショップに関して", "チェストショップは鯖民が自分でショップを作成できる機能です\n作成方法はチェストに隣接させる形で看板を設置しその看板に\n§8空白\n一回の取引で売るアイテムの価格\n一回の取引で売るアイテムの個数\n一回の取引で売るアイテムのidか名前(idの場合はmeta値まで記入してください)§r\nと入力すると作成されます\nまた、チェストに販売アイテムがないと売ることが出来ません\n販売するアイテムはエンチャントやアイテムの名前、説明などが消えるのでご注意ください\n")
            ->addFunction("Function Form", "コマンド看板に関して", "プレイヤーが作成可能なコマンド看板は\n一行目 - ##cmd\n二行目 - 説明または空白\n三行目 - command\n四行目 - 説明または空白")
            ->addFunction("Function Form", "エリトラに関して", "エリトラはLevel Shop3で購入できる飛行系アイテムです\n装着するとクリエイティブ飛行をすることが出来ます\n\nエリトラのクリエイティブ飛行は、ほぼすべてのワールドで使用可能ですが、lobbyやrule、athleticなど一部のワールドでは使用できません\n")
            ->addFunction("Function Form", "マイニングツールに関して", "マイニングツールは/mtで購入できる範囲破壊ツールです\n\n2段階のランクがあり、一番下位のマイニングツールはダイヤモンドで上位がネザライトになります\n\n上位以外はかなとこ修繕が利用不可となります")
            ->addFunction("Function Form", "マイニングレベルに関して", "マイニングレベルはブロックを採掘したとき得られる経験値を基にしたレベルシステムになります\n経験値が入手できるワールドと経験値を入手出来るブロックは一部制限されています")
            ->addFunction("Function Form", "ネザースターについて", "ネザースターはレベルアップ時などにもらえる換金アイテムです\nShop1の一番下にある「換金アイテム」から売却することでお金に換えられるほか逆にお金からアイテムにもできるため少額渡したいけどわざわざ銀行から渡すのは面倒なんて時とかに使えるかもしれません\n")
            ->addFunction("Function Form", "お金の譲渡方法に関して", "お金を譲渡するには/payコマンドを使用してください\n\n他には換金アイテムのネザースターでも可能です")
            ->addFunction("Function Form", "メジャーの取得方法と利用方法に関して", "メジャーは建築補助用に作られた機能になります\n\n取得する方法は/majorと入力して実行すると手に入ります\n\n使用方法は一回目のタップで最初の地点を設定\n二回目以降のタップで一回目に指定した地点からの長さを図れます\n\nまた、起点の変更はスニークタップすることで削除できるのでまた、一回目のタップをしてください\n")
            ->addFunction("Function Form", "カスタムレシピについて", "カスタムレシピは\n/recipeから見ることができます\n")
            ->addFunction("Function Form", "nature-javaについて", "経験値が入らない代わりに完全にバニラのワールドで遊ぶことができます\n")
            ->addFunction("Function Form", "経験値について", "経験値の入手方法は経験値瓶、レベルブロック、鉱石などから取得することが出来ます");
    }
}