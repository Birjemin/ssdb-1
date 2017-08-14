<?php

namespace Haolyy\Ssdb\Cache;

class Response
{
    public $cmd;
    public $code;
    public $data = null;
    public $message;

    function __construct($code='ok', $dataOrMessage=null){
        $this->code = $code;
        if($code == 'ok'){
            $this->data = $dataOrMessage;
        }else{
            $this->message = $dataOrMessage;
        }
    }

    function __toString(){
        if($this->code == 'ok'){
            $s = $this->data === null? '' : json_encode($this->data);
        }else{
            $s = $this->message;
        }
        return sprintf('%-13s %12s %s', $this->cmd, $this->code, $s);
    }

    function ok(){
        return $this->code == 'ok';
    }

    function not_found(){
        return $this->code == 'not_found';
    }
}