<?php 
namespace App\Infrastructure\Repository\SqlRepository;

use App\classes\CreateLogger;
use DateTime;
use App\Domain\User\User;

interface SqlInterface { 
    
    // const CreateLogger = CreateLogger::class;

    public function insert(string $table , array $dados) :int ;


    public function delete(int|string $id,string $table ,string|int $params = 'id') :int ;

    public function selectFindAll(string $table): array |null ;

    public function selectUserOfId(int|string $id,string $table,string|int $params='id') :array |null ;

    public function update(int $id,string $table ,array $dados,string|int $params='id') : int ;








}