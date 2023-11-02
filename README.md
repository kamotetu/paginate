# SimplePaginator

シンプルなページネータを表示するライブラリです。

## install with composer

```
$ composer require begien/simplepaginator:~1.0
```

or

composer.json
```json
{
    "require": {
        "begien/simplepaginator": "~1.0"
    }
}
```
```
$ composer install
```


## Example

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/config.php';

use Begien\Paginator; // 👈 このパッケージをインポートします

$pdo = connectDb(); // 👈 PDOインスタンス

$result_view_count = 2; // 👈 検索結果の最大表示数
$paginate_margin = 2; // 👈 ページネータで表示するボタンの余白の数

if (isset($_GET['max'])) {
    $result_view_count = (int)h($_GET['max']);
}

$sql = 'select * from users order by id asc'; // 👈 "LIMIT"以外のsqlを作成

// 👇 ページネータ取得
$paginator = new Paginator(
    $pdo,
    $sql,
    $result_view_count,
    $paginate_margin,
);

$result = $paginator->result; // 👈 検索結果
$result_count = $paginator->result_count // 👈 ヒットしたデータの数
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
        <div>
            <?php echo $result_count; ?>件ヒットしました。
        </div>
        <form action="" method="get" name="form">
            <input type="hidden" name="page" value=""><!-- 👈 submitするフォームにhiddenで指定してください -->
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
        <?php $paginator->paginate(); ?><!-- 👈 ページネータを表示します -->
        <script>
            let max = document.getElementById('max');
            let max_form = document.querySelector('[name="form"]');
            max.addEventListener('change', function (e) {
                max_form.submit();
            });
        </script>
    </body>
</html>
```

![ezgif com-video-to-gif](https://github.com/kamotetu/simplepaginator/assets/54009505/bfa0eff8-2765-48a8-9ac8-79a9b9e56046)

### ページネータの表示

```php
<?php $paginator->paginate(); ?>
```
ページネータを表示します

### 必要なタグ

```html
<input type="hidden" name="page" value="">
```
ページネータのボタンを押した際に、そのボタンの数字をセットするinputを必ず指定してください。<br>
デフォルトでは"name=page"のフォームを検索して、valueをセットします

## options

```php
/**
 * @var array $options{
 *      paginate_path: string,
 *      visible_prev_next: bool,
 *      visible_start_end: bool,
 *      form_name: string,
 *      page_name: string,
 *      background_color: array{
 *          default: string,
 *          selected: string,
 *      },
 *      color: array{
 *          default: string,
 *          selected: string,
 *      },
 * }
 */
$options = Paginator::getDefaultOptions(); // 👈 デフォルトのオプションを取得できます
```

- paginate_path

  デフォルトで用意しているテンプレート以外を利用したい場合、そのテンプレートのパスを指定します<br>
  デフォルトではこのライブラリで用意したテンプレートのパスが指定されています。<br>
  以下はデフォルトテンプレートです。カスタムテンプレート作成時に参考にしてください。

```html
<?php
    $is_visible_next = false;
    $is_visible_prev = false;
    $is_visible_start = false;
    $is_visible_end = false;
?>
<style>
    .begien_paginate_wrapper button {
        border: 1px solid;
        border-radius: 5px;
        cursor: pointer;
        background-color: <?php echo $this->background_color['default']; ?>;
        color: <?php echo $this->color['default']; ?>;
    }
    .begien_paginate_wrapper button.selected {
        background-color: <?php echo $this->background_color['selected']; ?>;
        color: <?php echo $this->color['selected']; ?>;
    }
</style>
<?php if ($this->count > 0) :?>
    <div class="begien_paginate_wrapper">
        <?php for ($i = 1;$this->count >= $i;++$i) : ?>
            <?php if ($this->page > 1) : ?>
                <?php if ($this->visible_start_end && !$is_visible_start) : ?>
                    <?php $is_visible_start = true; ?>
                    <button
                        type="button"
                        value="<?php echo 1; ?>"
                        class="paginate_button"
                    >
                        <<
                    </button>
                <?php endif; ?>
                <?php if ($this->visible_prev_next && !$is_visible_prev) : ?>
                    <?php $is_visible_prev = true; ?>
                    <button
                        type="button"
                        value="<?php echo $this->page - 1; ?>"
                        class="paginate_button "
                    >
                        <
                    </button>
                <?php endif; ?>
            <?php endif;?>
            <?php if (($this->page - $this->margin) > $i) : ?>
                <?php continue; ?>
            <?php elseif (($this->page + $this->margin) < $i) : ?>
                <?php break; ?>
            <?php endif; ?>
            <button
                type="button"
                value="<?php echo $i; ?>"
                class="
                    paginate_button
                    <?php if ((int)$this->page === (int)$i) :?>
                        selected
                    <?php endif; ?>
                "

            >
                <?php echo $i; ?>
            </button>

        <?php endfor; ?>
        <?php if ($this->page < $this->count) : ?>
            <?php if ($this->visible_prev_next && !$is_visible_next) : ?>
                <?php $is_visible_next = true; ?>
                <button
                    type="button"
                    value="<?php echo $this->page + 1; ?>"
                    class="paginate_button"
                >
                    >
                </button>
            <?php endif; ?>
            <?php if ($this->visible_start_end && !$is_visible_end) : ?>
                <?php $is_visible_end = true; ?>
                <button
                    type="button"
                    value="<?php echo $this->count; ?>"
                    class="paginate_button"
                >
                    >>
                </button>
            <?php endif; ?>
        <?php endif; ?>
    </div>
<?php endif; ?>
<script>
    let buttons = document.getElementsByClassName('paginate_button');
    let page_input = document.querySelector('[name="<?php echo $this->page_name; ?>"]');
    for (let i = 0;buttons.length > i;++i) {
        buttons[i].addEventListener('click', function (e) {
            let page = e.target.value;
            let form = document.querySelector('[name="<?php echo $this->form_name; ?>"]');
            if (!form) {
                form = this.closest('form') ?? document.querySelector('form');
            }
            page_input.value = page;
            form.submit();
        });
    }
</script>
```

- visible_prev_next

  ページネータの"前へ","次へ"を表示するかどうかを指定します<br>
  デフォルト: true

- visible_start_end

  ページネータの"開始へ","最後へ"を表示するかどうかを指定します<br>
  デフォルト: true

- form_name

  ページネータのボタンをクリックした際、submitさせたい`<form>`のnameを指定します。<br>
  デフォルト: "form"

- page_name
  ページネータのボタンのvalueをセットする`<input>`のnameを指定します<br>
  デフォルト: "page"

- background_color
  ページネータのボタンの背景色を指定します<br>
  default: 未選択時の色<br>
  selected: 選択時の色<br>
  デフォルト: <br>
  &nbsp;&nbsp;&nbsp;&nbsp;default: buttonface<br>
  &nbsp;&nbsp;&nbsp;&nbsp;selected: aqua

- color
  ページネータのボタンの数字の色を指定します<br>
  default: 未選択時の色<br>
  selected: 選択時の色<br>
  デフォルト: <br>
  &nbsp;&nbsp;&nbsp;&nbsp;default: black<br>
  &nbsp;&nbsp;&nbsp;&nbsp;selected: black


## LICENCE
MIT

## Author
https://github.com/kamotetu<br>
https://begien.com

適当に使ってみてください。
