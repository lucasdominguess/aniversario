<?php
namespace App\Infrastructure\Repository\SqlRepository;



use App\classes\CreateLogger;

use App\Infrastructure\Connection\Sql;
use App\Infrastructure\Repository\SqlRepository\SqlInterface;
use App\Infrastructure\Repository\Boletim_cirurgia_Repository\BoletimCirurgiaRepository;
use PDOStatement;

class SqlRepository implements SqlInterface { 
   public function __construct
       (
        public Sql $sql , public CreateLogger $createLogger,
        ) 
    {

    }  
    public function insert($table,$dados) :int
    {
        try {
            
            $stmt = $this->sql->insert($table,$dados);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\Exception $e) {
                if ($e->getCode() == '23505') {
                    

                    throw new \PDOException("Falha ao inserir dados! O campo de email ou o ID de login pode já estar em uso.");
                  }
            throw new \PDOException($e->getMessage());
            // return 0;
        }
   }
    public function delete(int|string $id,string $table,string|int $params = 'id'):int
    {
        $stmt= $this->sql->delete($id,$table,$params);
        try {
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function selectFindAll(string $table) :array| null
    {
        $stmt = $this->sql->selectFindAll($table);
        $stmt->execute();
        $r=$stmt->fetchAll(\PDO::FETCH_ASSOC); 
        
        return $r;
    }

    public function selectUserOfId(int|string $id,string $table,string|int $params='id') :array |null
    {
        $stmt = $this->sql->selectUserOfId($id,$table,$params);
        $stmt->execute();
        $r=$stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        return $r;
    }
    public function update(string|int $id,string $table,array $dados,string|int $params='id') : int 
    {   
        $stmt  = $this->sql->update($id,$table,$dados,$params);
        try {
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\Exception $e) {
            if ($e->getCode() == '23505') {
                throw new \PDOException("Falha ao atualizar dados!");
              }
        throw new \PDOException("Falha ao atualizar dados do usuario");
            }
    }
    public function setWhereCondition($query,$id_unidade=null,$id_adm=null,$dateIni=null,$dateEnd=null,string $orderBy=null,string $where=null,$offset=null,array $dados=[]): bool|PDOStatement  
    {   
        try {
            //aplica filtro por data  caso exista
            if($dateIni && $dateEnd){
                strpos($query,'where') ? $query.=' and': $query.=' where';
               $query .= ' data BETWEEN :dateIni AND :dateEnd ';
                $dados =[
                    ':dateIni' => $dateIni,
                    ':dateEnd' => $dateEnd
                 ];
            }
             //aplicar filtro por id caso exista
            if (!empty($id_unidade)) {
                
                strpos($query,'where') ? $query.=' and': $query.=' where';
                $query .= " id_unidade = :id_unidade";
                $dados[':id_unidade']= $id_unidade ;
            }
            //aplicar filtro por id_adm (id_pai) caso exista
            if ($id_adm != 1) {
                strpos($query,'where') ? $query.=' and': $query.=' where';
                $query .= " id_adm = :id_adm";
                $dados[':id_adm']= $id_adm;
            }
            if(!empty($where)){
                // strpos($query,'where') ? $query.=' and': $query.=' where';
                // $query .= " :where ";
                $dados[':where']= $where;
            }
            // aplica order by caso exista
            if($orderBy) {
                $query .= " ORDER BY $orderBy" ;
                if($offset){
                    $query .= " $offset ";
                // $dados[':orderby']= $orderBy;
            }
          }
            $stmt = $this->sql->prepare($query);
         
            if(!empty($dados)) $this->sql->setParams($stmt,$dados);
    
            return $stmt;
        } catch (\Throwable $th) {
            $this->createLogger->loggerCSV("Erro_set_where_function",$th->getMessage());
            throw new \Exception('falha na tentativa de executar parametros');
        }
    }
}