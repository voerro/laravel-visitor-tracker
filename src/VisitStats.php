<?php

namespace Voerro\Laravel\VisitorTracker;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class VisitStats
{
    /**
     * The SELECT part of the SQL query
     *
     * @var string
     */
    protected $sqlSelect = '';

    /**
     * Array of WHERE clauses
     *
     * @var array
     */
    protected $where = [];

    /**
     * Array of ORDER BY clauses
     *
     * @var array
     */
    protected $orderBy = [];

    /**
     * The LIMIT/OFFSET part of the SQL query
     *
     * @var string
     */
    protected $sqlLimitOffset = '';

    /**
     * The rest of the SQL query
     *
     * @var string
     */
    protected $sql = '';

    /**
     * A field to group the results by
     *
     * @var string
     */
    protected $groupBy;

    public static function routes()
    {
        Route::get('/stats', '\Voerro\Laravel\VisitorTracker\Controllers\StatisticsController@summary')->name('visitortracker.summary');
    }

    public static function query()
    {
        return new Self;
    }

    public function where($field, $symbol, $value)
    {
        array_push($this->where, [$field, $symbol, $value]);

        return $this;
    }

    public function except($fields)
    {
        if (in_array('login_attempts', $fields)) {
            $this->where('is_login_attempt', '!=', true);
        }

        if (in_array('bots', $fields)) {
            $this->where('is_bot', '!=', true);
        }

        if (in_array('ajax', $fields)) {
            $this->where('is_ajax', '!=', true);
        }

        return $this;
    }

    public function orderBy($field, $direction = 'ASC')
    {
        array_push($this->orderBy, [$field, $direction]);

        return $this;
    }

    public function groupBy($field)
    {
        $this->groupBy = $field;

        $this->sqlSelect .= ', v2.count';

        $this->sql .= "
            JOIN 
                (
                    SELECT {$field}, MAX(created_at) AS max_created_at, COUNT(*) AS count
                    FROM visitortracker_visits
                    GROUP BY {$field}
                ) v2
                ON v2.{$field} = v.{$field}
                AND v2.max_created_at = v.created_at
            GROUP BY v2.{$field}
        ";

        return $this;
    }

    protected function sqlWhere()
    {
        if (!count($this->where)) {
            return '';
        }

        $sql = ' WHERE';

        foreach ($this->where as $key => $value) {
            if ($key > 0) {
                $sql .= ' AND';
            }

            $sql .= " {$value[0]} {$value[1]} '{$value[2]}'";
        }

        return $sql;
    }

    protected function sqlOrderBy()
    {
        if (!count($this->orderBy)) {
            return '';
        }

        $sql = ' ORDER BY';

        foreach ($this->orderBy as $key => $value) {
            if ($key > 0) {
                $sql .= ',';
            }

            $sql .= " {$value[0]} {$value[1]}";
        }

        return $sql;
    }

    public function sql()
    {
        return $this->sqlSelect
            . $this->sql
            . $this->sqlOrderBy()
            . $this->sqlWhere()
            . $this->sqlLimitOffset;
    }

    public function count()
    {
        if ($this->groupBy) {
            $this->visits();
            $this->sqlSelect = "SELECT COUNT(DISTINCT {$this->groupBy}) AS total";
        } else {
            $this->sqlSelect = 'SELECT COUNT(*) AS total';
        }

        return $this->get()[0]->total;
    }

    public function period(Carbon $from, Carbon $to)
    {
        if ($from) {
            $this->where('created_at', '>=', $from);
        }

        if ($to) {
            $this->where('created_at', '<=', $to);
        }

        return $this;
    }

    public function paginate($perPage)
    {
        $totalCount = self::query()->visits()->count();

        $page = Paginator::resolveCurrentPage();
        $offset = ($page * $perPage) - $perPage;

        $this->sqlLimitOffset = " LIMIT {$perPage} OFFSET {$offset}";

        $results = $this->get();

        return new LengthAwarePaginator($results, $totalCount, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath()
        ]);
    }

    public function get()
    {
        $results = DB::select(DB::raw($this->sql()));

        return $results;
    }

    public function visits()
    {
        $this->sqlSelect = 'SELECT v.*';

        $this->sql = ' FROM visitortracker_visits v';

        return $this;
    }

    public function withUsers()
    {
        if ($table = config('visitortracker.users_table')) {
            $this->sqlSelect .= ', users.email AS user_email';

            $this->sql .= "
                LEFT JOIN {$table} users
                ON users.id = v.user_id
            ";
        }

        return $this;
    }

    public function latest()
    {
        return $this->orderBy('id', 'DESC');
    }

    public function loginAttempts()
    {
        return $this->where('is_login_attempt', '=', true);
    }

    public function bots()
    {
        return $this->where('is_bot', '=', true);
    }

    public function ajax()
    {
        return $this->where('is_ajax', '=', true);
    }

    public function unique()
    {
        return $this->groupBy('ip');
    }
}
