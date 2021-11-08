<?php

namespace Application\Database;

class QueryBuilder
{
    protected static $sql = '';
    protected static $instance = null;
    protected static $prefix = '';
    protected static $where = [];
    protected static $control = [];

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

        if ($columns) {
            self::$prefix = 'SELECT ' . $columns . ' FROM ' . $table;
        } else {
            self::$prefix = 'SELECT * FROM ' . $table;
        }
        
        return self::$instance;
    }

    /**
     * Query builder WHERE clause
     * 
     * @param string|null $condition
     * @return \Application\Database\QueryBuilder
     */
    public static function where(string $condition = null): QueryBuilder
    {
        self::$where[0] = ' WHERE ' . $condition;
        return self::$instance;
    }

    /**
     * Query builder LIKE operator
     * 
     * @param string $column Column name
     * @param string $pattern Match pattern
     * @return \Application\Database\QueryBuilder
     * @see https://dev.mysql.com/doc/refman/8.0/en/pattern-matching.html
     */
    public static function like(string $column, string $pattern): QueryBuilder
    {
        self::$where[] = trim($column . ' LIKE ' . $pattern);
        return self::$instance;
    }

    /**
     * Query builder AND operator
     * 
     * @param string|null $condition
     * @return \Application\Database\QueryBuilder
     */
    public static function and(string $condition = null): QueryBuilder
    {
        self::$where[] = trim('AND ' . $condition);
        return self::$instance;
    }

    /**
     * Query builder OR operator
     * 
     * @param string|null $condition
     * @return \Application\Database\QueryBuilder
     */
    public static function or(string $condition = null): QueryBuilder
    {
        self::$where[] = trim('OR ' . $condition);
        return self::$instance;
    }

    /**
     * Query builder IN operator
     * 
     * @param array $values
     * @return \Application\Database\QueryBuilder
     */
    public static function in(array $values): QueryBuilder
    {
        self::$where[] = 'IN (' . implode(', ', $values) . ')';
        return self::$instance;
    }

    /**
     * Query builder NOT operator
     * 
     * @param string $condition
     * @return \Application\Database\QueryBuilder
     */
    public static function not(string $condition = ''): QueryBuilder
    {
        self::$where[] = trim('NOT ' . $condition);
        return self::$instance;
    }

    /**
     * Query builder LIMIT clause
     * 
     * @param int $limit
     * @return \Application\Database\QueryBuilder
     */
    public static function limit(int $limit): QueryBuilder
    {
        self::$control[0] = 'LIMIT ' . $limit;
        return self::$instance;
    }

    /**
     * Query builder OFFSET clause
     * 
     * @param int $offset
     * @return \Application\Database\QueryBuilder
     */
    public static function offset(int $offset): QueryBuilder
    {
        self::$control[1] = 'OFFSET ' . $offset;
        return self::$instance;
    }

    /**
     * Get the query, built by the query builder
     * 
     * @return string Query
     */
    public static function getQuery(): string
    {
        self::$sql = self::$prefix . implode(' ', self::$where) . ' ' . implode(' ', self::$control);
        self::$sql = trim(preg_replace('/  /', ' ', self::$sql));
        return self::$sql;
    }
}
