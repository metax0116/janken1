<?php
// **********************
// 人間 vs コンピュータ じゃんけん
// **********************

// 人間が手を選んだかどうか
$player_hand = $_POST['hand'] ?? null;

// じゃんけんの手
$hands = [
    'gu' => 'グー',
    'ch' => 'チョキ',
    'pa' => 'パー'
];

// 勝敗判定
$result = "";
$computer_hand_key = array_rand($hands);
$computer_hand = $hands[$computer_hand_key];

if ($player_hand !== null) {
    if ($player_hand === $computer_hand_key) {
        $result = "あいこ！";
    } elseif (
        ($player_hand === 'gu' && $computer_hand_key === 'ch') ||
        ($player_hand === 'ch' && $computer_hand_key === 'pa') ||
        ($player_hand === 'pa' && $computer_hand_key === 'gu')
    ) {
        $result = "あなたの勝ち！";
    } else {
        $result = "あなたの負け…";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>じゃんけんゲーム</title>
    <style>
        body { font-family: sans-serif; text-align: center; padding: 40px; }
        .hand-btn { padding: 10px 20px; font-size: 18px; margin: 10px; }
        .result { font-size: 24px; margin-top: 20px; }
    </style>
</head>
<body>

<h1>人間 vs コンピュータ じゃんけん</h1>

<form method="post">
    <button class="hand-btn" name="hand" value="gu">✊ グー</button>
    <button class="hand-btn" name="hand" value="ch">✌ チョキ</button>
    <button class="hand-btn" name="hand" value="pa">🖐 パー</button>
</form>

<?php if ($player_hand !== null): ?>
    <div class="result">
        <p>あなた：<?= $hands[$player_hand] ?></p>
        <p>コンピュータ：<?= $computer_hand ?></p>
        <p><strong><?= $result ?></strong></p>
    </div>
<?php endif; ?>

</body>
</html>
