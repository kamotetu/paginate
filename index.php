<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/Paginator.php';

$pdo = connectDb();

$page = null;
$max = 3;
$start = null;
$paginate_margin = 2;
$prev = null;
$visible_margin_prev_next = true;
$visible_margin_start_end = true;
$is_visible_prev = false;
$is_visible_next = false;
$is_visible_start = false;
$is_visible_end = false;
$paginate_button_class = 'paginate_button';
$form_name = 'form';
$page_name = 'page';

if (!empty($_GET['page'])) {
    $page = (int)h($_GET['page'] ?? 1);
} else {
    $page = 1;
}
if (isset($_GET['max'])) {
    $max = (int)h($_GET['max']);
}

if ($page > 0) {
    $start = ($max * $page) - $max;
}

$sql = 'select * from users';

$paginator = new Paginator(
    $pdo,
    $sql,
    $max,
    $paginate_margin,
    $visible_margin_prev_next,
    $visible_margin_start_end,
    true,
    $paginate_button_class,
    $form_name,
    $page_name
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
        <form action="" method="get" name="<?php echo $form_name; ?>">
            <input type="hidden" name="<?php echo $page_name; ?>" value="">
            <label for="max">表示件数</label>
            <select id="max" name="max">
                <?php for ($i = 1;5 > $i;++$i) : ?>
                    <option
                        value="<?php echo $i; ?>"
                        <?php if ((int)$max === (int)$i) :?>
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
            // let buttons = document.getElementsByClassName('paginate_button');
            // let page_input = document.querySelector('[name="paginate_page"]');
            // for (let i = 0;buttons.length > i;++i) {
            //     buttons[i].addEventListener('click', function (e) {
            //         let page = e.target.value;
            //         page_input.value = page;
            //         max_form.submit();
            //     });
            // }
        </script>
        
    </body>
</html>