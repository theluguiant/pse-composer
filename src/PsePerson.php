<?php

namespace theluguiant\PseComposer;

class PsePerson{
    protected $document;
    protected $documentType;
    protected $firstName;
    protected $lastName;
    protected $company;
    protected $emailAddress;
    protected $city;
    protected $province;
    protected $address;
    protected $phone;
    protected $country;
    protected $mobile;
    
    public function __construct( $params ) {
        if ( is_array( $params ) ) {
            $this->document     = $params['document'];
            $this->documentType = $params['documentType'];
            $this->firstName    = $params['firstName'];
            $this->lastName     = $params['lastName'];
            $this->company      = $params['company'];
            $this->emailAddress = $params['emailAddress'];
            $this->address      = $params['address'];
            $this->city         = $params['city'];
            $this->province     = $params['province'];
            $this->phone        = $params['phone'];
            $this->country      = $params['country'];
            $this->mobile       = $params['mobile'];
             
            foreach ( get_object_vars( $this ) as $key => $attribute ) {
                switch ($key) {
                    case 'document':
                        $this->genericNumVal($this->document,12,'document');
                        break;

                    case 'documentType':
                        if(isset($this->documentType) && $this->documentType !== null){

                            if(strlen($this->documentType)>2){

                                throw new Exception('El tipo de documento puede tener mas de 2 caracteres '.$this->documentType);     

                            }else{

                            if(!in_array($this->documentType,array('CC','CE','TI','PPN','NIT','COD'))){

                                  throw new Exception('El tipo de documento no es valido '.$this->documentType);

                                }
                             } 	

                        }else{

                           throw new Exception( "$key no esta definida");

                        }	
                        break;

                    case 'firstName':
                        $this->genericTextVal($this->firstName,60,'firstName');
                        break;

                    case 'lastName':
                        $this->genericTextVal($this->lastName,60,'lastName');
                        break;	

                    case 'company':
                        $this->genericTextVal($this->company,60,'company');
                        break;	

                    case 'emailAddress':
                        if(isset($this->emailAddress) && $this->emailAddress !== null){
                            if(filter_var($this->emailAddress, FILTER_VALIDATE_EMAIL)){
                                if(strlen($this->emailAddress)>80){
                                    throw new Exception("$key el maximo es 80 caracteres");
                                }
                            }else{

                                throw new Exception( "$key no es un mail valido");  

                            }

                        }else{

                            throw new Exception( "$key no esta definido"); 
                        }
                        break;	

                    case 'address':
                        $this->genericTextVal($this->address,100,'address');
                        break;

                    case 'city':
                        $this->genericTextVal($this->city,50,'city');
                        break;	

                    case 'province':
                        $this->genericTextVal($this->province,50,'province');
                        break;	

                    case 'phone':
                        $this->genericNumVal($this->phone,30,'phone');
                        break;	

                    case 'country':
                        if(isset($this->country) and $this->country!=null){
                            if(is_string($this->country)){
                                if(strlen($this->country)>2){
                                   throw new Exception( "$key no puede ser de mas de 2 caracteres");
                                }else{
                                    $this->country($this->country);
                                }
                            }else{
                              throw new Exception( "$key no es valida");
                            }
                        }else{
                              throw new Exception( "$key no esta definida");
                        }
                        break;		

                    case 'mobile':
                        $this->genericNumVal($this->mobile,30,'mobile');
                        break;	

                }
            }
            
        } else {
                throw new Exception( "Parametros invalidos" );
        }
    }
        
    public function genericNumVal($numb,$size = null,$name = null){
    	if(isset($numb) && $numb !== null){
            if(is_numeric($numb)){
                
               if(strlen($numb)>$size){
                   
               	 throw new Exception( $name." no puede ser de mas de".$size." caracteres");
                 
               }
               
            }else{
                
            	throw new Exception( $name." debe ser numerico");
                
            }
        }else{
            
           throw new Exception( $name." no esta definida");
           
        } 
    } 
    /***************  validador de texto *******************/
    public function genericTextVal($string,$size = null,$name = null){
        if(isset($string) && $string !== null && $string !==''){ 
            if(is_string($string)){
                if(strlen($string)>$size){

                    throw new Exception($name." el maximo es ".$size." caracteres");

                }
            }else{

                throw new Exception($name." no es valido");

            }
        }else{

            throw new Exception($name." no esta definida");

        }   
    }
    /*************** diccionario iso 3166-1 alpha 2 y validacion pais *******************/
    public function country($country){
        $countries = array('AF','AX','AL','DZ','AS','AD','AO','AI','AQ','AG','AR','AM',
            'AW','AU','AT','AZ','BS','BH','BD','BB','BY','BE','BZ','BJ',
            'BM','BT','BO','BA','BW','BV','BR','IO','BN','BG','BF','BI',
            'KH','CM','CA','CV','KY','CF','TD','CL','CN','CX','CC','CO',
            'KM','CG','CD','CK','CR','CI','HR','CU','CY','CZ','DK','DJ',
            'DM','DO','EC','EG','SV','GQ','ER','EE','ET','FK','FO','FJ',
            'FI','FR','GF','PF','TF','GA','GM','GE','DE','GH','GI','GR',
            'GL','GD','GP','GU','GT','GG','GN','GW','GY','HT','HM','VA',
            'HN','HK','HU','IS','IN','ID','IR','IQ','IE','IM','IL','IT',
            'JM','JP','JE','JO','KZ','KE','KI','KR','KW','KG','LA','LV',
            'LB','LS','LR','LY','LI','LT','LU','MO','MK','MG','MW','MY',
            'MV','ML','MT','MH','MQ','MR','MU','YT','MX','FM','MD','MC',
            'MN','ME','MS','MA','MZ','MM','NA','NR','NP','NL','AN','NC',
            'NZ','NI','NE','NG','NU','NF','MP','NO','OM','PK','PW','PS',
            'PA','PG','PY','PE','PH','PN','PL','PT','PR','QA','RE','RO',
            'RU','RW','BL','SH','KN','LC','MF','PM','VC','WS','SM','ST',
            'SA','SN','RS','SC','SL','SG','SK','SI','SB','SO','ZA','GS',
            'ES','LK','SD','SR','SJ','SZ','SE','CH','SY','TW','TJ','TZ',
            'TH','TL','TG','TK','TO','TT','TN','TR','TM','TC','TV','UG',
            'UA','AE','GB','US','UM','UY','UZ','VU','VE','VN','VG','VI',
            'WF','EH','YE','ZM','ZW'
        );

        if(!in_array($country,$countries))
        {
            throw new Exception('La provincia no es valida '.$country);
        }
    }
    
    public function validations($var){
        
        
    }
}