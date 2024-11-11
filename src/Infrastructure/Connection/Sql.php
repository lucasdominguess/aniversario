<?php
namespace App\Infrastructure\Connection;


use App\classes\CreateLogger;


class Sql extends \PDO
/**
 * Conexão com banco de dados
 * @param $env variavel env com dados para conexão do banco

 *  
 */
    {   
        public function __construct(public CreateLogger $log) {
            try {
                global $env;

                parent::__construct("{$env['sgbd']}:
                dbname={$env['dbname']};
                host={$env['host']};
                port={$env['port']}",
                $env['user'],
                $env['password']);
            } catch (\PDOException $th) {
              
                $this->log->loggerTelegram("ERRO-SQL","Erro ao conectar banco de dados {$env['sgbd']},",true);
                throw new \PDOException('Erro ao conectar banco de dados');
            }
          
        }
        /**
         * Seleciona todos os dados de uma tabela especifica 
         * @param string $table Nome da tabela do banco de dados 
         */
        public function selectFindAll($table): \PDOStatement 
        {
            $stmt = $this->prepare("SELECT * from {$table}");
            // $stmt->execute();
            // $r=$stmt->fetchAll(\PDO::FETCH_ASSOC); 
            return $stmt;
        }
        /**
         * Seleciona todos os dados de uma tabela referente ao ID especifico
         * @param string $id identificação unica do usuario 
         * @param string $table tabela onde sera feita a busca pelos dados 
         * @param  string|int $params parametros para comparação where da consulta
         */
        public function selectUserOfId(string|int $id,string $table,string|int $params ='id'): \PDOStatement
        {
            $stmt = $this->prepare("SELECT * from {$table} where \"{$params}\" = :id");
            $stmt->bindValue(":id",$id);
            // $stmt->execute();
            // $r=$stmt->fetch(\PDO::FETCH_ASSOC);
            
            return $stmt;
        }
        /**
         * Atualizar dados em uma tabela dinamicamente
         * @param string $id identificação unica do usuario 
         * @param string $table nome da tabela para o update dos dados 
         * @param array $dados array de dados com nomes de colunas e valores 
         * @param string|int $params parametros para comparação where da consulta
         */
        public function update(string|int $id,string $table,array $dados =[],string|int $params = 'id') : \PDOStatement
        {
            
            $s = array_keys($dados);
            $t = "$table set";
            
            $c = "update $t";
            $clause = " where $params = :id" ; 

            foreach ($s as $key){
                $c .= ", $key = :{$key}";
            }
            
            $c.=$clause;
            $c= str_replace("set,","set",$c);
            // $c = substr($nome,0,strlen($nome)-1);
            $stmt  = $this->prepare($c);

            foreach ($dados as $key => $value ){
                $stmt->bindvalue(":{$key}",$value);
            }   
            $stmt->bindValue(':id',$id);

            return $stmt ; 
        }
        /**
         * Inserir dados em uma tabela dinamicamente
         * @param string $table nome da tabela 
         * @param array $dados array de dados com nomes de colunas e valores 
         */
        public function insert(string $table,array $dados = []) : \PDOStatement
        {
            $keys = array_keys($dados);
            $colunas = implode(',', $keys);
            $valores = implode(',', array_map(fn($e)=>":{$e}", $keys));
            $cmd = "INSERT INTO {$table}($colunas)VALUES($valores)";
            $stmt = $this->prepare($cmd);
          
            foreach ($dados as $key => $value) {
              $stmt->bindValue(":{$key}", $value);
                
            }
            
            return $stmt;
          
       }
       /**
         * deletar dados de uma tabela especifica
         * @param string $table nome da tabela para deletar os dados 
         * @param string $id identificação unica do usuario 
         * @param string|int $params parametros para comparação where da consulta
         */
       public function delete($id,$table,string|int $params = 'id') :\PDOStatement
       {
           $stmt= $this->prepare("DELETE from {$table} where $params = :id");
           $stmt->bindValue(':id',$id);
           return $stmt ; 
       }

      
        public function setParams(\PDOStatement $stmt , array $dados = []){ 
            
            foreach ($dados as $key => $value) {
                $stmt->bindValue($key,$value);
            }
        }
       
    }
