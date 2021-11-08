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
     * @return \Application\Entity\Customer|false Customer object or false on failure
     */
    public function findById(int $id): Customer
    {
        $stmt = $this->connection->pdo->prepare(
                QueryBuilder::select(Customer::TABLE)->where('id = :id')::getQuery()
            );
        $stmt->execute(['id' => (int) $id]);
        return Customer::arrayToEntity(
            $stmt->fetch(PDO::FETCH_ASSOC), new Customer()
        );
    }

    /**
     * Finds customer by email and returs customer object
     * 
     * @param string $email Customer email address
     * @return \Application\Entity\Customer|false Customer object or false on failure
     */
    public function findByEmail(string $email): Customer
    {
        $stmt = $this->connection->pdo->prepare(
            QueryBuilder::select(Customer::TABLE)->where('email = :email')::getQuery()
        );
        $stmt->execute(['email' => $email]);
        return Customer::arrayToEntity(
            $stmt->fetch(PDO::FETCH_ASSOC), new Customer()
        );
    }

    public function save(Customer $customer)
    {

    }

    public function insert(Customer $customer)
    {

    }

    public function update(Customer $customer)
    {

    }

    public function flush()
    {

    }

    public function remove(Customer $customer)
    {
        
    }
}
