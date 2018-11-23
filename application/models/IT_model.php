<?php
class IT_model extends CI_Model {
   //$data = array();

	public function __construct()	{
            $this->load->database();
            //$this->load->library('session');
            $this->load->library('email');
 //     $this->load->helper('email');

//      $this->email->initialize($config);  
      //require_once 'HTTP/Request.php';
      //require("mail/class.phpmailer.php");

   //   $this->load->http();
      
      $data = array();     
	}

    public function print_header($chan, $data) { 
        print($data); 
        return strlen($data); 
    } 

        
    public function GetComputers($data){
        try{
            // Set New Filter 
            $defaultStatus = "AND Status <> 'Dumped' ";
            $filter = "";
           
            // Computers
            if(isset($data['Status']) AND $data['Status']!==''){
                $filter = "AND Status = '".$data['Status']."' ";
            // Now handled above
            } else {
                $filter = "AND Status <> 'Dumped' ";
            }
            if(isset($data['Location']) AND $data['Location']!==''){
                $filter.="AND Location = '".$data['Location']."' ";
            }
            if(isset($data['User']) AND $data['User']!==''){
                $filter.="AND User = '".$data['User']."' ";
            }
            if(isset($data['Type']) AND $data['Type']!==''){
                $filter.="AND Type = '".$data['Type']."' ";
            }
            if(isset($data['Make']) AND $data['Make']!==''){
                $filter.="AND Make = '".$data['Make']."' ";
            }
            if(isset($data['Model']) AND $data['Model']!==''){
                $filter.="AND Model = '".$data['Model']."' ";
            }
            if(isset($data['Serial_No']) AND $data['Serial_No']!==''){
                $filter.="AND Serial_No = '".$data['Serial_No']."' ";
            }
            if(isset($data['Computer_Name']) AND $data['Computer_Name']!==''){
                $filter.="AND Computer_Name = '".$data['Computer_Name']."' ";
            }
            if(isset($data['Tag_No']) AND $data['Tag_No']!==''){
                $filter.="AND Tag_No = '".$data['Tag_No']."' ";
            }
            //if($filter == "") {
            //    $filter = "AND Status <> 'Dumped' ";
            //}
            // If a filter has been set preserve the values 
            //$this->session->unset_userdata('filter');
            //if($filter!==$defaultStatus){ 
         /*   if($filter!==""){ 
                $this->session->set_userdata('filter', $filter);
            } elseif($this->session->userdata('filter')) {
                $filter = $this->session->userdata('filter');
            } else {
                $this->session->unset_userdata('filter');
                $filter = $defaultStatus;
            }
        */
            $data['filter'] = $filter;
            $ListOrder = (isset($data['ListOrder'])?$data['ListOrder']: "Location, User, Type, Serial_No ");
            $data['ListOrder'] = $ListOrder;
            $sql = "SELECT ALTAG, Type, Make, Model, Serial_No, Tag_No, Computer_Name, OS, "
                ."Location, User, Status, Year, "
                . "Entered_Date, Entered_by, Checked_Date, Checked_By, Updated_Date, Updated_By "
                ."FROM computers "
                ."WHERE TRUE ".$filter
                ."ORDER BY ".$ListOrder;
            //echo $sql;
            $query = $this->db->query($sql);
            $computers = $query->result_array();
            $data['computers'] = $computers;
            $data['count'] = count($computers);
            $data['message'] = substr($filter,4); 
            $data['message'] = 'Filter: <b>'.substr($filter,4).'</b> &nbsp; &nbsp; Total Count: <b>'. count($computers).'</b>'; // Hide "AND " from the start of filter 
            //$data['message'] = count($computers);
        }catch (Exception $e) {
            echo $e->message();
            echo var_dump($sql);
        }
        return($data);
    }    

    public function exportData($data){
        $csv = "";
        $firstLineFields = '';
        try{
            $lines = $data['computers'];
            foreach($lines as $line){
                if($firstLineFields===''){
                    $firstLineFields = array_keys($line);
                    $csv.=implode(", ",$firstLineFields);
                    $csv.="\r\n";
                    $firstLineFields = array_flip($firstLineFields);
                }
                $line = str_replace(",",": ", $line);
                $csv.=implode(", ",array_merge($firstLineFields,$line));
                $csv.="\r\n";
            }
            $data['csv'] = $csv;
        } catch (Exception $e) {
            echo $e->message();
            //echo var_dump($sql);
            $data['csv'] = $e->message();
        }
      
        return($data);
    }        

