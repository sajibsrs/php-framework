<?php

namespace Application\Database;

use PDOException;
use Throwable;

class Pagination
{
    public const LIMIT_DEFAULT = 20;
    public const OFFSET_DEFAULT = 0;

    /**
     * Query string
     * 
     * @var string $sql
     */
    protected $sql;
    
    /**
     * Current page number
     * 
     * @var int $page
     */
    protected $page;

    /**
     * Item count per page
     * 
     * @var int $limit
     */
    protected $limit;

    public function __construct($sql, $page, $limit)
    {
        $offset = $page * $limit;

        switch (true) {
            case (stripos($sql, 'LIMIT') && stripos($sql, 'OFFSET')):
                break;
            case (stripos($sql, 'LIMIT')):
                $sql .= ' LIMIT ' . self::LIMIT_DEFAULT;
                break;
            case (stripos($sql, 'OFFSET')):
                $sql .= ' OFFSET ' . self::OFFSET_DEFAULT;
                break;
            default:
                $sql .= ' LIMIT ' . self::LIMIT_DEFAULT;
                $sql .= ' OFFSET ' . self::OFFSET_DEFAULT;
                break;
        }

        $this->sql = preg_replace('/LIMIT \d+.*OFFSET \d+/Ui', 'LIMIT ' . $limit . ' OFFSET ' .  $offset, $sql);
    }

    /**
     * Creates pagination array.
     * 
     * @param \Application\Database\Connection $con Database connection
     * @param int $mode PDO fetch mode
     * @param array $params Parameters for binding to query string (Optional)
     * @return array|bool Returns array of items or false on failure
     */
    public function paginate(Connection $con, int $mode, $params = [])
    {
        try {
            $stmt = $con->pdo->prepare($this->sql);

            if (! $stmt) {
                return false;
            }

            if ($params) {
                $stmt->execute($params);
            } else {
                $stmt->execute();
            }

            return $stmt->fetchAll($mode);
            
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        } catch (Throwable $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
