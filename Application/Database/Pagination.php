<?php

namespace Application\Database;

use PDOException;
use Throwable;

class Pagination
{
    public const LIMIT_DEFAULT = 20;
    public const OFFSET_DEFAULT = 0;

    /**
     * @var string $sql Query string
     */
    protected $sql;
    
    /**
     * @var int $page Current page number
     */
    protected $page;

    /**
     * @var int $limit Item count per page
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

    public function paginate(Connection $con, $mode, $params = [])
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
