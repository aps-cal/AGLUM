<?php
$allowed = array(
    'https://warwick.ac.uk',
    'https://moodle.warwick.ac.uk'
);
header('Content-Type: application/javascript');
if(isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed)) header("Access-Control-Allow-Origin: ".$_SERVER['HTTP_ORIGIN']);
header('Access-Control-Max-Age: 3628800');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Credentials: true');
class IT_controller extends CI_Controller {
    private $token = NULL;  // SSO Token from Warwick SSO Cookie
    private $wsos_api_key = '&wsos_api_key=f0b29422ccba737bf49724604580fbfe';
    public $user = array(); // The currently logged in SSO User
    public function __construct(){
        parent::__construct();
	$this->load->library('session');
        $this->load->library('Email'); 
        $this->load->helper('Email');
        $this->load->helper('Cookie');
        $this->load->helper('Html');
        $this->load->helper('Url');
        $this->load->model('IT_model');
        $this->load->database();
    }

   
    public function page($page){
        $user = $this->loaduser();
        if(!isset($user['id']) && !isset($user['user'])) {       
            echo "<html><body><h2>You are not logged in with a valid Warwick ID</h2></body></html>";
            return; // If theres nothing to record... don't
        } elseif(!(($user['deptcode']=='ET' ||  $user['deptcode']=='IN' ) && $user['warwickitsclass']=='Staff')) {
            // Only allow staff from CAL or ITS to access aldb.warwick.ac.uk/jsonp
            echo "<html><body><h2>You do not appear to be Applied Lingustics or ITS staff</h2></body></html>";
            return; // If theres nothing to record... don't
        }
        $data = $this->loaddata();
        $page = strtolower($page);  
        switch(strtolower($page)):
            case 'test':    
                $this->Ajax_model->test($data); break; 
            case 'computers':
                $data = $this->IT_model->GetComputers($data);
                $data = $this->IT_model->GetEquipLists($data);                
                echo $_GET['data']. '(' . json_encode($data) . ');'; 
                break;
            case 'getequipment':
                //echo "<h4>ALTag = ".$data['ALTag']."</h4>";
                $data = $this->IT_model->GetEquipment($data);
                $data = $this->IT_model->GetComputers($data);
                $data = $this->IT_model->GetEquipLists($data);
                echo $_GET['data']. '(' . json_encode($data) . ');'; 
                break;
            case 'setequipment':
                $data = $this->IT_model->SetEquipment($data);
                $data = $this->IT_model->GetComputers($data);
                $data = $this->IT_model->GetEquipLists($data);
                echo $_GET['data']. '(' . json_encode($data) . ');'; 
                break;  
            case 'export':
                $data = $this->IT_model->GetComputers($data);
                $data = $this->IT_model->exportData($data);
                echo $_GET['data']. '(' . json_encode($data) . ');'; 
                break;  
            endswitch;
    }
    
    private function safeString($str){
        // Purpose of this check is to remove characters not expected in strings that can be used by SQL Insertion. 
        //$str = mysqli_real_escape_string($this->db,$str);
        $str = $this->db->escape_str($str);
        $str = str_replace(')', '', $str);
        $str = str_replace('(', '', $str);
        $str = str_replace(';', '', $str);
        // $str = str_replace(',', '', $str); Commas are used in names and listorders
        return($str);
    }
    
    public function loaddata(){
        $data = array();  
        /*$keys = array('ALTag','Status','Location','User', 'Type','Make','Model',
            'Serial_No','Computer_Name','Tag_No','filter','New','ListOrder',
            'txtType','txtLocation','txtMake','txtUser','txtModel','txtStatus',
            'txtSerial_No','txtTag_no','txtOS','txtComputerName','txtITS','txtStd_Image',
            'txtDescription','txtNotes',
            'EnteredDate','DumpedDate','CheckedDate','UpdatedDate');
        foreach($keys as $key){
            if (filter_input(INPUT_GET, $_GET[$key], FILTER_VALIDATE_EMAIL)) {
                $data[$key] = filter_input(INPUT_GET, $_GET[$key], FILTER_SANITIZE_STRING);
            }
            if (filter_input(INPUT_POST, $key, FILTER_VALIDATE_EMAIL)) {
            //    $data[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRING);
            } 
            echo $key." = ".$data[$key]."<br/>";
        }
         * */
    
        foreach($_GET as $key=>$value){$data[$this->safeString($key)] = $this->safeString($value); }
        foreach($_POST as $key=>$value){$data[$this->safeString($key)] = $this->safeString($value);}
        foreach($_REQUEST as $key=>$value){$data[$this->safeString($key)] = $this->safeString($value);}
        return($data);
    }
    
    
    private function loaduser(){
        //$userdata = array();
        //try{
            $token = get_cookie("WarwickSSO");
            $userdata = $this->getUser($token);
            $this->logUser($userdata);
        //}
        return($userdata);
    }
    private function getUser($token){
        try{
            error_reporting(E_STRICT); 
            $pageURL = "https://websignon.warwick.ac.uk/sentry?requestType=1&token=".$token;
            $ch = curl_init(); 
            // set URL and other appropriate options
            curl_setopt($ch, CURLOPT_URL, $pageURL);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Should prevent output to browser
            $res = curl_exec($ch); 
        } catch (Exception $e) {
            printf('Error message (if any): '.curl_error($ch).'\n\n');
            printf("<p>");
            var_dump(curl_getinfo($ch)); 
            printf("</p><p>");
            var_dump($res); 
            printf("</p>");
        }
        curl_close($ch);
        return $this->parse($res);
    }
    
    private function parse($returnSSOString){
        $array = array();   
        $pieces = explode("\n", $returnSSOString);
        foreach ($pieces as $line) {
            if(strpos($line,'=') !== false) {
                list($field, $string) = explode('=', $line);
                //echo $field."  =>  ".$string."<br>";
                if(!empty($field)) {
                    if($field == 'id') {
                        $array['id'] = (int) $string;
                        
                    } else {
                        //$array[$field] = makesafe($string);
                        $array[$field] = $string;
                        
                    }
                }
            }
        }
        return $array;
    }
    
    private function logUser($user){
        if(!isset($user['id']) && !isset($user['user'])) return; // If theres nothing to record... don't
        
        
        $sql = "INSERT INTO user_log (UniversityID,UserID,Name,Firstname,Lastname,Title, "
            ."Email, Phone, Dept, DeptCode, Student, Staff, ITSClass, TeachingStaff, "
            ."SignonIP, RemoteIP) "
            ."VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";
        $values = array($user['id'], $user['user'], $user['name'], $user['firstname'], $user['lastname'], $user['title'],
            $user['email'], $user['telephoneNumber'],$user['dept'], $user['deptcode'], ($user['student'] == 'true' ? 1 : 0), ($user['staff'] == 'true' ? 1 : 0),
            $user['warwickitsclass'], ($user['warwickteachingstaff'] == 'true' ? 1 : 0), $user['urn:websignon:ipaddress'], $_SERVER['REMOTE_ADDR']);
        try{
            $this->db->query($sql, $values);
        }catch (Exception $e) {
         //      echo $e->message();
        }   
    }
}
        