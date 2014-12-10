<?php


 set_include_path('library');

require_once 'Zend/Loader.php';
Zend_Loader::loadClass('Zend_Gdata_YouTube');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
//debug
error_reporting(E_ALL);
//yt account info
$yt_user = 'username'; //youtube username or gmail account
$yt_pw = 'password'; //account password
$yt_source = 'Zenobia Test'; //name of application (can be anything)

//video path

$video_url = "g.mp4";

//yt dev key
$yt_api_key = 'your-youtube-developer-key'; //your youtube developer key get your one here https://code.google.com/apis/youtube/dashboard/gwt/index.html

//login in to YT
$authenticationURL= 'https://www.google.com/youtube/accounts/ClientLogin';
$httpClient = Zend_Gdata_ClientLogin::getHttpClient(
							              $username = $yt_user,
							              $password = $yt_pw,
							              $service = 'youtube',
							              $client = null,
							              $source = $yt_source, // a short string identifying your application
							              $loginToken = null,
							              $loginCaptcha = null,
							              $authenticationURL);

$yt = new Zend_Gdata_YouTube($httpClient, $yt_source, NULL, $yt_api_key);

$myVideoEntry = new Zend_Gdata_YouTube_VideoEntry();
 echo "Start Application.....";
$filesource = $yt->newMediaFileSource($video_url);
#$filesource->setContentType(/*mime_content_type ($video_url)*/"video/mp4"); //make sure to set the proper content type. 
$filesource->setContentType("video/mp4"); //make sure to set the proper content type. 
$filesource->setSlug("TEST");
 
#$myVideoEntry->setMediaSource(/*$filesource*/"Video");
$myVideoEntry->setMediaSource($filesource);
 
$myVideoEntry->setVideoTitle('JUNO Movie');
$myVideoEntry->setVideoDescription('JUNO Movie');
// Note that category must be a valid YouTube category !
$myVideoEntry->setVideoCategory('Comedy');
 
// Set keywords, note that this must be a comma separated string
// and that each keyword cannot contain whitespace
$myVideoEntry->SetVideoTags('cars, funny');
 
// Upload URI for the currently authenticated user

$uploadUrl ='https://uploads.gdata.youtube.com/feeds/users/default/uploads';
 
// Try to upload the video, catching a Zend_Gdata_App_HttpException
// if availableor just a regular Zend_Gdata_App_Exception
 $result=array();
try {
	//echo "Start Uplaoding.....";
    $newEntry = $yt->insertEntry($myVideoEntry,
                                 $uploadUrl,
                                 'Zend_Gdata_YouTube_VideoEntry');
	
	$result=simplexml_load_string($newEntry->getBody());
} catch (Zend_Gdata_App_HttpException $httpException) {
    echo $httpException->getRawResponseBody();
} catch (Zend_Gdata_App_Exception $e) {
    echo $e->getMessage();
}
//Youtube gdata video URL
echo "<br/>".$result->id;
//Youtube video ID http://www.youtube.com/watch?v=########
$video_id=end((explode('/', $result->id)));

echo "<br/>".$video_id;

?>