    public function GetEquipLists($data){
        try{
            $filter = $data['filter'];
            // Locations
            $sql = "SELECT Location FROM computers "
            //    ."WHERE TRUE " .$filter
                ."GROUP BY Location ";
            $query = $this->db->query($sql);
            $locations = $query->result_array();
            $data['locations'] = $locations;
           // Users
            $sql = "SELECT User FROM computers "
            //    ."WHERE TRUE ".$filter
                ."GROUP BY User ";
            $query = $this->db->query($sql);
            $users = $query->result_array();
            $data['users'] = $users;
            
             // Type
            $sql = "SELECT Type FROM computers "
            //    ."WHERE TRUE ".$filter
                ."GROUP BY Type ";
            $query = $this->db->query($sql);
            $types = $query->result_array();
            $data['types'] = $types;
            // Make
            $sql = "SELECT Make FROM computers "
            //    ."WHERE TRUE ".$filter
                ."GROUP BY Make ";
            $query = $this->db->query($sql);
            $makes = $query->result_array();
            $data['makes'] = $makes;
            // Model
            $sql = "SELECT Model FROM computers "
            //    ."WHERE TRUE ".$filter
                ."GROUP BY Model ";
            $query = $this->db->query($sql);
            $models = $query->result_array();   
            $data['models'] = $models;
            // SerialNos
            $sql = "SELECT Serial_No FROM computers "
            //    ."WHERE TRUE ".$filter
                ."GROUP BY Serial_No ";
            $query = $this->db->query($sql);
            $serials = $query->result_array();
            $data['serials'] = $serials;
            // TagNOs
            $sql = "SELECT Tag_No FROM computers "
            //    ."WHERE TRUE ".$filter
                ."GROUP BY Tag_No ";
            $query = $this->db->query($sql);
            $tags = $query->result_array();
            $data['tags'] = $tags;
            // Status
            $sql = "SELECT Status FROM computers "
            //    ."WHERE TRUE ".$filter
                ."GROUP BY Status ";
            $query = $this->db->query($sql);
            $statuses = $query->result_array();
            $data['statuses'] = $statuses;
            // OS
            $sql = "SELECT OS FROM computers "
            //    ."WHERE TRUE ".$filter
                ."GROUP BY OS ";
            $query = $this->db->query($sql);
            $oss = $query->result_array();
            $data['oss'] = $oss;
            // Computer Names
            $sql = "SELECT Computer_Name FROM computers "
            //    ."WHERE TRUE ".$filter
                ."GROUP BY Computer_Name ";
            $query = $this->db->query($sql);
            $compnames = $query->result_array();
            $data['compnames'] = $compnames;
 
        }catch (Exception $e) {
            echo $e->message();
            echo var_dump($sql);
        }
        return($data);
    }
   
    public function GetFilter($data){
        try{
            // Save Current Computer List Filter Variables in Session Variables
            
            $this->session->set_userdata('Status',$data['Status']);
            $this->session->set_userdata('Location', $data['Location']);
            $this->session->set_userdata('User', $data['User']);
            $this->session->set_userdata('Type', $data['Type']);
            $this->session->set_userdata('Make', $data['Make']);
            $this->session->set_userdata('Model', $data['Model']);
            $this->session->set_userdata('Serial_No', $data['Serial_No']);
            $this->session->set_userdata('Computer_Name', $data['Computer_Name']);
            $this->session->set_userdata('Tag_No', $data['Tag_No']);
        
            }catch (Exception $e) {
            echo $e->message();
            $data['Error'] = $e->message();
        //    echo var_dump($sql);
        }
        return($data);
    }
   
    public function SetFilter($data){
        try{
            // Collect previous Filter settings from session variables
            $FV = $this->session->userdata('Status');
            if (!empty($FV)) { 
                $data['Status'] = $this->session->userdata('Status');
            }
            $FV = $this->session->userdata('Location');
            if (!empty($FV)) { 
                $data['Location'] = $this->session->userdata('Location');
            }           
            $FV = $this->session->userdata('User');
            if (!empty($FV)) { $data['User'] = $FV;}
            $FV = $this->session->userdata('Type');
            if (!empty($FV)) { $data['Type'] = $FV;}
            $FV = $this->session->userdata('Make');
            if (!empty($FV)) { $data['Make'] = $FV;}
            $FV = $this->session->userdata('Model');
            if (!empty($FV)) { $data['Model'] = $FV;}
            $FV = $this->session->userdata('Serial_No');
            if (!empty($FV)) { $data['Serial_No'] = $FV;}
            $FV = $this->session->userdata('Computer_Name');
            if (!empty($FV)) { $data['Computer_Name'] = $FV;}
            $FV = $this->session->userdata('Tag_No');
            if (!empty($FV)) { $data['Tag_No'] = $FV;}
        /*   
            $data['Location'] = $this->session->userdata('Location');
            $data['User'] = $this->session->userdata('User');
            $data['Type'] = $this->session->userdata('Type');
            $data['Make'] = $this->session->userdata('Make');
            $data['Model'] = $this->session->userdata('Model');
            $data['Serial_No'] = $this->session->userdata('Serial_No');
            $data['Computer_Name'] = $this->session->userdata('Computer_Name');
            $data['Tag_No'] = $this->session->userdata('Tag_No');
          */
         
        }catch (Exception $e) {
            echo $e->message();
            $data['Error'] = $e->message();
        //    echo var_dump($sql);
        }
        return($data);
    }
   
