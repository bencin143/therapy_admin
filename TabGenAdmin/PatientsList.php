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
    $conscode = $_GET['conscode'];
	
    $query="SELECT PAT.DOCNUM MRN , FN_GET_PATIENT_NAME(PAT.DOCNUM) PATIENT,
			SUBSTR(FN_GETAGE(TO_CHAR(PAT.PATIENTDOB,'DD/MM/YYYY')),1,8)AGE,G.GENDERNAME,
			PV.DOCNUM VISITNO,
			TO_DATE(TO_CHAR(PV.ADMISSIONDATETIME,'DD/MON/YYYY'))||' / '|| TO_CHAR(PV.ADMISSIONDATETIME,'HH.MI AM') ADMISSIONDATE,
			FN_GET_EMPLOYEE_NAME(EMP.EMPLOYEECODE) ADMITTINGDOCTOR,
			LOC.LOCATIONNAME BED,
			LOC1.LOCATIONNAME ROOM,
			LOC2.LOCATIONNAME WARD,
			ST.SPONSORTYPENAME,
			PLN.PLANNAME ELGIBILTY,
			PC.MOBILENUMBER,
			PC.ADDRESS1||' ' ||PC.ADDRESS2 ADDRESS,
			CI.CITYNAME,
			PR.REFERRALPERSONNAME, 
			PID.CERTIFICATENO CERTIFICATENO,
			PID.POLICYNUMBER POLICYNUMBER,
			SY.USERFULLNAME,
			PID.EMPLOYEENUMBER EMPLOYEENUMBER
		FROM PATIENTS PAT 
		INNER JOIN PATIENTVISITS PV ON (PAT.DOCID = PV.DOCIDPATIENTS)
		LEFT OUTER JOIN GENDER G ON (PAT.PATIENTGENDERCODE = G.GENDERCODE)
		LEFT OUTER JOIN EMPLOYEES EMP ON (PV.ADMITTINGCONSULTANTCODE =  EMP.EMPLOYEECODE)
		INNER JOIN LOCATIONS LOC ON (PV.ADMITTINGBEDID = LOC.LOCATIONCODE)
		INNER JOIN LOCATIONS LOC1 ON (LOC.LOCATIONPARENTCODE = LOC1.LOCATIONCODE)
		INNER JOIN LOCATIONS LOC2 ON (LOC1.LOCATIONPARENTCODE = LOC2.LOCATIONCODE)
		LEFT OUTER JOIN PATIENTVISITINSDETAILS PID ON (PV.DOCIDPATIENTINSURANCEDETAILS=PID.DOCDETAILID AND PID.ISACTIVE='T' AND PID.ISDEFAULT='T')
		LEFT OUTER JOIN SPONSORTYPES ST ON (PID.SPONSORTYPECODE = ST.SPONSORTYPECODE)
		LEFT OUTER JOIN PLANS PLN ON (PID.PLANCODE = PLN.PLANCODE)
		INNER JOIN PATIENTCONTACTS PC ON (PAT.DOCID = PC.DOCIDPATIENTS AND PC.ISACTIVE = 'T')
		LEFT OUTER JOIN PATIENTREFFERALDETAILS PR ON (PAT.DOCID = PR.DOCIDPATIENTS AND PV.DOCID = PR.DOCIDPATIENTVISITS)
		LEFT OUTER JOIN CITIES CI ON (PC.CITYCODE=CI.CITYCODE)
		LEFT OUTER JOIN SYSUSER SY ON (PV.CREATEDBY = SY.USERCODE)
		WHERE PV.VISITTYPECODE IN ('IN')
			AND TO_DATE(TO_CHAR(PV.ADMISSIONDATETIME,'DD-MON-YYYY'))
	 		BETWEEN  TO_DATE(TO_CHAR('09/03/2016'), 'DD/MM/YYYY') AND  sysdate+1
			 AND PV.ADMITTINGCONSULTANTCODE='$conscode'";



    $stid = oci_parse($conn, $query);
    oci_execute($stid);
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
print_r(json_encode(array("PatientList"=>$output)));
	
    }else{
      //echo "Please Enter Valid Patient ID";
	//$row = array("state"=>"error");
       $err = array("status"=>"false");
	//$row=array_merge($err,$row);
       print_r(json_encode(array("PatientList"=>$err)));
    }

}else{
//  echo "Please Enter Valid Patient ID";
//	$row = array("state"=>"error");
      $err = array("status"=>"false");
	//$row=array_merge($err,$row);
       print_r(json_encode(array("PatientList"=>$err)));
}
?>
