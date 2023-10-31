<?php

namespace Begien\Paginator;

class Paginator
{
    private const DEFAULT_PAGINATE_PATH = __DIR__ . '/../template/paginate.php';
    /**
     * connected PDO
     *
     * @var \PDO
     */
    private $pdo;

    /**
     * without LIMIT sql query string
     *
     * @var string
     */
    private $base_sql;

    /**
     * paginate php file path
     *
     * @var ?string
     */
    private $paginate_path = self::DEFAULT_PAGINATE_PATH;

    /**
     * Undocumented variable
     *
     * @var string
     */
    private $form_name = 'form';

    /**
     * requested page
     *
     * @var string
     */
    private $page_name = 'page';

    /**
     * visible button of move one from current
     *
     * @var bool
     */
    private $visible_prev_next = true;

    /**
     * visible button of start and end
     *
     * @var bool
     */
    private $visible_start_end = true;

    /**
     * visible buttons limit count
     *
     * @var ?int
     */
    private $result_view_count;

    /**
     * visible margin buttons from current
     *
     * @var ?int
     */
    private $margin;

    /**
     * query result
     *
     * @var array
     */
    public $result = [];

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
    private $page;

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
        'visible_prev_next' => true,
        'visible_start_end' => true,
        'form_name' => 'form',
        'page_name' => 'page',
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

        $this->page = isset($_GET[$this->page_name])
            ? (int)$_GET[$this->page_name]
            : 1;
        $this->result = $this->getResult();
        $this->count = $this->getCount();
    }

    private function getResult()
    {
        if ($this->page < 1) {
            $this->page = 1;
        }
        $start = ((int)$this->result_view_count * (int)$this->page) - (int)$this->result_view_count;
        $sql = $this->base_sql . ' ' . ' LIMIT ' . $start . ',' . $this->result_view_count;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    private function getCount()
    {
        $sql = preg_replace('/\A(SELECT)(.+)(FROM)/ui', '$1 COUNT(*) $3', $this->base_sql);
        $stml = $this->pdo->prepare($sql);
        $stml->execute();
        $result = $stml->fetchColumn();
        $count = (int)ceil($result / $this->result_view_count);
        if ($count < 1) {
            $count = 1;
        }
        return $count;
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
