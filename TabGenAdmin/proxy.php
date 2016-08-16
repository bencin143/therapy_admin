<?php
//echo file_get_html("http://www.google.com");
//echo curl($_POST['address']);
echo "Hi this is proxy server..<br/>";
//echo curl("http://www.responsivegridsystem.com/");
$url = "https://youtu.be/Nubc09jTW-M";
/*$youtube_url = "www.youtube.com/watch?v=vpfzjcCzdtCk";

if (preg_match("/((http\:\/\/){0,}(www\.){0,}(youtube\.com){1} || (youtu\.be){1}(\/watch\?v\=[^\s]){1})/", $youtube_url) == 1)
{
    echo "Valid";
}
else
{
    echo "Invalid";
}*/

/*
$regex_pattern = "/(youtube.com|youtu.be)\/(watch)?(\?v=)?(\S+)?/";
$match;

if(preg_match($regex_pattern, $url, $match)){
    echo "Youtube video id is: ".$match[4];
}else{
    echo "Sorry, not a youtube URL";
}*/

$video_id=getYouTubeID($url);

if($video_id!=null)
echo "<iframe height='315' width='480' allowfullscreen='true' src='https://www.youtube.com/embed/"
.$video_id."?autoplay=0'></iframe>";
else echo "No video Id";
 function curl($url) {
        $ch = curl_init();  // Initialising cURL
        curl_setopt($ch, CURLOPT_URL, $url);    // Setting cURL's URL option with the $url variable passed into the function
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // Setting cURL's option to return the webpage data
        $data = curl_exec($ch); // Executing the cURL request and assigning the returned data to the $data variable
        curl_close($ch);    // Closing cURL
        return $data;   // Returning the data from the function
 }
 
function getYouTubeID($youtube_url){
	if(is_youtube_url($youtube_url)){
		$regex = "/(youtube.com|youtu.be)\/(watch)?(\?v=)?(\S+)?/";
		/*'![?&]{1}v=([^&]+)!'*/
		$YouTubeCheck = preg_match($regex, $youtube_url, $Data);
		If($YouTubeCheck){
			$VideoID = $Data[4];
			return $VideoID;
		}
		else{
			return null;
		}
	}
	else{
		//echo "Not a youtube url...<br/>";
		return null; 
	}
}

function is_youtube_url($youtube_url){
	$valid = preg_match("/((http\:\/\/){0,}(www\.){0,}(youtube\.com){1} || (youtu\.be){1}(\/watch\?v\=[^\s]){1})/", $youtube_url);
	return $valid;	
}
?>
