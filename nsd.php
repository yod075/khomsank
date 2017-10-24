<?php
//created by khomsan k. tel 09-2523-6777
$access_token ='';

$adminid ='';
$nsbGroup = '';
$nsbGroupNuke ='';
require_once('');

date_default_timezone_set('Asia/Bangkok');
$hi = date('Hi');
$noti_one = '0800';
$noti_two = '1400';

$dd = date('d');
$dm = date('m');
$dy = date('Y')+543;
$today = $dd."-".$dm."-".$dy;
$mtomorow = date('d-m-Y', strtotime("+1 day", strtotime($today)));

$botname ='#### NSB NSD 2 ####';


$botmsg = "".$botname."\n";
$botmsg .= "คำสั่งที่สามารถใช้ได้โดยการพิมพ์ \n";
$botmsg .= "h,help = เรียกดูคำสั่ง \n";
$botmsg .= "ผู้บังคับบัญชา = ข้อมูลผู้บังคับบัญชา \n";
$botmsg .= "วิธี , วิธีเพิ่ม = วิธีเพิ่มข้อมูลภารกิจ \n";
$botmsg .= "ภารกิจวันนี้ = แสดงภารกิจวันนี้ \n";
$botmsg .= "ภารกิจพรุ่งนี้   = แสดงภารกิจวันพรุ่งนี้\n";
$botmsg .= "พิมพ์ m=18-10-2560   = แสดงภารกิจตามวันที่ต้องการ\n";
$botmsg .= "รูปแบบการพิมพ์คือ m=วัน-เดือน-ปี  \n";


$bot_array = array("h","H","Help","help","ผู้บังคับบัญชา","วิธี","วิธีเพิ่ม","ภารกิจวันนี้","ภารกิจพรุ่งนี้");





// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
    $eventType = $event['type'];
    $replyToken = $event['replyToken'];
    $timestamp = $event['timestamp'];
    $userId = $event['source']['userId'];
		//join group
		$groupId = $event['source']['groupId'];


		if($eventType == 'message') {
      $eventMessageType = $event['message']['type'];
			$eventMessageText = $event['message']['text'];

      //$inputid = $event['message']['id'];
      if($eventMessageType == 'text'){
				//!insert
				$subtext = substr($eventMessageText, 0,4);

				$splittext = explode('=',$eventMessageText);
				$checkm = $splittext[0];
				$checkdate = $splittext[1];

					if(in_array($eventMessageText,$bot_array)){

						if($eventMessageText=='h' || $eventMessageText=='H' || $eventMessageText=='help' || $eventMessageText=='Help'){
								$processtext = $botmsg;
						}elseif ($eventMessageText=='ผู้บังคับบัญชา') {
									$str="SELECT comNo,comName FROM nsbcommand order by comNo ASC ";
									$result=mysqli_query($con, $str) or die(mysqli_error());
									$row=mysqli_num_rows($result);

									if($row==0){
										$processtext = $botname."\n";
										$processtext .= "empty data \n";
									}else{
										$processtext = $botname."\n";
										while($row = $result->fetch_assoc()) {
											$processtext .= "[".$row['comNo']."] :".$row['comName']."\n";
					 				}
				 				}
						}elseif ($eventMessageText=='วิธี' || $eventMessageText=='วิธีเพิ่ม') {
							$processtext = $botname."\n";
							$processtext .= "การเพิ่มข้อมูลให้พิมพ์ดังนี้ \n";
							$processtext .= "!add=หมายเลขผู้บังคับบัญชาดูจาก(พิมพ์ ผู้บังคับบัญชา)\n";
							$processtext .= ",ชื่อเรื่อง\n";
							$processtext .= ",วันที่ รูปแบบ (XX-XX-256X)\n";
							$processtext .= ",เวลา รูปแบบ (XX.XX) เช่น 10.30\n";
							$processtext .= ",สถานที่ \n";
							$processtext .= ",โน๊ตหรืออื่นๆ เช่นการแต่งกาย ถ้าไม่มีใส่ -\n";
							$processtext .= "ตัวอย่างพิมพ์ !add=1,ประชุมติดตามความคืบหน้าการสืบสวนฯ,05-10-2560,10.30,ห้องประชุม ปส.2 บช.ปส.,- \n";
							$processtext .= "หรือส่งรายละเอียดมาไลน์ส่วนตัวผมเพิ่มข้อมูลให้ครับ";

							# code...
						}elseif ($eventMessageText=='ภารกิจวันนี้') {
							$str="SELECT * FROM nsbevent WHERE eventDateStart ='$today' order by comId ASC,eventTimeStart ASC";
							$result=mysqli_query($con, $str) or die(mysqli_error());
							$row=mysqli_num_rows($result);

							if($row==0){
								$processtext = $botname."\n";
								$processtext .= "ไม่มีภารกิจประจำวันที่ ".$today." \n";
							}else{
								$processtext = $botname."\n";
								$processtext .= "ภารกิจประจำวันที่ ".$today." \n";

								while($row = $result->fetch_assoc()) {
													$processtext .= $row['comName']."\n";

													$processtext .= "เวลา: ".$row['eventTimeStart']."\n";
													$processtext .= "เรื่อง: ".$row['eventName']."\n";
 													$processtext .= "สถานที่:".$row['eventLocation']."\n";

													$processtext .= "\n";


 							 }
							}


						}elseif ($eventMessageText=='ภารกิจพรุ่งนี้') {
							$str="SELECT * FROM nsbevent WHERE eventDateStart ='$mtomorow' order by comId ASC,eventTimeStart ASC";
							$result=mysqli_query($con, $str) or die(mysqli_error());
							$row=mysqli_num_rows($result);

							if($row==0){
								$processtext = $botname."\n";
								$processtext .= "ไม่มีภารกิจพรุ่งนี้ วันที่  ".$mtomorow." \n";
							}else{
								$processtext = $botname."\n";
								$processtext .= "ภารกิจพรุ่งนี้ วันที่ ".$mtomorow." \n";

								while($row = $result->fetch_assoc()) {
													$processtext .= $row['comName']."\n";

													$processtext .= "เวลา: ".$row['eventTimeStart']."\n";
													$processtext .= "เรื่อง: ".$row['eventName']."\n";
 													$processtext .= "สถานที่:".$row['eventLocation']."\n";

													$processtext .= "\n";


 							 }
							}


						}


					}elseif ($checkm =='m' || $checkm =='M') {

						$str="SELECT * FROM nsbevent WHERE eventDateStart ='$checkdate' order by comId ASC,eventTimeStart ASC";
						$result=mysqli_query($con, $str) or die(mysqli_error());
						$row=mysqli_num_rows($result);

						if($row==0){
							$processtext = $botname."\n";
							$processtext .= "ไม่มีภารกิจประจำวันที่ ".$checkdate." \n";
						}else{
							$processtext = $botname."\n";
							$processtext .= "ภารกิจประจำวันที่ ".$checkdate." \n";

							while($row = $result->fetch_assoc()) {
												$processtext .= $row['comName']."\n";

												$processtext .= "เวลา: ".$row['eventTimeStart']."\n";
												$processtext .= "เรื่อง: ".$row['eventName']."\n";
												$processtext .= "สถานที่:".$row['eventLocation']."\n";

												$processtext .= "\n";


						 }
						}

					}elseif ($subtext=='!add') {
						$splittext = explode('=',$eventMessageText);
						$chunk = $splittext[1];
						//$textFilter = explode(",", $subtext);
						$textFilter = explode(",", $chunk);
						$comId = $textFilter[0];
						$eventName = $textFilter[1];
						$eventDateStart = $textFilter[2];
						$eventTimeStart = $textFilter[3];
						$eventLocation = $textFilter[4];
						$eventNote = $textFilter[5];

						$str="SELECT * FROM nsbcommand WHERE comId ='$comId'";
						$result=mysqli_query($con, $str) or die(mysqli_error());
						$row=mysqli_num_rows($result);

							if($row==0){
								$processtext = $botname."\n";
								$processtext .= "ไม่สามารถบันทึกได้ โปรดตรวจสอบข้อมูล \n";
							}else{
								$row = $result->fetch_assoc();
								$comName = $row['comName'];

										$sql ="INSERT INTO nsbevent SET ";
										$sql .="eventName = '$eventName' ";
										$sql .=",comId = '$comId'";
										$sql .=",comName = '$comName'";
										$sql .=",eventDateStart = '$eventDateStart'";
										$sql .=",eventTimeStart = '$eventTimeStart'";
										$sql .=",eventLocation = '$eventLocation'";
										$sql .=",eventNote = '$eventNote'";
										if (mysqli_query($con, $sql)) {
											$processtext = $botname."\n";
											$processtext .= "บันทึกข้อมูลสำเร็จ\n";
											$processtext .= "ผู้เข้าร่วม: ".$comName.""."\n";
											$processtext .= "ชื่อเรื่อง: ".$eventName.""."\n";
											$processtext .= "วันที่: ".$eventDateStart.""."\n";
											$processtext .= "เวลา: ".$eventTimeStart.""."\n";
											$processtext .= "สถานที่: ".$eventLocation.""."\n";
											$processtext .= "เพิ่มเติม: ".$eventNote.""."\n";
											mysqli_close($con);
										}



								}
					}elseif ($subtext=='poke') {
								$splittext = explode('=',$eventMessageText);
								$nukeId = $splittext[1];
								if($nukeId=='1'){

									$str="SELECT * FROM nsbevent WHERE eventDateStart ='$today' order by comId ASC,eventTimeStart ASC";
									$result=mysqli_query($con, $str) or die(mysqli_error());
									$row=mysqli_num_rows($result);

									if($row==0){
										$processtext = $botname."\n";
										$processtext .= "ไม่มีภารกิจประจำวันที่ ".$today." \n";
									}else{
										$processtext = $botname."\n";
										$processtext .= "ภารกิจประจำวันที่ ".$today." \n";

										while($row = $result->fetch_assoc()) {
															$processtext .= $row['comName']."\n";

															$processtext .= "เวลา: ".$row['eventTimeStart']."\n";
															$processtext .= "เรื่อง: ".$row['eventName']."\n";
		 													$processtext .= "สถานที่:".$row['eventLocation']."\n";
															$processtext .= "\n";


		 							 }
									}

									// Build message to reply back
									$tomessages = [
										'type' => 'text',
										'text' => $processtext
									];

									$urlpush = 'https://api.line.me/v2/bot/message/push';
									$datapush = [
										'to' => $nsbGroupNuke,
										'messages' => [$tomessages],
									];

									$post = json_encode($datapush);
									$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
									$ch = curl_init($urlpush);
									curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
									curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
									curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
									curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
									$result = curl_exec($ch);
									curl_close($ch);
									echo $result . "\r\n";


								}elseif($nukeId=='2'){

									$str="SELECT * FROM nsbevent WHERE eventDateStart ='$mtomorow' order by comId ASC,eventTimeStart ASC";
									$result=mysqli_query($con, $str) or die(mysqli_error());
									$row=mysqli_num_rows($result);

									if($row==0){
										$processtext = $botname."\n";
										$processtext .= "ไม่มีภารกิจพรุ่งนี้ วันที่  ".$mtomorow." \n";
									}else{
										$processtext = $botname."\n";
										$processtext .= "ภารกิจพรุ่งนี้ วันที่ ".$mtomorow." \n";

										while($row = $result->fetch_assoc()) {
															$processtext .= $row['comName']."\n";

															$processtext .= "เวลา: ".$row['eventTimeStart']."\n";
															$processtext .= "เรื่อง: ".$row['eventName']."\n";
		 													$processtext .= "สถานที่:".$row['eventLocation']."\n";
															$processtext .= "\n";


		 							 }
									}

									// Build message to reply back
									$tomessages = [
										'type' => 'text',
										'text' => $processtext
									];

									$urlpush = 'https://api.line.me/v2/bot/message/push';
									$datapush = [
										'to' => $nsbGroupNuke,
										'messages' => [$tomessages],
									];

									$post = json_encode($datapush);
									$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
									$ch = curl_init($urlpush);
									curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
									curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
									curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
									curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
									$result = curl_exec($ch);
									curl_close($ch);
									echo $result . "\r\n";

								}else{

								}
					}else {
						//no respone
					}



        //sending method
  			$messages = [
  				'type' => 'text',
  				'text' => $processtext
  			];
  			$url = 'https://api.line.me/v2/bot/message/reply';
  			$data = [
  			  'replyToken' => $replyToken,
  			  'messages' => [$messages],
  			];
        $post = json_encode($data);
        $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        echo $result . "\r\n";

      }elseif ($eventMessageType == 'image') {

      }elseif ($eventMessageType == 'sticker') {

      }elseif ($eventMessageType == 'file') {

      }elseif ($eventMessageType == 'audio') {

      }elseif ($eventMessageType == 'video') {

      }

    }elseif($eventType == 'follow') {
      //get profile
  		$url = 'https://api.line.me/v2/bot/profile/'. $userId ;
  		$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
  		$ch = curl_init();
  		curl_setopt($ch, CURLOPT_URL, $url);
  		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
  		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  		$output = curl_exec($ch);
  		curl_close($ch);
  		$profile = json_decode($output, true);
  		$displayName = $profile['displayName'];
  		$pictureUrl = $profile['pictureUrl'];



			$str="SELECT userId FROM nsbusers WHERE userId ='$userId' ";
			$result=mysqli_query($con, $str) or die(mysqli_error());
			$row=mysqli_num_rows($result);

			if($row==0){
				$sql ="INSERT INTO nsbusers SET ";
				$sql .="userId = '$userId' ";
				$sql .=",displayName = '$displayName'";
				$sql .=",pictureUrl = '$pictureUrl'";

				if (mysqli_query($con, $sql)) {
					$processtext = "++  ".$botname."  ++"."\n";
					$processtext .= "++  สวัสดีครับคุณ ".$displayName."\n";
					$processtext .= "++  ขอบคุณที่เพิ่มเราเป็นเพื่อนครับ "."\n";
					mysqli_close($con);
				}
				}else{
				$processtext = "++  ".$botname."  ++"."\n";
				$processtext .= "++  สวัสดีครับคุณ ".$displayName."\n";
				$processtext .= "++  ขอบคุณที่กลับมาเป็นเพื่อนเราอีกครั้งครับ  "."\n";
			}


      // Build message to reply back
	    $messages = [
	      'type' => 'text',
	      'text' => $processtext
	    ];

			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
			  'replyToken' => $replyToken,
			  'messages' => [$messages],
			];

      $post = json_encode($data);
      $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
      $result = curl_exec($ch);
      curl_close($ch);
      echo $result . "\r\n";


    }elseif($eventType == 'join'){

			$sql ="INSERT INTO nsbgroup SET ";
			$sql .="groupId = '$groupId' ";
			if (mysqli_query($con, $sql)) {
						$processtext = "".$botname.""."\n";
						$processtext .= "สวัสดีครับทุกท่าน ขอบคุณที่รับเข้ากลุ่มครับ\n";
						$processtext .= "หากอยากทราบว่าผมทำอะไรได้บ้างพิมพ์ help หรือ h "."\n";

				}

			      // Build message to reply back
				    $messages = [
				      'type' => 'text',
				      'text' => $processtext
				    ];

						$url = 'https://api.line.me/v2/bot/message/reply';
						$data = [
						  'replyToken' => $replyToken,
						  'messages' => [$messages],
						];

			      $post = json_encode($data);
			      $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
			      $ch = curl_init($url);
			      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			      curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			      $result = curl_exec($ch);
			      curl_close($ch);
			      echo $result . "\r\n";

		}

  }//foreach events
