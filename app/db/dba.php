<?php 

class Dba{
    private string $filename;
    private array $pessoas;
    private $handler;

    function __construct( string $filename ){
        $this->filename = $filename;
        $this->pessoas = [];
        if(file_exists($this->filename))
            $this->setHandler('r');
        else
            $this->setHandler('c');
        $content = fgets($this->handler);
        if($content){
            $this->pessoas = json_decode($content);
        }
        fclose($this->handler);
    }

    /**
     * Seter para o handler de arquivo do mock de bando de dados.
     */
    private function setHandler(string $mode):void{
        $this->handler = fopen($this->filename, $mode);
    }


    /**
     * Escreve o conteudo no arquivo
     */
    public function write():void{
        $this->setHandler('w');
        $content = json_encode($this->pessoas);
        fwrite($this->handler, $content);
        fclose($this->handler);
    }

    /**
     * Adiciona uma nova pessoa no final da lista do "bd"
     */
    public function appendContent($pessoa):void{
        $apend = $pessoa->toArray();
        array_push($this->pessoas, $apend);
    }

    public function getAll():array{
        return $this->pessoas;
    }
}


