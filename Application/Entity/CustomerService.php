<?php

namespace Application\Entity;

use Application\Database\Connection;
use Application\Database\QueryBuilder;
use PDO;
use PDOException;
use Throwable;

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

    /**
     * For new customer adds a new customer in the database.
     * If existing customer updated the data.
     * 
     * @param \Application\Entity\Customer $customer Customer instance
     * @return bool True on success and false on failure
     */
    public function save(Customer $customer): bool
    {
        if ($customer->getId() && $this->findById($customer->getId())) {
            return $this->update($customer);
        } else {
            return $this->insert($customer);
        }
    }

    /**
     * Adds new customer to database
     * 
     * @param \Application\Entity\Customer $customer Customer instance
     * @return bool True on success and false on failure
     */
    public function insert(Customer $customer)
    {
        $columns = $customer->entityToArray();
        
        unset($columns['id']);

        $insert = 'INSERT INTO ' . $customer::TABLE . ' ';
        $queryColumns = '';
        $queryValues = '';

        foreach ($columns as $column => $value) {
            $queryColumns .= $column . ',';
            $queryValues .= '\'' . $value . '\',';
        }

        $queryColumns = substr($queryColumns, 0, -1);
        $queryValues = substr($queryValues, 0, -1);
        $query = $insert . '(' . $queryColumns . ')' . ' VALUES ' . '(' . $queryValues . ')';

        return $this->execute($query);
    }

    /**
     * Updates existing customer in the database
     * 
     * @param \Application\Entity\Customer $customer Customer instance
     * @return bool True on success and false on failure
     */
    public function update(Customer $customer)
    {
        $columns = $customer->entityToArray();
        $update = 'UPDATE ' . $customer::TABLE;
        $where = ' WHERE id = ' . $customer->getId();

        unset($columns['id']);

        $update .= ' SET ';

        foreach ($columns as $column => $value) {
            $update .= $column . ' = \'' . $value . '\',';
        }

        $query = substr($update, 0, -1) . $where;

        return $this->execute($query);
    }

    /**
     * Executes the query
     * 
     * @param string $query The query which is being executed
     * @return bool Return true on success and false on failure
     */
    protected function execute(string $query): bool
    {
        $success = false;

        try {
            $stmt = $this->connection->pdo->prepare($query);
            $stmt->execute();
            $success = true;
        } catch (PDOException $e) {
            error_log(__METHOD__ . ':' . __LINE__ . ':' . $e->getMessage());
            $success = false;
        } catch (Throwable $e) {
            error_log(__METHOD__ . ':' . __LINE__ . ':' . $e->getMessage());
            $success = false;
        }

        return $success;
    }

    /**
     * Delete customer
     * 
     * @param \Application\Entity\Customer $customer Customer instance
     * @return bool
     */
    public function delete(Customer $customer): bool
    {
        $query = 'DELETE FROM ' . $customer::TABLE . ' WHERE id = :id';
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->execute(['id' => $customer->getId()]);
        return ($this->findById($customer->getId())) ? false : true;
    }
}
