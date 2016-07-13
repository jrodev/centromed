<?php

namespace DataBase\Model;

use Zend\Db\TableGateway\TableGateway,
    Zend\Db\Sql\Select,
    Zend\Db\Sql\Sql
;

class Table
{
    protected $tableGateway;
    protected $sql;
    protected $adapter;
    protected $inputFilter;
    
    public $idTbl  = 0;
    public $fields = array();

    public function __construct(TableGateway $tableGateway, $fields=array())
    {
        $this->tableGateway = $tableGateway;
        $this->adapter = $tableGateway->getAdapter();
        $this->sql = new Sql($this->adapter);
        
        // Los fields lo define el config
        // Primer field es PK (Convencion)
        $this->idTbl  = $fields[0]['name'];
        $this->fields = $fields;
    }
    
    /// CORREGIR LO DE FIELDS Y LA LOGICA!!
    private function exchangeArray($inpsData=array())
    {   
        $data = array();
        $isIns = empty($inpsData[$this->idTbl]);// si NO viene id de tabla
        foreach ($this->fields as $i=>$field) {
            $value = key_exists($field['name'],$inpsData)?$inpsData[$field['name']]:'';
            if(empty($value)){
                if($isIns){
                    if(!is_null($field['value'])){ // tiene Valor por defecto
                        $data[$field['name']] = $field['value'];
                    }
                    if($field['null']){ // Acepta nulos
                        $data[$field['name']] = $value;
                    }
                }// Ningun valor vacio para UPD
            }else{
                $data[$field['name']] = $value;
            }
        }
        flog('Table->exchangeArray->data:',$data);
        return $data;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getRow($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array($this->idTbl=>$id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    /**
     * Si en $inpsData contiene idTbl se tomara como un UPD sino como INS
     * @param Array $inpsData Datos de formulario enviados para guardar.
     * @return Array|Boolean Retorna el registro Acrtualizado o Insertado.
     */
    public function saveRow($inpsData)
    {
        $data  = $this->exchangeArray($inpsData);
        $id = key_exists($this->idTbl, $data)?(int)$data[$this->idTbl]:0;
        flog('saveRow $id---->>>', $id);
        if ($id) {
            if ($this->getRow($id)) {
                $affecteds = $this->tableGateway->update($data, array($this->idTbl=>$id));
            } else {
                throw new \Exception('Row id does not exist');
            }
        } else {
            $affecteds = $this->tableGateway->insert($data);
            if($affecteds){
                $data[$this->idTbl] = $this->tableGateway->lastInsertValue;
            }
        }
        flog('Table->saveRow->$affecteds:',$affecteds);
        return $affecteds ? $data : FALSE;
    }
    
    /**
     * 
     */
    public function updateRow($set, $whr)
    {
        // table definido en Model class
        $upd  = $this->sql->update($this->table)->set($set)->where($whr);
        $stmt = $this->sql->prepareStatementForSqlObject($upd);
        $res  = $stmt->execute(); flog("updateRow:",$res);
        return $res->getAffectedRows();
    }

    public function deleteRow($id)
    {
        $this->tableGateway->delete(array($this->idTbl=>(int)$id));
    }
    
    /**
     * Devuelve un array de resultado
     * @param Select $sel objeto select que arma la query
     * @return Array Resultado de la consulta select como array
     */
    public function sqlFetchAll(Select $sel)
    {
        $selStr = $this->sql->getSqlStringForSqlObject($sel);
        return $this->adapter->query($selStr, 'execute')->toArray();
    }
}