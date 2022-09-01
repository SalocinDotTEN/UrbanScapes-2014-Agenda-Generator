<?php
// if (session_id() == '') {
// 	session_start();
// }
// link to the font file no the server
$fontname = 'font/Montserrat-Bold.ttf';
// controls the spacing between text
$i=30;
//JPG image quality 0-100
$quality = 100;

$fileId = md5(time());

function html2rgb($color)
{
    if ($color[0] == '#')
        $color = substr($color, 1);

    if (strlen($color) == 6)
        list($r, $g, $b) = array($color[0].$color[1],
                                 $color[2].$color[3],
                                 $color[4].$color[5]);
    elseif (strlen($color) == 3)
        list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
    else
        return false;

    $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);

    return array($r, $g, $b);
}

function create_image($user){

		global $fontname;	
		global $quality;
		global $fileId;
		$file = "agendaImgs/".$fileId.".jpg";	
	
	// if the file already exists dont create it again just serve up the original	
	if (!file_exists($file)) {
		//base image
		$numRows = count($user);
		//base image
		$im = imagecreatefromjpeg("img/schedule_bg".$numRows.".jpg");
		//That triangle.. -.-
		$triangle = imagecreatefrompng("img/schedule_triangle.png");
		// this defines the starting height for the text block
		$i = 30;
		$y = 520;
		// loop through the array and write the text
		foreach ($user as $value){
			$venueColour = html2rgb($value['venue_colour']);
			imagettftext($im, 20, 0, 132, $y+$i, imagecolorallocate($im, 0, 0, 0), $fontname, strtoupper($value['name']));
			$timeStart = date("g:ia", strtotime($value['timestart']));
			if ($timeStart == "12:01am") {
				$timeStart = "12:00am";
			}
			// $timeEnd = date("g:ia", strtotime($value['timeend']));
			imagettftext($im, 19, 0, 757, $y+$i, imagecolorallocate($im, 0, 0, 0), $fontname, $timeStart); //.' - '.$timeEnd);
			imagefilledrectangle($im, 1030, $y+$i+22, 1327, $y+$i-43, imagecolorallocate($im, $venueColour[0], $venueColour[1], $venueColour[2]));
			imagecopy($im, $triangle, 1030, $y+$i-41, 0, 0, 36, 64);
			imagettftext($im, 15, 0, 1075, $y+$i, imagecolorallocate($im, 0, 0, 0), $fontname, strtoupper($value['venue']));
			// this one is to separate the lines of text.
			$i = $i+100;
		}
			// create the image
			imagejpeg($im, $file, $quality);
			
	}
						
		return $file;	
}

$user = array();

for ($i=1; $i <= 43; $i++) {
	array_push($user, array(
		'name'=> 'JAGWAR MA FEAT. TAYLOR SWIFT',
		'venue' => 'UPFRONT STAGE',
		'venue_colour' => '#eeee22',
		'timestart' => '18:30',
		'timeend' => '21:30')
	);
}

// if (isset($_SESSION['inputter']) && ($_SESSION['inputter'] != '')) {
// 	$user = unserialize($_SESSION['inputter']);
// } else {
	// $user = array(
	// 	array(
	// 		'name'=> 'JAGWAR MA FEAT. TAYLOR SWIFT',
	// 		'venue' => 'UPFRONT STAGE',
	// 		'venue_colour' => '#eeee22',
	// 		'timestart' => '18:30',
	// 		'timeend' => '21:30'),
	// );
// }

// var_dump($user);

// run the script to create the image
$filename = create_image($user);
// header("Location: http://senedi-wip.com/urbanscapes2014/my-agenda?file=".$fileId);
// exit;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Test Urbanscapes 2014 Agenda</title>
</head>

<body>
<?php
$ref = getenv("HTTP_REFERER");
echo $ref; 
?>
<img src="agendaImgs/<?php echo $fileId; ?>.jpg" />

</body>
</html>