<?php

namespace Application\Database;

class QueryBuilder
{
    protected static $sql = '';
    protected static $instance = null;
    protected static $prefix = '';
    protected static $where = [];
    protected static $control = ['', ''];

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

    /**
     * Query builder NOT operator
     */
    public static function not(string $condition): QueryBuilder
    {
        self::$where[] = trim('NOT ' . $condition);
        return self::$instance;
    }

    /**
     * Query builder LIMIT clause
     */
    public static function limit(int $limit): QueryBuilder
    {
        self::$control[0] = 'LIMIT ' . $limit;
        return self::$instance;
    }

    /**
     * Query builder OFFSET clause
     */
    public static function offset(int $offset): QueryBuilder
    {
        self::$control[1] = 'OFFSET ' . $offset;
        return self::$instance;
    }

    /**
     * Get the query built by the Query builder
     */
    public static function getSQL(): string
    {
        self::$sql = self::$prefix . implode(' ', self::$where) . ' ' . self::$control[0] . ' ' . self::$control[1];
        return trim(preg_replace('/  /', ' ', self::$sql));
    }
}