    public function GetEquipment($data){
        try{
            $sql = "SELECT ALTag, "
                ."TRIM(Location) AS Location, "
                ."TRIM(User) AS User, "
                ."TRIM(Status) AS Status, "
                ."TRIM(Type) AS Type, "
                ."TRIM(Make) AS Make, "
                ."TRIM(Model) AS Model, "
                ."TRIM(Serial_No) AS Serial_No, "
                ."TRIM(Tag_No) AS Tag_No, "
                ."TRIM(Computer_Name) AS Computer_Name, "
                ."TRIM(OS) AS OS, "
                ."if(Std_Image,'Y','N') as Std_Image, "
                ."if(ITS,'Y','N') as ITS, Description, Notes, Year, "
                ."Entered_Date, Entered_By, Dumped_Date, Dumped_By, "
                ."Checked_Date, Checked_By, Updated_Date, Updated_By "
                ."FROM computers WHERE ALTag = ".$data['ALTag']." ";
            $query = $this->db->query($sql);
            $equip = $query->row_array();
            $data['equip'] = $equip;
       }catch (Exception $e) {
            echo $e->message();
            echo var_dump($sql);
        }
        return($data);
   }
   
    public function SetEquipment($data){
        $data['sql'] = 'No SQL Set';
        try{
            $UserData = $data['UserData'];
            if(isset($data['ALTag']) AND $data['ALTag']!=""){
                $sql = "UPDATE computers SET "
                    ."Location = TRIM('".$data['txtLocation']."'), "
                    ."User = TRIM('".$data['txtUser']."'), "
                    ."Status = TRIM('".$data['txtStatus']."'), "
                    ."Type = TRIM('".$data['txtType']."'), "
                    ."Make = TRIM('".$data['txtMake']."'), "
                    ."Model = TRIM('".$data['txtModel']."'), "
                    ."Serial_No = TRIM('".$data['txtSerial_No']."'), "  
                    ."Tag_No = TRIM('".$data['txtTag_No']."'), "
                    ."Computer_Name = TRIM('".$data['txtComputer_Name']."'), "
                    ."OS = TRIM('".$data['txtOS']."'), "
                    ."Std_Image = ".($data['txtStd_Image']=='Y'?1:0).", "
                    ."ITS = ".($data['txtITS']=='Y'?1:0).", "
                    ."Description = TRIM('".$data['txtDescription']."'), "
                    ."Notes = TRIM('".$data['txtNotes']."'), "
                    ."Updated_By = '".$UserData['user']."' "
                    ."WHERE ALTag = ".$data['ALTag']." ";
            } else {
                $sql = "INSERT INTO computers ("
                    ."Location, User, Status, "
                    ."Type, Make, Model, "
                    ."Serial_No, Tag_No, Computer_Name, OS, "
                    ."Std_Image, ITS, Description, Notes, Year, "
                    ."Entered_Date, Entered_By "
                    .") values ("
                    ."TRIM('".$data['txtLocation']."'), TRIM('".$data['txtUser']."'), "
                    ."TRIM('".$data['txtStatus']."'),"
                    ."TRIM('".$data['txtType']."'), TRIM('".$data['txtMake']."'), "
                    ."TRIM('".$data['txtModel']."'), "
                    ."TRIM('".$data['txtSerial_No']."'), TRIM('".$data['txtTag_No']."'), "
                    ."TRIM('".$data['txtComputer_Name']."'), "
                    ."TRIM('".$data['txtOS']."'), "
                    .($data['txtStd_Image']=='Y'?1:0).", "
                    .($data['txtITS']=='Y'?1:0).", "
                    ."TRIM('".$data['txtDescription']."'), TRIM('".$data['txtNotes']."'), "
                    ."YEAR(NOW()), NOW(), "
                    ."'".$UserData['user']."'  "
                    .")";
            }
           $data['sql'] = $sql;
           // ERROR entering blanks not values !!! 
           $this->db->query($sql);
           // Obtain latest ALTag
           if($data['ALTag']==0){
                $sql = "SELECT MAX(ALTag) AS ALTag FROM computers ";
                $query = $this->db->query($sql);
                $equip = $query->row_array();
                $data['ALTag'] = $equip['ALTag'];
           }
           // Log all changes to the database
           $sql = "INSERT INTO computerslog SELECT * FROM computers WHERE ALTag = ".$data['ALTag']." ";
           $this->db->query($sql);
        }catch (Exception $e) {
           echo $e->message();
        }
        return($data);
    }
   
   public function chkVar($var){
      if(!isset($var)){
         $var = '';
      }
      return($var);
   }
   
  
}
  