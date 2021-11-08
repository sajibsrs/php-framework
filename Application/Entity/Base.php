<?php

namespace Application\Entity;

/**
 * Extend this class when creating new entity.
 */
abstract class Base
{
    /**
     * Entity id
     * 
     * @var int
     */
    protected int $id = 0;

    /**
     * Entity mapping
     * 
     * @var array
     */
    protected array $mapping = ['id' => 'id'];

    /**
     * Get entity id
     * 
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set entity id
     * 
     * @param int $id Entity id
     */
    public function setId(int $id): void
    {
        $this->id = (int) $id;
    }

    /**
     * Converts array into entity
     * 
     * @param array $data Data array
     * @param \Application\Entity\Base $instance Entity instance
     * @return \Application\Entity\Base|false Entity instance or false on failure
     */
    public static function arrayToEntity(array $data, Base $instance)
    {
        if ($data) {
            
            foreach ($instance->mapping as $dbColumn => $propertyName) {
                $method = 'set' . ucfirst($propertyName);
                $instance->$method($data[$dbColumn]);
            }
            
            return $instance;
        }

        return false;
    }

    /**
     * Converts entity to array
     * 
     * @return array Converted array of entity
     */
    public function entityToArray(): array
    {
        $data = [];

        foreach ($this->mapping as $dbColumn => $propertyName) {
            $method = 'get' . ucfirst($propertyName);
            $data[$dbColumn] = $this->$method() ?? null;
        }

        return $data;
    }
}
