<?php

namespace theluguiant\PseComposer;

class ServicePseBank{
    public $PseAuth;
    public $transId;
    public $bankCode;
    public $bankInterface;
    public $returnURL;
    public $reference;
    public $description;
    public $language;
    public $currency;
    public $totalAmount;
    public $taxAmount;
    public $devolutionBase;
    public $tipAmount;
    public $ipAddress;
    public $userAgent;
    
    public $infoPayer;
    public $infoBuyer;
    Public $infoShipping;
    Public $playerDocumentType;
    Public $playerDocument;
    Public $playerFirstName;
    Public $playerLastName;
    Public $playerCompany;
    Public $playerEmailAddress;
    Public $playerAddress;
    Public $playerCity;
    Public $playerProvince;
    Public $playerCountry;
    Public $playerPhone;
    Public $playerMobile;
    public $buyerDocumentType;
    public $buyerDocument;
    public $buyerFirstName;
    public $buyerLastName;
    public $buyerCompany;
    public $buyerEmailAddress;
    public $buyerAddress;
    public $buyerCity;
    public $buyerProvince;
    public $buyerCountry;
    public $buyerPhone;
    public $buyerMobile;
    public $shippingDocumentType;
    public $shippingDocument;
    public $shippingFirstName;
    public $shippingLastName;
    public $shippingCompany;
    public $shippingEmailAddress;
    public $shippingAddress;
    public $shippingCity;
    public $shippingProvince;
    public $shippingCountry;
    public $shippingPhone;
    public $shippingMobile;
    private static $auth;
    
    public function __construct() {
        $this->PseAuth   = new PseAuth();
        $this->ipAddress = $this->Ip();
        $this->userAgent = $this->Browser();
    }
    
    public function getBankList()
    {  
        switch ($this->PseAuth->status) {
            case 1:
                $urlpse = $this->PseAuth->url_prod;
                break;
            
            default:
                $urlpse = $this->PseAuth->url_test;
                break;
        }
       
        //@TODO verificar la funcion init_set en el php.ini 
        //ini_set("default_socket_timeout",60);
        $client = new \nusoap_client($urlpse,'wsdl');
     
        $err = $client->getError();
        if( $err ){
           return $err.'soy un lindo error';
        }else{
            self::$auth = new Authentication();
            $result = $client->call('getBankList',$this->PseAuth->auth );
            $client->clearDebug();
            $err = $client->getError();
            if(isset($err) && $err !== null ){
                return $err;
            }else{
                if(isset($result) && $result !== null && $result !== false){
                    return  $result;
                }else{
                   return json_encode("No se pudo obtener la lista de entidades financieras, por favor intente mas tarde");
                }
            }
        }
    }
    
    public function createTransaction(){
        switch ($this->PseAuth->status) {
            case 1:
                $urlpse = $this->PseAuth->url_prod;
                break;
            
            default:
                $urlpse = $this->PseAuth->url_test;
                break;
        }
        
        $params = [
            'auth' =>[
                'login'      => $this->PseAuth->login,
                'tranKey'    => $this->PseAuth->hashkey,
                'seed'       => $this->PseAuth->seed
            ],
            'transaction' =>[
                'bankCode'       => $this->bankCode,
                'bankInterface'  => $this->bankInterface,
                'returnURL'      => $this->returnURL,
                'reference'      => $this->reference,
                'description'    => $this->clean($this->description),
                'language'       => $this->language,
                'currency'       => $this->currency,
                'totalAmount'    => $this->totalAmount,
                'taxAmount'      => $this->taxAmount,
                'devolutionBase' => $this->devolutionBase,
                'tipAmount'      => $this->tipAmount,
                'payer'          => $this->infoPlayer,
                'buyer'          => $this->infoBuyer,
                'shipping'       => $this->infoShipping,
                'ipAddress'      => $this->ipAddress,
                'userAgent'      => $this->userAgent
            ]   
        ];
        
        //@TODO verificar la funcion init_set en el php.ini 
        ini_set("default_socket_timeout",30);
        
        $client = new \nusoap_client($urlpse,'wsdl');
        $client->clearDebug();
        $err = $client->getError();
        if( $err ){
           return $err;
        }else{
            $client->clearDebug();
            $err = $client->getError();
            if(isset($err) && $err !== null ){
                return $err;
            }else{
                $result = $client->call('createTransaction',$params);

                if (isset($result['createTransactionResult'])) {
                   return $this->responseCreateTransaction($result);
                } else {
                   return $result;
                }
            }    
        }    
    }
    
