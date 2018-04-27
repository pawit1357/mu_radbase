<?php
class MailUtil {

	public static function sendMail($to_email, $subject, $content) {
		
// 		$mail = Yii::app ()->Smtpmail;
// 		$mail->IsSMTP ();
// 		$mail->SetFrom ( 'pawitvaap@gmail.com', 'MU-RADBASE' );
// 		$mail->Subject = $subject;
// 		$mail->MsgHTML ( $content );
// 		$mail->CharSet = "utf8";
// 		$mail->ClearAddresses ();
// 		$mail->AddAddress ( "pawitvaap@gmail.com", "" );
		
// 		if ($mail->Send ()) {
// 			return true;
// 		} else {
// 			return false; 
// 		}
// 		echo "SEND MAIL>>>>".$to_email;
		return true;
	}

	
	public static function GetEmailContent($recipient,$detail) {
		return '
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tbody>
			<tr>
				<td style="padding-bottom: 20px;">

						<table border="0" cellpadding="0" cellspacing="0" width="600px"
							style="height: 100%;">
							<tbody>
								<tr>
									<td valign="top" class="bodyContent">
										<table border="0" cellpadding="1" cellspacing="0"
											width="100%">
											<tbody>
												<tr>
													<td valign="top">เรียน  '.$recipient.',</td>
												</tr>
												<tr>
													<td>&nbsp;&nbsp;&nbsp;&nbsp;มีรายการบันทึกข้อมูล'.$detail.' ที่รอการอนุมัติ  กรุณากดลิงค์แล้วลงทะเบียนเข้าใช้งานระบบ เพื่อทำรายการอนมัติ <a href="http://myapps1357.com/radbase">MU-RADBASE</a> </td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
				</td>
			</tr>
		</tbody>
	</table>
<br><br><br>
-------------------------------------------------------------------------------------
<br>ศูนย์บริหารความปลอดภัยอาชีวอนามัยและสิ่งแวดล้อม
<br>มหาวิทยาลัยมหิดล ศาลายา
<br>http://radbase.mahidol/
';
	}

}
?>
