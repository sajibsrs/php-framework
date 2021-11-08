<?php

namespace Application\Entity;

use Application\Database\Connection;
use Application\Database\QueryBuilder;
use PDO;

class CustomerService
{
    /**
     * Database connection
     * 
     * @var \Application\Database\Connection $connection
     */
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Finds customer by id and returns customer object
     * 
     * @param int $id Customer id
     * @return \Application\Entity\Customer|false Customer object
     */
    public function findById(int $id): Customer
    {
        $stmt = $this
            ->connection
            ->pdo
            ->prepare(QueryBuilder::select('customers')->where('id = :id')::getQuery());
        $stmt->execute(['id' => (int) $id]);
        return Customer::arrayToEntity(
            $stmt->fetch(PDO::FETCH_ASSOC), new Customer()
        );
    }
}
