<?php
// namespace App\Infrastructure\Connection;


// use Redis;
// use RedisException;
// class RedisConn extends Redis
// {      
//     protected string $host;
//     protected int $port;
//     function __construct()
//     {  
         
//         try {
//         $config = parse_ini_file(__DIR__.'/../../../../config.ini', true);

//         if (!$config) {
//             throw new RedisException('Failed to parse configuration file.');
//         }
        
//         $this->host = $config['redis']['redis_host'];
//         $this->port =$config['redis']['redis_port'];
//         $this->connect($this->host, $this->port);
       
//         } catch (RedisException $e) {
            
//             throw new RedisException("Falha ao conectar ao servidor Redis em {$this->host}:{$this->port}. Error: " . $e->getMessage(), 0, $e);
//         }
//     }

    
    
// }


