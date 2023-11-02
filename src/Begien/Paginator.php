<?php

namespace Begien;

class Paginator
{
    private const DEFAULT_PAGINATE_PATH = __DIR__ . '/template/paginate.php';

    private const DEFAULT_VISIBLE_PREV_NEXT = true;

    private const DEFAULT_VISIBLE_START_END = true;

    private const DEFAULT_FORM_NAME = 'form';

    private const DEFAULT_PAGE_NAME = 'page';

    private const DEFAULT_BACKGROUND_COLOR_DEFAULT = 'buttonface';

    private const DEFAULT_BACKGROUND_COLOR_SELECTED = 'aqua';

    private const DEFAULT_COLOR_DEFAULT = 'black';

    private const DEFAULT_COLOR_SELECTED = 'black';

    /**
     * connected PDO
     *
     * @var \PDO
     */
    public $pdo;

    /**
     * without LIMIT sql query string
     *
     * @var string
     */
    public $base_sql;

    /**
     * paginate php file path
     *
     * @var ?string
     */
    public $paginate_path = self::DEFAULT_PAGINATE_PATH;

    /**
     * Undocumented variable
     *
     * @var string
     */
    public $form_name = self::DEFAULT_FORM_NAME;

    /**
     * requested page
     *
     * @var string
     */
    public $page_name = self::DEFAULT_PAGE_NAME;

    /**
     * visible button of move one from current
     *
     * @var bool
     */
    public $visible_prev_next = self::DEFAULT_VISIBLE_PREV_NEXT;

    /**
     * visible button of start and end
     *
     * @var bool
     */
    public $visible_start_end = self::DEFAULT_VISIBLE_START_END;

    /**
     * visible buttons limit count
     *
     * @var ?int
     */
    public $result_view_count;

    /**
     * visible margin buttons from current
     *
     * @var ?int
     */
    public $margin;

    /**
     * query result
     *
     * @var array
     */
    public $result = [];

    /**
     * count of result
     * @var int
     */
    public $result_count;

    /**
     * create paginate html
     *
     * @var html
     */
    public $paginate;

    /**
     * count of paginate buttons
     *
     * @var int
     */
    public $count;

    /**
     * current page
     *
     * @var int
     */
    public $page;

    public $background_color = [
        'default' => self::DEFAULT_BACKGROUND_COLOR_DEFAULT,
        'selected' => self::DEFAULT_BACKGROUND_COLOR_SELECTED
    ];

    public $color = [
        'default' => self::DEFAULT_COLOR_DEFAULT,
        'selected' => self::DEFAULT_COLOR_SELECTED
    ];

    /**
     * option values
     *
     * @var array{
     * paginate_path: ?string,
     * visible_prev_next: ?bool,
     * visible_start_end: ?bool,
     * form_name: ?string,
     * page_name: ?string,
     * }
     */
    private static $default_options = [
        'paginate_path' => self::DEFAULT_PAGINATE_PATH,
        'visible_prev_next' => self::DEFAULT_VISIBLE_PREV_NEXT,
        'visible_start_end' => self::DEFAULT_VISIBLE_START_END,
        'form_name' => self::DEFAULT_FORM_NAME,
        'page_name' => self::DEFAULT_PAGE_NAME,
        'background_color' => [
            'default' => self::DEFAULT_BACKGROUND_COLOR_DEFAULT,
            'selected' => self::DEFAULT_BACKGROUND_COLOR_SELECTED
        ],
        'color' => [
            'default' => self::DEFAULT_COLOR_DEFAULT,
            'selected' => self::DEFAULT_COLOR_SELECTED
        ]
    ];

    public function __construct(
        \PDO $pdo,
        string  $base_sql,
        int $result_view_count,
        int $margin,
        array $options = []
    )
    {
        $this->pdo = $pdo;
        $this->base_sql = $base_sql;
        $this->result_view_count = $result_view_count;
        $this->margin = $margin;
        $this->initOptions($options);
        $this->initPage();
        $this->initResult();
        $this->initCount();
        $this->initResultCount();
    }

    private function initOptions($options)
    {
        if (isset($options['paginate_path'])) {
            $this->paginate_path = $options['paginate_path'];
        }
        if (isset($options['visible_prev_next'])) {
            $this->visible_prev_next = $options['visible_prev_next'];
        }
        if (isset($options['visible_start_end'])) {
            $this->visible_start_end = $options['visible_start_end'];
        }
        if (isset($options['form_name'])) {
            $this->form_name = $options['form_name'];
        }
        if (isset($options['page_name'])) {
            $this->page_name = $options['page_name'];
        }
        if (isset($options['background_color']['default'])) {
            $this->background_color['default'] = $options['background_color']['default'];
        }
        if (isset($options['background_color']['selected'])) {
            $this->background_color['selected'] = $options['background_color']['selected'];
        }
        if (isset($options['color']['default'])) {
            $this->color['default'] = $options['color']['default'];
        }
        if (isset($options['color']['selected'])) {
            $this->color['selected'] = $options['color']['selected'];
        }
    }

    private function initPage()
    {
        $this->page = isset($_GET[$this->page_name])
            ? (int)$_GET[$this->page_name]
            : 1;
    }

    private function initResult()
    {
        $sql = $this->getResultSql();
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $this->result = $stmt->fetchAll();
    }

    public function getResult()
    {
        return $this->result;
    }

    public function getResultSql()
    {
        if ($this->page < 1) {
            $this->page = 1;
        }
        $start = ((int)$this->result_view_count * (int)$this->page) - (int)$this->result_view_count;
        return $this->base_sql . ' LIMIT ' . $start . ',' . $this->result_view_count;
    }

    private function initCount(): void
    {
        $sql = $this->getCountSql();
        $stml = $this->pdo->prepare($sql);
        $stml->execute();
        $result = $stml->fetchColumn();
        $count = (int)ceil($result / $this->result_view_count);
        if ($count < 1) {
            $count = 1;
        }
        $this->count = $count;
    }

    public function getCountSql()
    {
        return preg_replace('/\A(SELECT)(.+)(FROM)/ui', '$1 COUNT(*) $3', $this->base_sql);
    }

    public function getCount()
    {
        return $this->count;
    }

    private function initResultCount()
    {
        $sql = $this->getCountSql();
        $stml = $this->pdo->prepare($sql);
        $stml->execute();
        $this->result_count = $stml->fetchColumn();
    }

    public function getResultCount()
    {
        return $this->result_count;
    }

    public function paginate()
    {
        require_once $this->paginate_path;
    }

    /**
     * @return array{
     * paginate_path: ?string,
     * visible_prev_next: ?bool,
     * visible_start_end: ?bool,
     * form_name: ?string,
     * page_name: ?string,
     * }
     */
    public static function getDefaultOptions(): array
    {
        return self::$default_options;
    }
}
