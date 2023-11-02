<?php

namespace Tests\Begien;

require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/../../src/config/config.php";

use PHPUnit\Framework\TestCase;
use Begien\Paginator;

class PaginatorTest extends TestCase {

    public function setUp(): void
    {
    }

    /**
     * @test
     * @dataProvider resultDataProvider
     */
    public function getResultSql($page, $result_view_count, $margin, $sql) {
        $_GET['page'] = $page;
        $paginator = new Paginator(
            connectDb(),
            'select * from users order by id asc',
            $result_view_count,
            $margin,
            Paginator::getDefaultOptions()
        );

        $this->assertSame(
            $sql,
            $paginator->getResultSql()
        );
    }

    /**
     * @test
     * @dataProvider countDataProvider
     */
    public function getCountSql($page, $result_view_count, $margin, $sql)
    {
        $_GET['page'] = $page;
        $paginator = new Paginator(
            connectDb(),
            'select * from users order by id asc',
            $result_view_count,
            $margin,
            Paginator::getDefaultOptions()
        );

        $this->assertSame(
            $sql,
            $paginator->getCountSql()
        );
    }

    public function resultDataProvider()
    {
        return [
            [
                'page' => 1,
                'result_view_count' => 1,
                'margin' => 1,
                'sql' => 'select * from users order by id asc LIMIT 0,1',
            ],
            [
                'page' => 1,
                'result_view_count' => 2,
                'margin' => 2,
                'sql' => 'select * from users order by id asc LIMIT 0,2',
            ],
            [
                'page' => 2,
                'result_view_count' => 3,
                'margin' => 3,
                'sql' => 'select * from users order by id asc LIMIT 3,3',
            ],
            [
                'page' => 2,
                'result_view_count' => 4,
                'margin' => 3,
                'sql' => 'select * from users order by id asc LIMIT 4,4',
            ],
        ];
    }

    public function countDataProvider()
    {
        return [
            [
                'page' => 1,
                'result_view_count' => 1,
                'margin' => 1,
                'sql' => 'select COUNT(*) from users order by id asc',
            ],
            [
                'page' => 1,
                'result_view_count' => 2,
                'margin' => 2,
                'sql' => 'select COUNT(*) from users order by id asc',
            ],
        ];
    }
}
