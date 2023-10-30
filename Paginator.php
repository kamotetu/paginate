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
     * @var bool
     */
    private $auto;

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
    private $max;

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
        int $max,
        int $margin,
        bool $visible_margin_prev_next = false,
        bool $visible_margin_start_end = false,
        bool $auto = true,
        string $paginate_button_class = 'paginate_button',
        string $form_name = 'form',
        string $page_name = 'page',
    )
    {
        $this->pdo = $pdo;
        $this->base_sql = $base_sql;
        $this->max = $max;
        $this->margin = $margin;
        $this->visible_margin_prev_next = $visible_margin_prev_next;
        $this->visible_margin_start_end = $visible_margin_start_end;
        $this->auto = $auto;
        $this->paginate_button_class = $paginate_button_class;
        $this->form_name = $form_name;
        $this->page_name = $page_name;

        $this->page = isset($_GET[$this->page_name])
            ? (int)$_GET[$this->page_name]
            : 1;
        $this->result = $this->getResult();
        $this->getCount();

    }

    private function getResult()
    {
        var_dump($this->page);
        $start = ((int)$this->max * (int)$this->page) - (int)$this->max;
        $sql = $this->base_sql . ' ' . ' LIMIT ' . $start . ',' . $this->max;
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
        $this->count = (int)ceil($result / $this->max);
    }

    public function paginate()
    {
        require_once "./paginate.php";
    }
}
