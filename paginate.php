<?php
    $is_visible_next = false;
    $is_visible_prev = false;
    $is_visible_start = false;
    $is_visible_end = false;
?>
<?php if ($this->count > 0) :?>
    <?php for ($i = 1;$this->count >= $i;++$i) : ?>
        <?php if (($this->page - $this->margin) > $i) : ?>
            <?php if ($this->visible_margin_start_end && !$is_visible_start) : ?>
                <?php $is_visible_start = true; ?>
                <button
                    type="button"
                    value="<?php echo 1; ?>"
                    class="paginate_button"
                >
                    <<
                </button>
            <?php endif; ?>
            <?php if ($this->visible_margin_prev_next && !$is_visible_prev) : ?>
                <?php $is_visible_prev = true; ?>
                <button
                    type="button"
                    value="<?php echo $this->page - 1; ?>"
                    class="paginate_button"
                >
                    <
                </button>
            <?php endif; ?>
            <?php continue; ?>
        <?php elseif (($this->page + $this->margin) < $i) : ?>
            <?php if ($this->visible_margin_prev_next && !$is_visible_next) : ?>
                <?php $is_visible_next = true; ?>
                <button
                    type="button"
                    value="<?php echo $this->page + 1; ?>"
                    class="paginate_button"
                >
                    >
                </button>
            <?php endif; ?>
            <?php if ($this->visible_margin_start_end && !$is_visible_end) : ?>
                <?php $is_visible_end = true; ?>
                <button
                    type="button"
                    value="<?php echo $this->count; ?>"
                    class="paginate_button"
                >
                    >>
                </button>
            <?php endif; ?>
            <?php break; ?>
        <?php endif; ?>
        <button
            type="button"
            value="<?php echo $i; ?>"
            class="<?php echo $this->paginate_button_class; ?>"
            <?php if ((int)$this->page === (int)$i) :?>
                style="color: red;"
            <?php endif; ?>
        >
            <?php echo $i; ?>
        </button>
    <?php endfor; ?>
<?php endif; ?>
<?php if ($this->auto) :?>
    <?php echo $this->form_name; ?>
    <script>
        let buttons = document.getElementsByClassName('<?php echo $this->paginate_button_class; ?>');
        let page_input = document.querySelector('[name="<?php echo $this->page_name; ?>"]');
        for (let i = 0;buttons.length > i;++i) {
            buttons[i].addEventListener('click', function (e) {
                let page = e.target.value;
                let form = null;
                <?php if ($this->form_name) : ?>
                    form = document.querySelector('[name="<?php echo $this->form_name; ?>"]');
                    console.log(form);
                <?php else : ?>
                    form = this.closest('form') ?? document.querySelector('form');
                <?php endif; ?>
                page_input.value = page;
                form.submit();
            });
        }
    </script>
<?php endif; ?>