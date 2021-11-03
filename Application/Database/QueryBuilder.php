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
     * If none provided *select* all will be applied.
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
}
