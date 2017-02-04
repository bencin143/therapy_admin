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
    $PatientID = $_GET['pntID'];
    $query="SELECT P.DOCNUM MRN,
			PV.DOCNUM IPNO,
			TO_CHAR(PV.ADMISSIONDATETIME,'DD/MON/YYYY') DOA,
			FN_GETPATIENTNAME(P.DOCID) PATIENTNAME,
			FN_GET_EMPLOYEE_NAME(EMP.EMPLOYEECODE) CONSULTANT,
			INV.DOCNUM,
			SUM(NVL(INV.TOTALBILLAMOUNT,0)) TOTALBILL,
			INV.PATIENTDISCOUNTAMOUNT, 
			RECTBL.ADVANCE ADVANCEADJUSTED,
			(SUM(NVL(INV.TOTALBILLAMOUNT,0)) -(NVL(INV.PATIENTDISCOUNTAMOUNT,0)+NVL(RECTBL.ADVANCE,0))) OUTSTANDING

		FROM PATIENTS P 
		INNER JOIN PATIENTVISITS PV  ON P.DOCID=PV.DOCIDPATIENTS
		LEFT  JOIN (SELECT SUM(DEPOSITAMOUNT) ADVANCE,DOCIDPATIENTS,DOCIDVISITS FROM
		 RECEIPTS REC WHERE REC.ISCANCELLED='N'  AND REC.ISREFUNDED='N' AND REC.ISADJUSTED='Y' GROUP BY DOCIDPATIENTS,DOCIDVISITS ) 			RECTBL ON (RECTBL.DOCIDPATIENTS = P.DOCID
		 AND RECTBL.DOCIDVISITS = PV.DOCID)
		INNER JOIN INVOICES INV ON INV.DOCIDVISITS=PV.DOCID
		INNER JOIN SETTELEDINVOICES SI ON INV.DOCID=SI.INVOICENO
		INNER JOIN VIEWBEDDETAILS VBD ON  PV.ADMITTINGBEDID=VBD.BEDLOCCODE
		INNER JOIN EMPLOYEES EMP ON PV.ADMITTINGCONSULTANTCODE=EMP.EMPLOYEECODE
		INNER JOIN PATIENTINSURANCEDETAILS INSU ON PV.DOCIDPATIENTINSURANCEDETAILS=INSU.DOCDETAILID
		WHERE 
	
		PV.VISITTYPECODE='IN'  
		AND INV.DOCUMENTTYPECODE='INV001' AND P.DOCNUM='$PatientID' AND PV.DOCNUM='G2459'
		GROUP BY 
		P.DOCNUM,P.DOCID,PV.ADMISSIONDATETIME,
		EMP.EMPLOYEECODE,RECTBL.ADVANCE,
		PV.DOCNUM,INSU.SPONSORTYPECODE,INV.PATIENTDISCOUNTAMOUNT,SI.AMOUNT,INV.DOCNUM";



    $stid = oci_parse($conn, $query);
    oci_execute($stid);
    $row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
    
    if($row != false) {
       $err = array("status"=>"true");
	$row=array_merge($err,$row);
       print_r(json_encode(array("BillDetails"=>$row)));
    }else{
      //echo "Please Enter Valid Patient ID";
	//$row = array("state"=>"error");
       $err = array("status"=>"false");
	//$row=array_merge($err,$row);
       print_r(json_encode(array("BillDetails"=>$err)));
    }

}else{
//  echo "Please Enter Valid Patient ID";
//	$row = array("state"=>"error");
      $err = array("status"=>"false");
	//$row=array_merge($err,$row);
       print_r(json_encode(array("BillDetails"=>$err)));
}
?>
