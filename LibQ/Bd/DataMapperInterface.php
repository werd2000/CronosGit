<?php

interface DataMapperInterface
{
    public function findById($id);
    
    public function findAll();
    
    public function search($criteria);
    
    public function insert(EntityAbstract $entity);
    
    public function update(EntityAbstract $entity);
    
    public function delete($id);          
}
