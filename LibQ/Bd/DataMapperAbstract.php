<?php

abstract class DataMapperAbstract implements DataMapperInterface
{
    protected $_adapter;
    protected $_collection;
    protected $_entityClass;
    protected $_entityTable;  
         
    /**
     * Class constructor
     */
    public function __construct(MySQLAdapter $adapter, CollectionAbstract $collection, array $entityOptions = array())
    {
        $this->_adapter = $adapter;
        $this->_collection = $collection;
        if (isset($entityOptions['entityClass'])) {
            $this->setEntityClass($entityOptions['entityClass']);
        }
        if (isset($entityOptions['entityTable'])) {
            $this->setEntityTable($entityOptions['entityTable']);
        }
        $this->init();
    }
    
    /**
     * Initialize the data mapper here
     */
    public function init(){}
    
    /**
     * Get the instance of the database adapter
     */ 
    public function getAdapter()
    {
        return $this->_adapter;
    }
    
    /**
     * Get the collection the mapper uses
     */
    public function getCollection()
    {
        return $this->_collection;
    }
    
    /**
     * Set the class for reconstructing entities
     */ 
    public function setEntityClass($entityClass)
    {
        if (!class_exists($entityClass, false)) {
            throw new DataMapperException('The specified entity class ' . $entityClass . ' does not exist.');
        }
        $this->_entityClass = $entityClass;
    }
    
    /**
     * Get the class for reconstructing entities
     */ 
    public function getEntityClass()
    {
        return $this->_entityClass;
    }
    
    /**
     * Set the entity database table the mapper works with
     */ 
    public function setEntityTable($entityTable)
    {
        if (!is_string($entityTable) || empty($entityTable)) {
            throw new DataMapperException('The specified entity table ' . $entityTable . ' is invalid.');
        }
        $this->_entityTable = $entityTable;
    }
    
    /**
     * Get the entity database table the mapper works with
     */ 
    public function getEntityTable()
    {
        return $this->_entityTable;
    }
    
    /**
     * Find an entity by its ID
     */ 
    public function findById($id)
    {
        $id = (int) $id;
        $this->_adapter->select($this->_entityTable, "id = $id");
        if ($data = $this->_adapter->fetch()) {
            return new $this->_entityClass($data);
        }
        return null;
    }
    
    /**
     * Find all the entities
     */ 
    public function findAll()
    {
        $this->_collection->clear();
        $this->_adapter->select($this->_entityTable);
        while ($data = $this->_adapter->fetch()) {
            $this->_collection->add($data['id'], new $this->_entityClass($data));    
        }
        return $this->_collection->count() !== 0 ?
               $this->_collection :
               null; 
    }
    
    /**
     * Find all the entities that match the specified criteria
     */ 
    public function search($criteria)
    {
        $this->_collection->clear();
        $this->_adapter->select($this->_entityTable, $criteria);
        while ($data = $this->_adapter->fetch()) {
            $this->_collection->add($data['id'], new $this->_entityClass($data));    
        }
        return $this->_collection->count() !== 0 ?
               $this->_collection :
               null;
    }
}
