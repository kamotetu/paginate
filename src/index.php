<?php

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/Begien/Paginator/Paginator.php';

$pdo = connectDb();
var_dump($pdo);

$result_view_count = 2;
$paginate_margin = 2;

if (isset($_GET['max'])) {
    $result_view_count = (int)h($_GET['max']);
}

$sql = 'select * from users order by id desc';

$options = Begien\Paginator\Paginator::getDefaultOptions();
$options['form_name'] = 'hoge';

$paginator = new Begien\Paginator\Paginator(
    $pdo,
    $sql,
    $result_view_count,
    $paginate_margin,
    $options,
);

$result = $paginator->result;
$paginate_button_quantity = $paginator->count;
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>ページネーションテスト</title>
    </head>
    <body>
        <style>
            .paginate_button {
                margin: 0 auto;
            }
        </style>
        <form action="" method="get" name="form">
            <input type="hidden" name="page" value="">
            <label for="max">表示件数</label>
            <select id="max" name="max">
                <?php for ($i = 1;5 > $i;++$i) : ?>
                    <option
                        value="<?php echo $i; ?>"
                        <?php if ((int)$result_view_count === (int)$i) :?>
                            selected="selected"
                        <?php endif; ?>
                    >
                        <?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
        </form>
        <ul>
            <?php foreach ($result as $key => $user) : ?>
                <li><?php echo $user['name'] ?></li>
            <?php endforeach; ?>
        </ul>
        <?php $paginator->paginate(); ?>
        <script>
            let max = document.getElementById('max');
            let max_form = document.querySelector('[name="form"]');
            max.addEventListener('change', function (e) {
                max_form.submit();
            });
        </script>
    </body>
</html>
