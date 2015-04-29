<?php

namespace Nucleo\Service;

use ZeDb\Model;

abstract class GenericTable extends Model {

    CONST WITH_TRANSACTION = TRUE;
    CONST WITHOUT_TRANSACTION = FALSE;
    
    public function __construct($primary_key, $options = null) {
        $this->primaryKey = $primary_key;
        $adapter = $this->getAdaptador();
        parent::__construct($adapter, $options);
    }

    private function getAdaptador(){
        $params = $this->getDatabaseManager()->get('config')['zendexperts_zedb']['adapter'];
        $adapter = new \Zend\Db\Adapter\Adapter($params);
        return $adapter;
    }

    public function beginTransaction(){
        $this->connection->beginTransaction();
    }
    
    public function commit(){
        $this->connection->commit();
    }
    
    public function rollback(){
        $this->connection->rollback();
    }
    
    public function disconnect(){
        $this->connection->disconnect();
    }
    
    public function isConnected(){
        $this->connection->isConnected();
    }
    
    protected function executeSql($sql){
        try {
            //prepara executa a sql
            $statement = $this->getAdapter()->createStatement();
            $statement->prepare($sql);
            $result = $statement->execute();

            //formata retornos diferentes para sql de busca e alteracao
            if( in_array(explode(' ', trim($sql))[0],['INSERT', 'UPDATE', 'DELETE']) ){
                $return = [
                    'error' => 0,
                    'rowAffected' =>$result->getAffectedRows(),
                ];
            } else {
                $return = [
                    'error' => 0,
                    'result' => $result->getResource()->fetchAll(\PDO::FETCH_ASSOC),
                ];
                
            }
            return $return;
        } catch (\Exception $e) {
            return [
                'error' => 1,
                'message' => $e->getMessage(),
                'type' => 'db'
            ];
        }
    }

}