    public function getTransactionInformation() {
      
        switch ($this->PseAuth->status) {
            case 1:
                $urlpse = $this->PseAuth->url_prod;
                break;
            
            default:
                $urlpse = $this->PseAuth->url_test;
                break;
        }
        $params = [
                    'auth' => [
                         'login'      => $this->PseAuth->login,
                         'tranKey'    => $this->PseAuth->hashkey,
                         'seed'       => $this->PseAuth->seed
                    ],
                    'transactionID' => $this->transId
                ];
        ini_set("default_socket_timeout",30);
        
        $client = new \nusoap_client($urlpse,'wsdl');
        $err = $client->getError();
        if( $err ){
           return $err;
        }else{
        
           $result = $client->call('getTransactionInformation',$params);     
   
           return $result;
        }   
      
    }
    
    public function infoPlayer() {
        $structureInfoPlayer = [
            'documentType' => $this->playerDocumentType,
            'document'     => $this->playerDocument,
            'firstName'    => $this->playerFirstName,
            'lastName'     => $this->playerLastName,
            'company'      => $this->playerCompany,
            'emailAddress' => $this->playerEmailAddress,
            'address'      => $this->playerAddress,
            'city'         => $this->playerCity,
            'province'     => $this->playerProvince,
            'country'      => $this->playerCountry,
            'phone'        => $this->playerPhone,
            'mobile'       => $this->playerMobile,
        ];
        new PsePerson($structureInfoPlayer); // valida la estructura 
        $this->infoPlayer  = $structureInfoPlayer; 
    }
    
    public function infoBuyer() {
        $structureInfoBuyer = [
            'documentType'  => $this->buyerDocumentType,
            'document'      => $this->buyerDocument,
            'firstName'     => $this->buyerFirstName,
            'lastName'      => $this->buyerLastName,
            'company'       => $this->buyerCompany,
            'emailAddress'  => $this->buyerEmailAddress,
            'address'       => $this->buyerAddress,
            'city'          => $this->buyerCity,
            'province'      => $this->buyerProvince,
            'country'       => $this->buyerCountry,
            'phone'         => $this->buyerPhone,
            'mobile'        => $this->buyerMobile,
        ];
        new PsePerson($structureInfoBuyer); // valida la estructura 
        $this->infoBuyer = $structureInfoBuyer;
    }
    
    public function infoShipping() {
        $structureInfoShipping = [
            'documentType' => $this->shippingDocumentType,
            'document'     => $this->shippingDocument,
            'firstName'    => $this->shippingFirstName,
            'lastName'     => $this->shippingLastName,
            'company'      => $this->shippingCompany,
            'emailAddress' => $this->shippingEmailAddress,
            'address'      => $this->shippingAddress,
            'city'         => $this->shippingCity,
            'province'     => $this->shippingProvince,
            'country'      => $this->shippingCountry,
            'phone'        => $this->shippingPhone,
            'mobile'       => $this->shippingMobile,
        ];
        new PsePerson($structureInfoShipping); // valida la estructura 
        $this->infoShipping = $structureInfoShipping;
    }
    
    private function responseCreateTransaction($array) {
        $response = $array['createTransactionResult'];
        if (isset($response['returnCode'])) {
            return $response;
        }
    }
    
    public function params(){
    	return $this->PseAuth->paramAuth;
    }
    
    private function Ip() {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        if (array_key_exists('HTTP_X_FORWARDED_FOR',$_SERVER)) {
            $ipAddress = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
        }
        return $ipAddress;
    }
    
