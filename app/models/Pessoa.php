<?php 

class Pessoa{
    public string $nome;
    public int $NIS;

    function __construct(string $nome, int $NIS){
        if($nome !== '')
            $this->setNome($nome);
        else 
            throw new ErrorException("Campo nome obrigatório");
        if($NIS)
            $this->setNIS($NIS);
        else
            throw new ErrorException("Campo NIS obrigatório");
    } 

    public function getNome(){
        return $this->$nome;
    }

    public function getNIS():int{
        return $this->$NIS;
    }

    public function setNome( string $novo_nome):void{
        $this->nome = $novo_nome;
    }

    public function setNIS(int $novo_NIS):void{
        $this->NIS = $novo_NIS;
    }

    static function getPessoaByName(string $nome, $dba):array{
        $todos = $dba->getAll();
        $reg_nome = "/$nome/i";
        $filtrados= [];
        foreach($todos as $p){
            $match;
            preg_match($reg_nome, $p->nome, $match);
            if(sizeof($match))
                array_push($filtrados, $p);
        }
        return $filtrados;
    }

    static function getPessoaByNIS(int $NIS, $dba):array{
        $todos = $dba->getAll();
        $reg_nome = "/$NIS/i";
        $filtrados= [];
        foreach($todos as $p){
            $match;
            preg_match($reg_nome, (string)$p->NIS, $match);
            if(sizeof($match))
                array_push($filtrados, $p);
        }
        return $filtrados;
    }

    public function toArray():array{
        return [
            'nome'=>$this->nome,
            'NIS'=>$this->NIS
        ];
    }

    public function save($dba){
        $dba->appendContent($this);
        $dba->write();
    }
}