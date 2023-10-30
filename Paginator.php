<?php

class Paginator
{
    /**
     * connected PDO
     *
     * @var PDO
     */
    private $pdo;

    /**
     * without LIMIT sql query string
     *
     * @var string
     */
    private $base_sql;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $order_by;
    /**
     * paginate php file path
     *
     * @var string
     */
    private $paginate_path;

    /**
     *
     *
     * @var string
     */
    private $paginate_button_class;

    /**
     * Undocumented variable
     *
     * @var string
     */
    private $form_name;

    /**
     * requested page
     *
     * @var string
     */
    private $page_name;

    /**
     * visible button of move one from current
     *
     * @var ?bool
     */
    private $visible_margin_prev_next;

    /**
     * visible button of start and end
     *
     * @var ?bool
     */
    private $visible_margin_start_end;

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
     * Undocumented variable
     *
     * @var html
     */
    public $paginate;

    public $count;

    /**
     * page value
     *
     * @var ?int
     */
    private $page;

    public function __construct(
        PDO $pdo,
        string  $base_sql,
        string $order_by,
        int $result_view_count,
        int $margin,
        ?string $paginate_path,
        bool $visible_margin_prev_next = false,
        bool $visible_margin_start_end = false,
        string $form_name = 'form',
        string $page_name = 'page',
    )
    {
        $this->pdo = $pdo;
        $this->base_sql = $base_sql;
        $this->order_by = $order_by;
        $this->result_view_count = $result_view_count;
        $this->margin = $margin;
        $this->paginate_path = $paginate_path;
        $this->visible_margin_prev_next = $visible_margin_prev_next;
        $this->visible_margin_start_end = $visible_margin_start_end;
        $this->form_name = $form_name;
        $this->page_name = $page_name;

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
        $paginate_path = $this->paginate_path;
        if (!$paginate_path) {
            $paginate_path = __DIR__ . '/paginate.php';
        }
        require_once $paginate_path;
    }
}
