<?php 
namespace App\Infrastructure\Repository\BirthdayRepository;

use App\Infrastructure\Repository\SqlRepository\SqlRepository;


class BirthdayRepository extends SqlRepository 
{ 
    public function insert($table,$dados) :int
    {
        try {
            $stmt = $this->sql->insert($table,$dados);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\Exception $e) {
            return 0;
        }
   }
      
    
    public function delete($id,$table,string|int $params = 'id'):int
    {
        $stmt= $this->sql->delete($id,$table);
        try {
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function selectFindAll($table) :array| null
    {   
        $stmt = $this->sql->selectFindAll($table);
        $stmt->execute();
        $r=$stmt->fetchAll(\PDO::FETCH_ASSOC); 
        if ($stmt->rowCount() == 0 )return ['summary'=>'Nenhum registro encontrado'];
        return $r;
    }

    public function selectUserOfId($id,$table,$params='id') :array |null
    {
        $stmt = $this->sql->selectUserOfId($id,$table,$params);
        $stmt->execute();
        $r=$stmt->fetchAll(\PDO::FETCH_ASSOC);
        if ($stmt->rowCount() == 0 )return ['summary'=>'Nenhum registro encontrado'];
        return $r;
    }

    public function update($id,$table,$dados,$params='id') : int 
    {   
        try {
            $stmt  = $this->sql->update($id,$table,$dados,$params);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function query($date) :array |null 
    {
        $stmt=$this->sql->prepare(
        "SELECT * from aniversarios , to_char(nascimento,'mm') as mes  order by date");
        $stmt->bindValue(':date',$date);
        try {
            $stmt->execute();
            $r=$stmt->fetchAll(\PDO::FETCH_ASSOC); 
            return $r ;
        } catch (\Throwable $e) {
            throw new \Exception('Erro no banco de dados');
            
        }
    }
    public function selectUserByLogin($login) :array |null 
    {
       
            $stmt = $this->sql->prepare("SELECT * from usuarios where login_rede = :login");
            $stmt->bindValue(":login",$login);
            $stmt->execute();
            $r=$stmt->fetch(\PDO::FETCH_ASSOC);
           
            return $r;
    }
    public function SelectUsersbyDate($date) :array |null
    {   
        try {
         
            $stmt=$this->sql->prepare(
    
                "WITH A AS (
                SELECT * from aniversarios , to_char(nascimento,'mm') as mes 
                WHERE mes = :date 
                order by nascimento desc,nome)
                SELECT 
                    a.id,
                    a.id_empresa,
                    a.nome,
                    a.nascimento,
                    a.mes ,
                    nome_empresa as empresa from A 
                JOIN empresas em on A.id_empresa = em.id"
            );
    
                $stmt->bindValue(':date',$date);
                $stmt->execute();
                $r=$stmt->fetchAll(\PDO::FETCH_ASSOC); 
                if ($stmt->rowCount() == 0 )return ['summary'=>'Nenhum registro encontrado'];
                return $r ;
        } catch (\Throwable $th) {
            $this->createLogger->loggerCSV('erro_buscar_aniversariantes_por_data', $th->getMessage());
            throw new \Exception('Erro ao buscar aniversariantes por data');
        
        }
    }
    public function SelectUsers(int|string $id=null) :array |null
    {   
        try {
            $cmd ="WITH A AS (
                SELECT * from aniversarios           
                order by nome ,nascimento desc)
                SELECT 
                    a.id,
                    a.id_empresa,
                    a.nome,
                    a.nascimento,
                    nome_empresa as empresa from A 
                JOIN empresas em on A.id_empresa = em.id";
                          
            if (!empty($id)) $cmd.= " where a.id = :id";
    
            $stmt= $this->sql->prepare($cmd);
    
            if (!empty($id)) $stmt->bindValue(":id",$id);
    
                $stmt->execute();
                $r=$stmt->fetchAll(\PDO::FETCH_ASSOC); 
                if ($stmt->rowCount() == 0 )return ['summary'=>'Nenhum registro encontrado'];
                return $r ;
          
        } catch (\Throwable $th) {
            $this->createLogger->loggerCSV('erro_buscar_aniversariantes', $th->getMessage());
            throw new \Exception('Erro ao buscar aniversariantes');
        }
    }
    
    
}

    
