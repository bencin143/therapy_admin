
<?php

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
echo "done";
    $conn = oci_connect('rcaremagnumfo', 'rcaremagnumfo', $DBstr);
    //$PatientID = "AB16022579";

    //$PatientID = $_GET['pntID']; //"AB1000345";
/*
    $query="SELECT DISTINCT	CH.DOCNUM LABNO,
       					PAT.DOCNUM MRNNO,
				        FN_GET_PATIENT_NAME(PAT.DOCNUM) PATIENTNAME,
       					CASE
         				WHEN pat.patientgendercode = 'M' THEN
          				'MALE'
         				WHEN pat.patientgendercode = 'F' THEN
          				'FEMALE'
       					END SEX,
       					VT.VISITTYPENAME VISITTYPE,
       					CD.SAMPLEID SAMPLENO,
       					SPT.SPECIALITYNAME,
       					SP.SPECIMENNAME,
       					SER.SERVICENAME PROFILE,
       					SERV.SERVICENAME INVESTICATION,
       					lrd.resultnumeric RESULT,
       					lrd.normalminvalue || '-' || lrd.normalmaxvalue NORMALVALUES,
       					lrd.resulttemplatetext,
       					OH.DOCNUM ORDERNO,
       					trunc(OH.ORDERDATE) ORDERDATE,
       					CH.DOCDATE COLLECTIONDATE,
       					FN_GET_EMPLOYEE_NAME(EMP.EMPLOYEECODE) DOCTOR,
       					DEP.DEPARTMENTNAME DEPARTMENT,
       					ST.STATUSNAME STATUS,
       					prt.priorityname PRIORITY,
       					lrd.organismremarks,
       					lrd.microbiologyremarks
  				FROM 	COLLECTIONHEADER        CH,
       					COLLECTIONDETAILS       CD,
       					COLLECTIONSTATUSDETAILS CSD,
       					LABRESULTDETAILS        LRD,
       					STATUS                  ST,
       					SPECIMENS               SP,
       					PATIENTS                PAT,
       					SERVICES                SER,
       					SERVICES                SERV,
       					INVESTIGATIONPARAMETERS IVP,
       					SPECIALITIES            SPT,
       					LABORDERS               LO,
       					ORDERHEADER             OH,
       					VISITTYPES              VT,
       					EMPLOYEES               EMP,
       					DEPARTMENT              DEP,
       					ORGSTRUCTURE            ORG,
       					priorities              prt
 				WHERE 	CH.DOCID = CD.DOCIDCOLLECTIONHEADER
				   AND CD.DOCDETAILID = CSD.DOCDETAILIDCOLLDETAILS
				   AND CSD.DOCDETAILID = LRD.DOCDETAILIDCSD(+)
				   AND (CD.SAMPLESTATUS = ST.STATUSNUMBER AND ST.STATUSTYPECODE = 'LABSTS')
				   AND CD.SAMPLETYPE = SP.SPECIMENCODE
				   AND CH.DOCIDPATIENTS = PAT.DOCID
				   AND CSD.PROFILE = SER.SERVICECODE(+)
				   AND CSD.INVESTIGATION = SERV.SERVICECODE
				   AND LRD.PARAMETERCODE = IVP.CODE(+)
				   AND CD.SAMPLESENTOLOCATION = SPT.SPECIALITYCODE
				   AND CSD.DOCDETAILIDLABORDERS = LO.DOCDETAILID
				   AND LO.DOCIDORDERHEADER = OH.DOCID
				   AND OH.VISITTYPECODE = VT.VISITTYPECODE
				   AND OH.ORDERPLACERCODE = EMP.EMPLOYEECODE
				   AND OH.DEPARTMENTCODE = DEP.DEPARTMENTCODE
				   AND OH.ORGSTRUCTCODE = ORG.ORGSTRUCTCODE
				   AND lo.prioritycode = prt.prioritycode
				   AND   PAT.DOCNUM='AB1000321' AND ROWNUM<5    
				ORDER BY  CH.DOCNUM,CH.DOCDATE DESC";
*/

	$query="SELECT PATIENTPREFIX||' '|| PATIENTFIRSTNAME||''|| PATIENTMIDDLENAME|| '' || PATIENTLASTNAME AS NAME,
    SUBSTR(FN_GETAGE(TO_CHAR(P.PATIENTDOB,'DD/MM/YYYY')),1,8) AGE ,G.GENDERNAME AS GENDER,P.PATIENTBLOODGROUPCODE AS BGROUP
    FROM PATIENTS P
    LEFT OUTER JOIN GENDER G ON (P.PATIENTGENDERCODE = G.GENDERCODE) WHERE P.DOCNUM='AB16022327'";
echo "done";
    //$query1 = "SELECT P.DOCNUM FROM PATIENTS P LIMIT 1,100";
 //   $stid = oci_parse($conn, $query);
   // oci_execute($stid);
    $row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
    //$row = array('NAME'=>'JOHN','AGE'=>'31 years','GENDER'=>45,'BGROUP'=>'O+');
echo "done";
    if($row != false) {
echo "done";
       print_r(json_encode($row));
	/*$im = imagecreatetruecolor(250, 100);

	// Create some colors
	$white = imagecolorallocate($im, 255, 255, 255);
	$grey = imagecolorallocate($im, 128, 128, 128);
	$black = imagecolorallocate($im, 0, 0, 0);
	imagefilledrectangle($im, 0, 0, 249, 99, $white);
	// The text to draw
$text = <<<ABC
Name        : {$row['NAME']}
Age            : {$row['AGE']}
Gender     : {$row['GENDER']}
B-Group    : {$row['BGROUP']}
ABC;
	// Replace path by your own font path
	$font = '/var/www/html/OpenSans-Regular.ttf';

	// Add some shadow to the text
	//imagettftext($im, 20, 0, 11, 21, $grey, $font, $text);

	// Add the text
	imagettftext($im, 10, 0, 10, 20, $black, $font, $text);
	//header( "Content-type: image/png" );
	// Using imagepng() results in clearer text compared with imagejpeg()
	$filename = "/var/www/html/TabGenAdmin/mattermost_dist/mattermost/web/static/images/PatientID/".$_GET['pntID'].".png";
	imagepng($im, $filename);
	imagedestroy($im); */
    }else{
      echo "Please Enter Valid Patient ID";
    }

}else{
  echo "Please Enter Valid Patient ID";
}
?>
