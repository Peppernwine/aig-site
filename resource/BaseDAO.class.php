<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 6/3/2018
 * Time: 12:25 PM
 */

require_once RESOURCE_PATH . "/PaginationState.class.php";

class BaseDAO
{
    private $entityTableMap;
    private $idMap;
    private $calculatedFields;
    private $fieldMap;

    protected function getEntityTableMap() {

    }


    protected function getIdMap() {

    }

    protected function getCalculatedFields() {

    }


    protected function getFieldMap() {

    }

    public function __construct()
    {
        $this->entityTableMap = $this->getEntityTableMap();
        $this->idMap  = $this->getIdMap();
        $this->calculatedFields = $this->getCalculatedFields();
        $this->fieldMap = $this->getFieldMap();
    }

    public function getNewPaginationState($searchParams) {
        $paginationState = PaginationState::withNewToken('Orders',$searchParams,5);
        return $paginationState;
    }

    public function getPaginationState($token) {
        $paginationToken = PaginationState::withExitingToken($token);
        return $paginationToken;
    }

    protected function getIDField($entityName)
    {
        $id_field = '';
        if (key_exists($entityName, $this->idMap))
            $id_field = $this->idMap[$entityName];

        return $id_field ;
    }

    protected function generateInsertSQL($entityName)
    {
        $tableName = $this->entityTableMap[$entityName];
        $entityFieldMap = $this->fieldMap[$entityName];
        $paramNames = '';
        $fieldNames = '';

        $id_field = $this->getIDField($entityName);

        foreach ($entityFieldMap as $key => $value) {

            if ($key === $id_field) continue;

            if ($fieldNames === '')
                $fieldNames = $value;
            else
                $fieldNames = $fieldNames . ',' . $value;

            if ($paramNames === '')
                $paramNames = ':' . $key;
            else
                $paramNames = $paramNames . ',' . ':' . $key;
        }

        $fieldNames = '(' . $fieldNames . ')';
        $paramNames = '(' . $paramNames . ')';

        return 'INSERT INTO ' . $tableName . $fieldNames . ' VALUES' . $paramNames;
    }

    protected function getTableInsertParameters($entityName, $entity,$idValues)
    {
        $entityFieldMap = $this->fieldMap[$entityName];
        $id_field = $this->getIDField($entityName);
        $paramValues = [];
        foreach ($entityFieldMap as $key => $value) {
            if ($key === $id_field) continue;

            $getMethodName = 'get' . ucfirst($key);

            $method = new ReflectionMethod($entity, $getMethodName);

            if (array_key_exists($key, $idValues))
                $paramValues[':' . $key] = $idValues[$key];
            else
                $paramValues[':' . $key] = $method->invoke($entity);
        }
        return $paramValues;
    }


    protected function gatherEntityFields($row,$entityName,&$data){
        $fieldMap = $this->fieldMap[$entityName];
        foreach ($fieldMap as $key => $value) {
            if ($this->isCalculatedField($entityName, $key)) continue;
            $data[$key] = $row[$value];
        }
    }

    protected function isCalculatedField($entityName, $property) {
        if (!array_key_exists($entityName,$this->calculatedFields))
            return false;

        $calc_fields = $this->calculatedFields[$entityName];
        return in_array($property,$calc_fields);
    }

}