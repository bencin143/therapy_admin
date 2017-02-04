<?php
include('connect_db.php');
include('tabgen_php_functions.php');// all the function/ methodes are in this php file
function fetch_url_info($url)
        {
                libxml_use_internal_errors(true);
                $c = file_get_contents($url);
                $d = new DomDocument();
                $d->loadHTML($c);
                $xp = new domxpath($d);
                $list=array();
                foreach ($xp->query("//meta[@property='og:title']") as $el) {
                        $list['title']=$el->getAttribute("content");
                }
                foreach ($xp->query("//meta[@property='og:description']") as $el) {
                        $list['desc']=$el->getAttribute("content");
                }
                foreach ($xp->query("//meta[@property='og:image']") as $el) {
                        $list['imgsrc']=$el->getAttribute("content");
                }
                return $list;
        }
$token = get_token_from_header();
if($token==null){
	echo json_encode(array("status"=>false,"message"=>"Your request is unauthorized."));
}
else{
	$user_id = getUserIdByToken($conn,$token);
	if($user_id==null){
		echo json_encode(array("status"=>false,"message"=>"Invalid token"));
	}
	else
	{
		if(isAdmin($conn,$user_id)){//check if the user is admin or not
			$news_title = $_POST['news_title'];
			$news_headline = $_POST['news_headline'];
			$news_details = $_POST['news_details'];
			$tab_id = $_POST['tab_id'];
			$ext_link = $_POST['ext_link'];
                        $fetch_det = fetch_url_info($ext_link);
                      
			
			if(empty($tab_id)){
				echo json_encode(array("status"=>false,"message"=>"Please send tab Id under which the news is to be posted."));
			}
			
			else{
				$id = randId(26);//creating unique id
                                if($news_title== '' ){
                                $news_title=$fetch_det['title'];
                                $news_title= substr($news_title, 0,39);
                                
                                }
				$news_title=str_replace ("'","''", $news_title);
                                if($news_headline== ''){
                                $news_headline=$fetch_det['desc'];
                                $news_headline= substr($news_headline, 0,99);
                                
                                }
				$news_headline=str_replace ("'","''", $news_headline);
				$news_details=str_replace ("'","''", $news_details);
                                $news_details=$news_details.$fetch_det['title'].'<br>'.$fetch_det['desc'];
                                $news_details=str_replace ("'","''", $news_details);
                                $imgsrc=$fetch_det['imgsrc'];
                               
				$created_at = time()*1000;
				$status = "true";
				$query = "insert into News(Id,CreateAt,title,headline,Details,Link,Active,tab_id,Image) values('$id',$created_at,'$news_title',
				'$news_headline','$news_details','$ext_link','$status','$tab_id','$imgsrc')";
                               
                            
                            
				if($conn->query($query)){
					echo json_encode(array("status"=>true,"message"=>"News posted successfully."));
				}
				else{
					echo json_encode(array("status"=>false,"message"=>"An internal problem occurs."));
				}
			}
		}
		else{
			echo json_encode(array("status"=>false,"message"=>"You are not authorised for this action."));
		}
	}
}


?>
