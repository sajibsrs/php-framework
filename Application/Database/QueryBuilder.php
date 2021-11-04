<?php

namespace Application\Database;

class QueryBuilder
{
    public static $sql = '';
    public static $instance = null;
    public static $prefix = '';
    public static $where = [];
    public static $control = ['', ''];

    /**
     * Query builder SELECT statement
     * 
     * @param string $table Table name.
     * @param string|null $columns Column name. Comma separated if there's more than one.
     * If none provided *SELECT * (ALL)* will be applied.
     * @return Application\Database\Database Database instance.
     */
    public static function select(string $table, string $columns = null): QueryBuilder
    {
        self::$instance = new QueryBuilder();

        if($columns) {
            self::$prefix = 'SELECT ' . $columns . ' FROM ' . $table;
        } else {
            self::$prefix = 'SELECT * FROM ' . $table;
        }
        
        return self::$instance;
    }

    /**
     * Query builder WHERE clause
     */
    public static function where(string $condition = null): QueryBuilder
    {
        self::$where[0] = ' WHERE ' . $condition;
        return self::$instance;
    }

    /**
     * Query builder LIKE operator
     */
    public static function like(string $column, string $pattern): QueryBuilder
    {
        self::$where[] = trim($column . ' LIKE ' . $pattern);
        return self::$instance;
    }

    /**
     * Query builder AND operator
     */
    public static function and(string $condition = null): QueryBuilder
    {
        self::$where[] = trim('AND ' . $condition);
        return self::$instance;
    }

    /**
     * Query builder OR operator
     */
    public static function or(string $condition = null): QueryBuilder
    {
        self::$where[] = trim('OR ' . $condition);
        return self::$instance;
    }

    /**
     * Query builder IN operator
     */
    public static function in(array $values): QueryBuilder
    {
        self::$where[] = 'IN ( ' . implode(',', $values) . ' )';
        return self::$instance;
    }
}
