<form action="ph.php" method="POST">
    <input type="text" name="pid">
    <input type="submit" value="getdata">
</form>
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

   $query="SELECT  OH.DOCNUM ORDERNO,RI.ITEMDESC,MO.QUANTITY,(TO_CHAR(OH.ORDERDATE,'DD/MM/YYYY')) ORDERDATE,
        PV.DOCNUM VISITNO,PA.DOCNUM MRN,FN_GET_PATIENT_NAME(PA.DOCNUM) PATIENT,VT.VISITTYPENAME,
        FN_GET_EMPLOYEE_NAME(OH.ORDERPLACERCODE) DOCTOR,RU.UOMNAME
FROM    MEDICATIONORDERS MO
INNER JOIN ORDERHEADER OH ON (MO.DOCIDORDERHEADER=OH.DOCID)
INNER JOIN STATUS OS ON (MO.ORDERSTATUSCODE=OS.STATUSNUMBER AND OS.STATUSTYPECODE = 'MEDSTS')
INNER JOIN ITEM RI ON (MO.ITEMCODE=RI.ITEMCODE)
INNER JOIN UOM RU ON (RI.SALESUOMCODE=RU.UOMCODE)
INNER JOIN PATIENTS PA ON (OH.DOCIDPATIENTS=PA.DOCID)
INNER JOIN PATIENTVISITS PV ON (OH.DOCIDPATIENTVISITS=PV.DOCID)
INNER JOIN VISITTYPES VT ON (PV.VISITTYPECODE=VT.VISITTYPECODE)
WHERE PA.DOCNUM='".$_POST['pid']."' AND ROWNUM<5 ORDER BY OH.DOCDATE DESC";


	//$query="SELECT CH.DOCNUM from PATIENTS CH where rownum<3 ";
echo "done";
    //$query1 = "SELECT P.DOCNUM FROM PATIENTS P LIMIT 1,100";
 $stid = oci_parse($conn, $query);
    oci_execute($stid);
    $row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
    //$row = array('NAME'=>'JOHN','AGE'=>'31 years','GENDER'=>45,'BGROUP'=>'O+');
echo "done";
    if($row != false) {
echo "done data retrieved";
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