    private function Browser() {
        $browser = array("IE", "OPERA", "MOZILLA", "NETSCAPE", "FIREFOX", "SAFARI", "CHROME");
        $os = array("WIN", "MAC", "LINUX");
        $info['browser'] = "OTHER";
        $info['os'] = "OTHER";
        foreach ($browser as $parent) {
            $s = strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $parent);
            $f = $s + strlen($parent);
            $version = substr($_SERVER['HTTP_USER_AGENT'], $f, 15);
            $version = preg_replace('/[^0-9,.]/', '', $version);
            if ($s) {
                $info['browser'] = $parent;
                $info['version'] = $version;
            }
        }
        foreach ($os as $val) {
            if (strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $val) !== false)
                $info['os'] = $val;
        }
        return $info['browser'] . ' ' . $info['version'];
    }
    
    public function clean($string) {
        $string = preg_replace('/[^A-Za-z0-9\-]/',' ', $string); // Removes special chars.
        $finaltext = preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
        return  $this->htmlEncode($finaltext);
    }
   
    public function htmlEncode($s) {
        return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
    }
   
    public function genericTextVal($string,$size = null,$name = null){
        if(isset($string) and $string!= null){ 
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
    public function vallanguage($language){
       $language_codes = [
        'en','aa','ab','af','am','ar','as','ay','az','ba','be','bg', 
        'bh','bi','bn','bo','br','ca','co','cs','cy','da','de','dz', 
        'el','eo','es','et','eu','fa','fi','fj','fo','fr','fy','ga', 
        'gd','gl','gn','gu','ha','hi','hr','hu','hy','ia','ie','ik', 
        'in','is','it','iw','ja','ji','jw','ka','kk','kl','km','kn', 
        'ko','ks','ku','ky','la','ln','lo','lt','lv','mg','mi','mk', 
        'ml','mn','mo','mr','ms','mt','my','na','ne','nl','no','oc', 
        'om','pa','pl','ps','pt','qu','rm','rn','ro','ru','rw','sa', 
        'sd','sg','sh','si','sk','sl','sm','sn','so','sq','sr','ss', 
        'st','su','sv','sw','ta','te','tg','th','ti','tk','tl','tn', 
        'to','tr','ts','tt','tw','uk','ur','uz','vi','vo','wo','xh', 
        'yo','zh','zu', 
        ];
       if(!in_array($language,$language_codes)){
          throw new Exception('El lenguaje es invalido '.$language);
       }
    }
    public function valmoney($money){
        $money_codes = [
            'AFA','AWG','AUD','ARS','AZN','BSD','BDT','BBD','BYR','BOB',
            'BRL','GBP','BGN','KHR','CAD','KYD','CLP','CNY','COP','CRC',
            'HRK','CPY','CZK','DKK','DOP','XCD','EGP','ERN','EEK','EUR',
            'GEL','GHC','GIP','GTQ','HNL','HKD','HUF','ISK','INR','IDR',
            'ILS','JMD','JPY','KZT','KES','KWD','LVL','LBP','LTL','MOP',
            'MKD','MGA','MYR','MTL','BAM','MUR','MXN','MZM','NPR','ANG',
            'TWD','NZD','NIO','NGN','KPW','NOK','OMR','PKR','PYG','PEN',
            'PHP','QAR','RON','RUB','SAR','CSD','SCR','SGD','SKK','SIT',
            'ZAR','KRW','LKR','SRD','SEK','CHF','TZS','THB','TTD','TRY',
            'AED','USD','UGX','UAH','UYU','UZS','VEB','VND','AMK','ZWD'
        ];
        if(!in_array($money,$money_codes)){
          throw new Exception('La moneda es invalida '.$money);
        }
    }
    public function doubleValidation($double,$name){
        if(isset($double) && $double !== null){
            if(!is_double($double)){
               throw new Exception($name." no es valida"); 
            }
        }else{
            throw new Exception($name." no esta definida");
        }
    }
  
    //@TODO set player values 
    public function setPlayerDocumentType($playerDocumentType = null){
        $this->playerDocumentType = $playerDocumentType;
    } 
    
    public function setPlayerDocument($playerDocument = null){
        $this->playerDocument = $playerDocument;
    } 
    
    public function setPlayerFirstName($playerFirstName = null){
        $this->playerFirstName = $this->clean($playerFirstName);
    } 
    
    public function setPlayerLastName($playerLastName = null){
        $this->playerLastName = $this->clean($playerLastName);
    } 
    
    public function setPlayerCompany($playerCompany = null){
        $this->playerCompany = $this->clean($playerCompany);
    } 
    
    public function setPlayerEmailAddress($playerEmailAddress = null){
        $this->playerEmailAddress = $playerEmailAddress;
    } 
    
    public function setPlayerAddress($playerAddress = null){
        $this->playerAddress = $playerAddress;
    } 
    
    public function setPlayerCity($playerCity = null){
        $this->playerCity = $this->clean($playerCity);
    } 
    
    public function setPlayerProvince($playerProvince  = null){
        $this->playerProvince = $playerProvince;
    } 
    
    public function setPlayerCountry($playerCountry = null){
        $this->playerCountry = $playerCountry;
    } 
    
    public function setPlayerPhone($playerPhone = null){
        $this->playerPhone = $playerPhone;
    } 
    
    public function setPlayerMobile($playerMobile = null){
        $this->playerMobile = $playerMobile;
    } 
    //@TODO set buyer values 
    public function setBuyerDocumentType($buyerDocumentType = null){
       $this->buyerDocumentType = $buyerDocumentType;
    } 
    
    public function setBuyerDocument($buyerDocument = null){
        $this->buyerDocument = $buyerDocument;
    } 
    
    public function setBuyerFirstName($buyerFirstName = null){
        $this->buyerFirstName = $this->clean($buyerFirstName);
    } 
    
    public function setBuyerLastName($buyerLastName = null){
        $this->buyerLastName = $this->clean($buyerLastName);
    } 
    
    public function setBuyerCompany($buyerCompany = null){
        $this->buyerCompany = $this->clean($buyerCompany);
    } 
    
    public function setBuyerEmailAddress($buyerEmailAddress = null){
        $this->buyerEmailAddress = $buyerEmailAddress;
    } 
    
    public function setBuyerAddress($buyerAddress = null){
        $this->buyerAddress = $buyerAddress;
    } 
    
    public function setBuyerCity($buyerCity = null){
        $this->buyerCity = $this->clean($buyerCity);
    } 
    
    public function setBuyerProvince($buyerProvince  = null){
        $this->buyerProvince = $buyerProvince;
    } 
    
    public function setBuyerCountry($buyerCountry = null){
        $this->buyerCountry = $buyerCountry;
    } 
    
    public function setBuyerPhone($buyerPhone = null){
        $this->buyerPhone = $buyerPhone;
    } 
    
    public function setBuyerMobile($buyerMobile = null){
        $this->buyerMobile = $buyerMobile;
    } 
    
    //@TODO set shipping 
    public function setShippingDocumentType($shippingDocumentType = null){
        $this->shippingDocumentType = $shippingDocumentType;
    } 
    
    public function setShippingDocument($shippingDocument = null){
        $this->shippingDocument = $shippingDocument;
    }
    
    public function setShippingFirstName($shippingFirstName = null){
        $this->shippingFirstName = $this->clean($shippingFirstName);
    } 
    
    public function setShippingLastName($shippingLastName = null){
        $this->shippingLastName = $this->clean($shippingLastName);
    } 
    
    public function setShippingCompany($shippingCompany = null){
        $this->shippingCompany = $this->clean($shippingCompany);
    } 
    
    public function setShippingEmailAddress($shippingEmailAddress = null){
        $this->shippingEmailAddress = $shippingEmailAddress;
    } 
    
    public function setShippingAddress($shippingAddress = null){
        $this->shippingAddress = $shippingAddress;
    } 
    
    public function setShippingCity($shippingCity = null){
        $this->shippingCity = $this->clean($shippingCity);
    } 
    
    public function setShippingProvince($shippingProvince  = null){
        $this->shippingProvince = $shippingProvince;
    } 
    
    public function setShippingCountry($shippingCountry = null){
        $this->shippingCountry = $shippingCountry;
    } 
    
    public function setShippingPhone($shippingPhone = null){
        $this->shippingPhone = $shippingPhone;
    } 
    
    public function setShippingMobile($shippingMobile = null){
        $this->shippingMobile = $shippingMobile;
    } 
    
    //@TODO set info createTransaction 
    public function setBankCode($bankCode = null){
        $this->genericTextVal($bankCode,4,'bankCode');   
        $this->bankCode = $bankCode;
    }
    
    public function setBankInterface($bankInterface = null){
        $this->genericTextVal($bankInterface,1,'bankInterface'); 
        switch ($bankInterface) {
            case '0':
            case '1':
                $this->bankInterface = $bankInterface;
                break;    

            default:
                throw new Exception("bankInterface es un string igual a 0 o 1");
                break;
        }
    }
    
    public function setReturnURL($returnURL = null){
        $this->genericTextVal($returnURL,255,'returnURL'); 
        if (filter_var($returnURL, FILTER_VALIDATE_URL) === FALSE) {
            
            throw new Exception("returnURL invalida");
            
        }else{
            
            $this->returnURL = $returnURL;
            
        }   
     
    }
    
    public function setReference($reference = null){
        $this->genericTextVal($reference,32,'reference');    
        $this->reference = $reference;
    }
    
    public function setDescription($description = null){
        $this->genericTextVal($description,255,'description');    
        $this->description = $description;
    }
    
    public function setLanguage($language = null){
        $this->genericTextVal($language,2,'language');   
        $this->vallanguage($language);   
        $this->language = $language;
    }
    
    public function setCurrency($currency = null){
        $this->genericTextVal($currency,3,'currency'); 
        $this->valmoney($currency);   
        $this->currency = $currency;
    }
    
    public function setTotalAmount($totalAmount = null){
        $this->doubleValidation($totalAmount,'totalAmount');   
        $this->totalAmount = $totalAmount;
    }
    
    public function setTaxAmount($taxAmount = null){
        $this->doubleValidation($taxAmount,'taxAmount');  
        $this->taxAmount = $taxAmount;
    }
    
    public function setDevolutionBase($devolutionBase = null){
        $this->doubleValidation($devolutionBase,'devolutionBase');   
        $this->devolutionBase = $devolutionBase;
    }
    
    public function setTipAmount($tipAmount = null){
        $this->doubleValidation($tipAmount,'tipAmount');    
        $this->tipAmount = $tipAmount;
    }
    
    // @TODO set $transId 
    public function setTransId($transId){
        $this->transId = $transId;
    }
}

