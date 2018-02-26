<?php

namespace theluguiant\PseComposer;

class PseAuth{
    
    public $login;        //identificador dado por placetopay
    public $tranKey;      //Llave transaccional 
    public $seed;         //semilla
    public $hashkey;      //hash generado con la semilla y la llave Llave transaccional 
    public $url_test;     //url servicio de pruebas
    public $url_prod;     //url servicio en produccion
    public $paramAuth;    //parametros de autenticacion 
    public $status;       //estado de la aplicacion, 0 test o  1 produccion
    public $auth;         //autenticador
    /******************** datos de configuracion predefinidos ***********************/
    public function __construct() {
        $this->login = '6dd490faf9cb87a9862245da41170ff2';
        $this->seed  = date( 'c' );
        $this->tranKey = '024h1IlD';
        $this->hashkey = sha1($this->seed.$this->tranKey,false);
        $this->url_test = 'https://test.placetopay.com/soap/pse/?wsdl';
        $this->url_prod = 'https://test.placetopay.com/soap/pse/?wsdl';
        $this->paramAuth = [
                              'login'      => $this->login,
                              'tranKey'    => $this->hashkey,
                              'seed'       => $this->seed
                            ];
        $this->status = 0;
        $this->auth   = ['auth' => $this->paramAuth];
    }
    /************************** set data ****************************************/
    public function setLogin($idinteficator  = null){
        if(isset($idinteficator) && $idinteficator!=null && $idinteficator != '')
        {
            if(is_string($idinteficator) && strlen($idinteficator)>32)
            {
              
                    $this->login  = $idinteficator;
           
            }else{

                throw new Exception("El identificador no es valido");


            }
        }else{
            
            throw new Exception('El identificador no puede ser nulo');
         
        }
       
    }
     
    public function setTranKey($tranKey  = null){
        if(isset($tranKey) and $tranKey != null){
         
            if(is_string($tranKey) && strlen($tranKey)>40){
              
                $this->tranKey  = $tranKey; 
             
            }else{
               
                throw new Exception("El identificador no es valido");
             
            }
      }else{
          
        throw new Exception('La Llave transaccional no puede ser nula');
        
      }  
       
    }
    
    public function setSeed(){
       $this->seed  = date('c');
    }
    public function setHashkey($tranKey = null){
       if(isset($tranKey) && $tranKey != null){
          $seed=date('c'); 
          $this->setTranKey($tranKey);
          $this->hashkey = sha1($seed.$this->tranKey,false);
       }else{
          throw new Exception('La Llave transaccional no puede ser nula');
       }
       
    }
    public function setUrlTest($urlTest=null){
      if(isset($urlTest) && $urlTest!=null){
        if (filter_var($urlTest, FILTER_VALIDATE_URL) === FALSE) {
           throw new Exception('La url no es valida');
       }else{
          $this->url_test  = $urlTest;
       }
      }else{
           throw new Exception('La url no puede ser nula');
      }  
       
    } 
    
    public function setUrlProd($urlProd  = null){
      if(isset($urlProd) && $urlProd!=null){
       if (filter_var($urlProd, FILTER_VALIDATE_URL) === FALSE) {
           throw new Exception('La url no es valida');
       }else{
          $this->url_prod  = $urlProd;
       }     
      }else{
        throw new Exception('La url no puede ser nula');
      }  
    }
    public function setParamAuth($idinteficator = null,$tranKey = null){
       
        if(
            (isset($identificador) && $idinteficator !== null) &&
            (isset($tranKey) && $tranKey !== null) 
            ){
                $seed=date('c');  
                $this->setTranKey($tranKey);
                $this->setLogin($idinteficator);
                $this->hashkey = sha1($seed.$this->tranKey,false);

                $this->paramAuth = array(
                               'login'      => $this->login,
                               'tranKey'    => $this->hashkey,
                               'seed'       => $seed
                             );
        }else{
           
            throw new Exception('El identificador y la Llave transaccional no pueden ser nulos');
        }
       
    }
    
    public function setStatus($status = null){
        if(isset($status) && $status !== null){
            if(is_numeric($status)){
                
                if($this->status === 0 || $this->status === 1){
                    
                    $this->status = $status;
                    
                }else{
                    
                    throw new Exception('El estatus es 0 prueba o 1 produccion');
                    
                }
                
            }else{
                
               throw new Exception('El estatus es numerico');
               
            }
            
        }else{
            
          throw new Exception('El estatus no puede ser nulo');
          
        }
        
    }
}