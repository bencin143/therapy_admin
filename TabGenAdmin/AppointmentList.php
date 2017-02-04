<?php
/* API Details
 * Parameter name	: pntID
 * Method 	 	: GET
 * URL Access Pattern	:?pntID=value_of_PatientID
*/
error_reporting(E_ALL);
ini_set('display_errors', 'On');
if(true){
    $DBstr = "(DESCRIPTION =
        (ADDRESS_LIST =
          (ADDRESS = (PROTOCOL = TCP)(HOST = 202.191.203.21)(PORT = 1521))
        )
        (CONNECT_DATA =
          (SERVICE_NAME = ABMHT2DB)
        )
      )";
    $conn = oci_pconnect('rcaremagnumfo', 'rcaremagnumfo', $DBstr);
//getting the patient id , passed as parameter, via GET method
    $EmpID = $_GET['EmpID'];
    //$ToDate = $_GET['ToDate'];
    //$Fromdate = $_GET['FromDate'];
    $query="SELECT P.DOCNUM MRN,FN_GET_PATIENT_NAME(P.DOCNUM) PATIENTNAME,PA.DOCNUM APPOINTMENTNO,
				SUBSTR(FN_GETAGE(TO_CHAR(PATIENTDOB,'DD/MM/YYYY')),1,8) AGE ,
				DECODE(P.PATIENTGENDERCODE,'M','MALE','FEMALE') SEX,
				FN_GET_EMPLOYEE_NAME(PA.RESOURCECODE) CONSULTANT,
				SERV.SERVICENAME APPOINTMENTTYPE,PS.APPOINTMENTSTATUSNAME,
				TO_CHAR(PA.STARTDATETIME,'DD/MON/YYYY HH:MI:SS AM')STARTDATETIME,
				TO_CHAR(PA.ENDDATETIME,'DD/MON/YYYY HH:MI:SS AM')ENDDATETIME,S.USERFULLNAME,
				PA.REMARKS,ST.SPONSORTYPENAME
				FROM PATIENTAPPOINTMENTS PA  
				INNER JOIN PATIENTS  P ON (PA.DOCIDPATIENTS=P.DOCID )
				INNER JOIN PATIENTVISITS PV ON (PA.DOCIDPATIENTVISITS = PV.DOCID)
				LEFT OUTER JOIN SERVICES SERV ON (PA.SERVICECODE=SERV.SERVICECODE)
				LEFT OUTER JOIN EMPLOYEES EMP ON (PA.RESOURCECODE=EMP.EMPLOYEECODE)
				INNER JOIN APPOINTMENTSTATUS PS ON (PA.APMNTSTATUSCODE=PS.APPOINTMENTSTATUSCODE)
				INNER JOIN SYSUSER S ON (PA.CREATEDBY=S.USERCODE)
				INNER JOIN PATIENTINSURANCEDETAILS PI ON (PA.DOCIDPATIENTS=PI.DOCIDPATIENTS)
				INNER JOIN SPONSORTYPES ST ON (PI.SPONSORTYPECODE = ST.SPONSORTYPECODE AND PI.ISDEFAULT = 'T')
			WHERE 
				PA.DOCDATE >= TO_DATE(TO_CHAR('09/03/2016'), 'DD/MM/YYYY')
				AND PA.DOCDATE <= sysdate 
				AND PA.APMNTSTATUSTYPECODE='AS' 
				AND PA.RESOURCECODE='$EmpID'
				AND PA.APMNTSTATUSCODE NOT IN ('13')";



    $stid = oci_parse($conn, $query);
    oci_execute($stid);
    //$row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
    	$output=null;
	$count=0;
//$row = $res->fetch(PDO::FETCH_ASSOC)
	while($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)){
		$output[]=$row;
		$count++;
	}
    if($count>0) {
       $err = array("status"=>"true");
	//$row=array_merge($err,$row);
       //print_r(json_encode(array("Appointmnts"=>$row)));
//print_r(json_encode($err));
print_r(json_encode(array("Appointmnts"=>$output)));
	
    }else{
      //echo "Please Enter Valid Patient ID";
	//$row = array("state"=>"error");
       $err = array("status"=>"false");
	//$row=array_merge($err,$row);
       print_r(json_encode(array("Appointmnts"=>$err)));
    }

}else{
//  echo "Please Enter Valid Patient ID";
//	$row = array("state"=>"error");
      $err = array("status"=>"false");
	//$row=array_merge($err,$row);
       print_r(json_encode(array("Appointmnts"=>$err)));
}
?>