//no event
}elseif($hi == $noti_one){

	$str="SELECT * FROM nsbevent WHERE eventDateStart ='$today' order by comId ASC,eventTimeStart ASC";
	$result=mysqli_query($con, $str) or die(mysqli_error());
	$row=mysqli_num_rows($result);

	if($row==0){
		$processtext = $botname."\n";
		$processtext .= "[AUTO]ไม่มีภารกิจประจำวันที่ ".$today." \n";
	}else{
		$processtext = $botname."\n";
		$processtext .= "[AUTO]ภารกิจประจำวันที่ ".$today." \n";
		$i = 1;
		while($row = $result->fetch_assoc()) {
							$processtext .= $row['comName']."\n";

							$processtext .= "เวลา: ".$row['eventTimeStart']."\n";
							$processtext .= "เรื่อง: ".$row['eventName']."\n";
							$processtext .= "สถานที่:".$row['eventLocation']."\n";

							$processtext .= "\n";
							$i++;

	 }
	}
	// Build message to reply back
	$tomessages = [
		'type' => 'text',
		'text' => $processtext
	];

	$urlpush = 'https://api.line.me/v2/bot/message/push';
	$datapush = [
		'to' => $nsbGroup,
		'messages' => [$tomessages],
	];

	$post = json_encode($datapush);
	$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
	$ch = curl_init($urlpush);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$result = curl_exec($ch);
	curl_close($ch);
	echo $result . "\r\n";

}elseif($hi == $noti_two){

	$str="SELECT * FROM nsbevent WHERE eventDateStart ='$mtomorow' order by comId ASC,eventTimeStart ASC";
	$result=mysqli_query($con, $str) or die(mysqli_error());
	$row=mysqli_num_rows($result);

	if($row==0){
		$processtext = $botname."\n";
		$processtext .= "[AUTO]ไม่มีภารกิจพรุ่งนี้ วันที่  ".$mtomorow." \n";
	}else{
		$processtext = $botname."\n";
		$processtext .= "[AUTO]ภารกิจพรุ่งนี้ วันที่ ".$mtomorow." \n";
		$i = 1;
		while($row = $result->fetch_assoc()) {
							$processtext .= $row['comName']."\n";

							$processtext .= "เวลา: ".$row['eventTimeStart']."\n";
							$processtext .= "เรื่อง: ".$row['eventName']."\n";
							$processtext .= "สถานที่:".$row['eventLocation']."\n";

							$processtext .= "\n";
							$i++;

	 }
	}

	// Build message to reply back
	$tomessages = [
		'type' => 'text',
		'text' => $processtext
	];

	$urlpush = 'https://api.line.me/v2/bot/message/push';
	$datapush = [
		'to' => $nsbGroup,
		'messages' => [$tomessages],
	];

	$post = json_encode($datapush);
	$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
	$ch = curl_init($urlpush);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$result = curl_exec($ch);
	curl_close($ch);
	echo $result . "\r\n";

}

echo "OK";

?>
