<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/Paginator.php';

$pdo = connectDb();

$page = null;
$result_view_count = 2;
$paginate_margin = 2;
$visible_margin_prev_next = true;
$visible_margin_start_end = true;
$paginate_path = __DIR__ . '/paginate.php';
$is_visible_prev = false;
$is_visible_next = false;
$is_visible_start = false;
$is_visible_end = false;
$form_name = 'form';
$page_name = 'page';

// if (isset($_GET['max'])) {
//     $result_view_count = (int)h($_GET['max']);
// }

$sql = 'select * from users';

$paginator = new Paginator(
    $pdo,
    $sql,
    $result_view_count,
    $paginate_margin,
    $paginate_path,
    $visible_margin_prev_next,
    $visible_margin_start_end,
    $form_name,
    $page_name,

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
        <form action="" method="get" name="<?php echo $form_name; ?>">
            <input type="hidden" name="<?php echo $page_name; ?>" value="">
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
