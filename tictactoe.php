<?php
session_start();

// 初期化
if (!isset($_SESSION['board']) || isset($_POST['reset'])) {
    $_SESSION['board'] = array_fill(0, 9, ''); // 0..8
    $_SESSION['turn'] = 'X';
    $_SESSION['winner'] = null;
    $_SESSION['message'] = 'ゲーム開始: X の番です';
}

// POST でセルを指定されたら処理
if (isset($_POST['move']) && $_SESSION['winner'] === null) {
    $move = intval($_POST['move']);
    if ($move >= 0 && $move < 9 && $_SESSION['board'][$move] === '') {
        $_SESSION['board'][$move] = $_SESSION['turn'];
        // 勝敗判定
        $winner = check_winner($_SESSION['board']);
        if ($winner !== null) {
            $_SESSION['winner'] = $winner;
            $_SESSION['message'] = "勝者: {$winner}！";
        } elseif (is_draw($_SESSION['board'])) {
            $_SESSION['winner'] = 'Draw';
            $_SESSION['message'] = "引き分けです。";
        } else {
            // 手番交代
            $_SESSION['turn'] = ($_SESSION['turn'] === 'X') ? 'O' : 'X';
            $_SESSION['message'] = "{$_SESSION['turn']} の番です";
        }
    } else {
        $_SESSION['message'] = "そのマスは選べません。";
    }
}

// 勝者判定関数
function check_winner($b) {
    $lines = [
        [0,1,2], [3,4,5], [6,7,8], // rows
        [0,3,6], [1,4,7], [2,5,8], // cols
        [0,4,8], [2,4,6]           // diags
    ];
    foreach ($lines as $line) {
        list($a,$c,$d) = $line;
        if ($b[$a] !== '' && $b[$a] === $b[$c] && $b[$a] === $b[$d]) {
            return $b[$a];
        }
    }
    return null;
}

// 引き分け判定
function is_draw($b) {
    foreach ($b as $cell) {
        if ($cell === '') return false;
    }
    return true;
}
?>
<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>三目並べ（PHP）</title>
<style>
    body { font-family: system-ui, -apple-system, "Yu Gothic UI", "Hiragino Kaku Gothic ProN", Meiryo, sans-serif; padding: 20px; }
    .board { display: grid; grid-template-columns: repeat(3, 100px); gap: 6px; margin-bottom: 12px; }
    .cell { width: 100px; height: 100px; display:flex; align-items:center; justify-content:center; font-size:40px; border:2px solid #444; background:#f7f7f7; cursor:pointer; }
    .cell.disabled { background:#eaeaea; cursor:default; color:#666; }
    .info { margin-bottom: 12px; font-size:18px; }
    form.inline { display:inline-block; margin:0; }
    button.reset { margin-left: 12px; padding:6px 10px; }
</style>
</head>
<body>
    <h1>三目並べ（PHP）</h1>
    <div class="info">
        <?php echo htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8'); ?>
        <?php if ($_SESSION['winner'] !== null): ?>
            <form method="post" class="inline" style="margin-left:10px;">
                <button type="submit" name="reset" value="1" class="reset">リセット</button>
            </form>
        <?php endif; ?>
    </div>

    <div class="board">
        <?php for ($i = 0; $i < 9; $i++): ?>
            <?php $val = $_SESSION['board'][$i]; ?>
            <?php if ($val === '' && $_SESSION['winner'] === null): ?>
                <form method="post" class="inline">
                    <input type="hidden" name="move" value="<?php echo $i; ?>">
                    <button type="submit" class="cell"><?php echo '&nbsp;'; ?></button>
                </form>
            <?php else: ?>
                <div class="cell disabled"><?php echo ($val === '') ? '&nbsp;' : htmlspecialchars($val, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>
        <?php endfor; ?>
    </div>

    <?php if ($_SESSION['winner'] === null): ?>
        <form method="post" style="display:inline-block;">
            <button type="submit" name="reset" value="1" class="reset">途中リセット</button>
        </form>
    <?php endif; ?>

    <hr>
    <p>遊び方：マスをクリックして交互に置きます。X と O の二人対戦です。勝者が決まるか引き分けでゲーム終了します。</p>
</body>
</html>
