<?php
$id = $_GET ['id'];
$answers = Answers::model ()->findAll ( array (
		"condition" => "department_id=" . $id 
) );
?>
<head>
<style>
.content_poll {
	width: 100%;
	margin: 0 auto;
	padding: 10px 40px;
	background-color: white;
}

.content_poll .poll {
	position: relative;
	margin-top: 30px;
}

.content_poll .poll .num {
	position: absolute;
	left: -40px;
	top: -5px;
	background: #eeeeee;
	color: #666666;
	padding: 3px 4px;
	font-size: 14px;
	box-shadow: -7px 0px 0px #dddddd;
}

.content_poll .poll h2 {
	font-weight: 200;
	font-color: green;
	font-size: 16px;
}

.content_poll .poll .choices {
	margin: 10px 0 0 0;
}

.content_poll .poll .choices .choice {
	width: 100%;
	margin: 10px 0;
}

.content_poll .poll .choices .choice label {
	color: #666;
	font-size: 13px;
	margin-left: 5px;
}

.fileUpload {
	position: relative;
	overflow: hidden;
	margin: 10px;
}

.fileUpload input.upload {
	position: absolute;
	top: 0;
	right: 0;
	margin: 0;
	padding: 0;
	font-size: 20px;
	cursor: pointer;
	opacity: 0;
	filter: alpha(opacity = 0);
}
</style>
<script
	src="<?php echo ConfigUtil::getAppName();?>/assets/global/plugins/jquery.min.js"
	type="text/javascript"></script>

<script type="text/javascript">

	function getFilename($id,$elm) {
		 var fn = $($elm).val();
		 $('#txt_updesc_'+$id).val(fn);
			
	}
	
jQuery(document).ready(function () {
});
</script>
</head>
<form id="Form1" method="POST" enctype="multipart/form-data">
	<div class="portlet light bordered">
		<div class="portlet-title">
			<div class="caption">
				<?php echo  MenuUtil::getMenuName($_SERVER['REQUEST_URI'])?>
			</div>
			<div class="actions">
			</div>
		</div>
		<div class="portlet-body form">
			<div class="form-body">
				<div class="content_poll">
				
					<div class="poll">
						<div class="num">1</div>
						<h2>ส่วนงาน(คณะ)
							มีการแต่งตั้งคณะกรรมการและหรือแทนผู้รับผิดชอบในการกำกับ
							ดูแลงานด้านความปลอดภัยทางรังสีหรือไม่?</h2>
						<div class="choices">
							<div class="choice">

								<input type="radio" value="1" id="1" name="answer_choices[1]"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "1", "1") ?> /><label
									for="1">มี
									(โปรดแนบสำเนาคำสั่งแต่งตั้งคณะกรรมการหรือรายชื่อผู้รับผิดชอบ
									(Radiation Safety Officer:RSO)</label> <label> <input
									name="answer_files[]" type="file" style="display: none;"
									onchange='getFilename(1,this)' /><a> &nbsp;&nbsp;<i
										class="fa fa-paperclip"></i>
								</a></label>
																<input id="txt_updesc_1" placeholder="" disabled="disabled"
									style="border: none" value="<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "1", "")?>" />
<?php if(QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "1", "") != ''){?>
<a title="Download" class="fa fa-download" 
									href="<?php echo ConfigUtil::getAppName().''. QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "1", "")?>"></a>
									<input type="hidden" name="tmpPath[1]" value="<?php echo ConfigUtil::getAppName().''. QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "1", "")?>">
<?php }?>

								
							</div>
							<div class="choice">
								<input type="radio" value="0" id="1" name="answer_choices[1]"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "1", "0") ?> /><label
									for="2">ไม่มี &nbsp;&nbsp;</label> <input type="text"
									name="answer_texts[1]"
									value="<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::TEXT_TYPE, "1", "") ?>"
									style="border: none; border-bottom-style: dotted;" />

							</div>
						</div>
					</div>
					<div class="poll">
						<div class="num">2</div>
						<h2>ส่วนงาน(คณะ)
							มีแต่งตั้งคณะกรรมการพิจารณารับรองงานวิจัยด้านรังสีหรือไม่?</h2>
						<div class="choices">
							<div class="choice">
								<input type="radio" value="1" id="2" name="answer_choices[2]"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "2", "1") ?> /><label
									for="1">มี (โปรดแนบสำเนาคำสั่งแต่งตั้งคณะกรรมการ)</label> <label>
									<input name="answer_files[]" type="file" style="display: none;"
									onchange='getFilename(2,this)' /><a> &nbsp;&nbsp;<i
										class="fa fa-paperclip"></i>
								</a>
								</label> 
								<input id="txt_updesc_2" placeholder="" disabled="disabled"
									style="border: none" value="<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "2", "")?>" />
<?php if(QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "2", "") != ''){?>
<a title="Download" class="fa fa-download"
									href="<?php echo ConfigUtil::getAppName().''. QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "2", "")?>">&nbsp;</a>
									<input type="hidden" name="tmpPath[2]" value="<?php echo ConfigUtil::getAppName().''. QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "2", "")?>">
<?php }?>


							</div>
							<div class="choice">
								<input type="radio" value="0" id="2" name="answer_choices[2]"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "2", "0") ?> /><label
									for="2">ไม่มี &nbsp;&nbsp;</label> <input type="text"
									name="answer_texts[2]"
									value=" <?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::TEXT_TYPE, "2", "") ?>"
									style="border: none; border-bottom-style: dotted;" />
							</div>
						</div>
					</div>

					<div class="poll">
						<div class="num">3</div>
						<h2>ส่วนงาน(คณะ) มีแผนงานความปลอดภัยทางรังสีที่ครอบคลุมด้านต่าง ๆ
							หรือไม่?</h2>
						<div class="choices">
							<div class="choice">

								<input type="radio" value="1" id="3" name="answer_choices[3]"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "3", "1") ?> /><label
									for="1">มีแผนงาน</label> <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input
									type="checkbox" value="1" id="3.1.1"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "3.1.1", "1")?>
									name="answer_choices[3.1.1]" /><label for="1">ด้านการปฏิบัติงานที่เกี่ยวข้องกับการใช้รังสีที่มีผลต่อสุขภาพ
								</label> <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input
									type="checkbox" value="1" id="3.1.2"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "3.1.2", "1")?>
									name="answer_choices[3.1.2]" /><label for="1">ด้านการประเมินความเสี่ยงด้านความปลอดภัยทางรังสี
								</label> <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input
									type="checkbox" value="1" id="3.1.3"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "3.1.3", "1")?>
									name="answer_choices[3.1.3]" /><label for="1">ด้านการโต้ตอบเหตุฉุกเฉินกรณีเกิดอุบัติเหตุจากรังสี

								</label> <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input
									type="checkbox" value="1" id="3.1.4"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "3.1.4", "1")?>
									name="answer_choices[3.1.4]" /><label for="1">ด้านการกำจัดกากกัมมันตรังสี
								</label> <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input
									type="checkbox" value="1" id="3.1.5"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "3.1.5", "1")?>
									name="answer_choices[3.1.5]" /><label for="1">ด้านการกำกับดูแล
									ติดตามและตรวจสอบการใช้รังสีให้เป็นไปตามที่กำหมายกำหนด </label>
								<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox"
									value="1" id="3.1.6"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "3.1.6", "1")?>
									name="answer_choices[3.1.6]" /><label for="1">อื่น ๆ โปรดระบุ<input
									type="text" name="answer_texts[3.1.6]"
									value=" <?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::TEXT_TYPE, "3.1.6", "") ?>"
									style="border: none; border-bottom-style: dotted;" />
								</label>
							</div>
							<div class="choice">
								<input type="radio" value="0" id="2" name="answer_choices[3]"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "3", "0") ?> /><label
									for="2">ไม่มีแผน &nbsp;&nbsp;</label><input type="text"
									name="answer_texts[]"
									value="<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::TEXT_TYPE, "3", "0") ?>"
									placeholder="คาดว่าจะดำเนินการเสร็จสิ้นภายใน"
									style="border: none; border-bottom-style: dotted; width: 250px;" />
							</div>
						</div>
					</div>
					<div class="poll">
						<div class="num">4</div>
						<h2>ส่วนงานมีการจัดทำเอกสารด้านความปลอดภัยในการใช้รังสีหรือไม่?</h2>
						<div class="choices">
							<div class="choice">

								<input type="radio" value="1" id="4.1" name="answer_choices[4]"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "4", "1") ?> /><label
									for="1">มี</label> <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input
									type="checkbox" value="1" id="4.1.1"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "4.1.1", "1")?>
									name="answer_choices[4.1.1]" /><label for="1">คู่มือ </label> <label>
									<input name="answer_files[]" type="file" style="display: none;"
									onchange='getFilename(411,this)' /><a> &nbsp;&nbsp;<i
										class="fa fa-paperclip"></i>
								</a> 
								<input id="txt_updesc_411" placeholder="" disabled="disabled"
									style="border: none" value="<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "4.1.1", "")?>" />
<?php if(QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "4.1.1", "") != ''){?>
<a title="Download" class="fa fa-download"
									href="<?php echo ConfigUtil::getAppName().''. QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "4.1.1", "")?>">&nbsp;</a>
									<input type="hidden" name="tmpPath[4.1.1]" value="<?php echo ConfigUtil::getAppName().''. QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "4.1.1", "")?>">
<?php }?>



								</label> <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input
									type="checkbox" value="1" id="4.1.2"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "4.1.2", "1")?>
									name="answer_choices[4.1.2]" /> <label for="1">แนวปฏิบัติ </label>
								<label> <input name="answer_files[]" type="file"
									style="display: none;" onchange='getFilename(412,this)' /><a>
										&nbsp;&nbsp;<i class="fa fa-paperclip"></i>
								</a></label>
								
						<input id="txt_updesc_412" placeholder="" disabled="disabled"
									style="border: none" value="<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "4.1.2", "")?>" />
																			
<?php if(QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "4.1.2", "") != ''){?>
<a title="Download" class="fa fa-download"
									href="<?php echo ConfigUtil::getAppName().''. QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "4.1.2", "")?>">&nbsp;</a>
									<input type="hidden" name="tmpPath[4.1.2]" value="<?php echo ConfigUtil::getAppName().''. QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "4.1.2", "")?>">
<?php }?>

									
									<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox"
									value="1" id="4.1.3"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "4.1.3", "1")?>
									name="answer_choices[4.1.3]" /><label for="1">กฎ/ระเบียบ/ข้อบังคับ
								</label> <label> <input name="answer_files[]" type="file"
									style="display: none;" onchange='getFilename(413,this)' /><a>
										&nbsp;&nbsp;<i class="fa fa-paperclip"></i>
								</a></label> 
								
								
				<input id="txt_updesc_413" placeholder="" disabled="disabled"
									style="border: none" value="<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "4.1.3", "")?>" />
																					
<?php if(QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "4.1.3", "") != ''){?>
<a title="Download" class="fa fa-download"
									href="<?php echo ConfigUtil::getAppName().''. QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "4.1.3", "")?>">&nbsp;</a>
									<input type="hidden" name="tmpPath[4.1.3]" value="<?php echo ConfigUtil::getAppName().''. QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "4.1.3", "")?>">
<?php }?>



									
									<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox"
									value="1" id="4.1.4"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "4.1.4", "1")?>
									name="answer_choices[4.1.4]" /><label for="1">อื่น ๆ โปรดระบุ<input
									type="text" name="answer_texts[4.1.4]"
									value="<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::TEXT_TYPE, "4.1.4", "") ?>"
									style="border: none; border-bottom-style: dotted;" />
								</label>
							</div>
							<div class="choice">
								<input type="radio" value="0" name="answer_choices[4]" id="4"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "4", "0") ?> /><label
									for="2">ไม่มี &nbsp;&nbsp;</label><input type="text"
									name="answer_texts[4]"
									value="<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::TEXT_TYPE, "4", "") ?>"
									placeholder="คาดว่าจะดำเนินการเสร็จสิ้นภายใน"
									style="border: none; border-bottom-style: dotted; width: 250px;" />
							</div>
						</div>
					</div>
					<div class="poll">
						<div class="num">5</div>
						<h2>มีการตรวจสอบคุณภาพและขออนุญาตมีไว้ในครอบครอง
							นำเข้าหรือส่งออกเครื่องกำเนิดรังสีหรือวัสดุกัมมันตรังสี
							ตามมาตรฐานและกฎหมายกำหนด</h2>
						<div class="choices">
							<div class="choice">

								<input type="radio" value="1" id="51" name="answer_choices[5]"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "5", "1") ?> /><label
									for="1">มี </label>

							</div>
							<div class="choice">
								<input type="radio" value="0" name="answer_choices[5]" id="52"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "5", "0") ?> /><label
									for="2">ไม่มี &nbsp;&nbsp;</label><input type="text"
									name="answer_texts[5]"
									value="<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::TEXT_TYPE, "5", "") ?>"
									placeholder="คาดว่าจะดำเนินการเสร็จสิ้นภายใน"
									style="border: none; border-bottom-style: dotted; width: 250px;" />
							</div>
						</div>
					</div>
					<div class="poll">
						<div class="num">6</div>
						<h2>มีการขึ้นทะเบียนเครื่องกำเนิดรังสีและวัสดุกัมมันตรังสี</h2>
						<div class="choices">
							<div class="choice">

								<input type="radio" value="1" id="6.1" name="answer_choices[6]"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "6", "1") ?> /><label
									for="1">มี</label> <label> <input name="answer_files[]"
									type="file" style="display: none;"
									onchange='getFilename(6,this)' /><a> &nbsp;&nbsp;<i
										class="fa fa-paperclip"></i></a></label> 
										
<input id="txt_updesc_6" placeholder="" disabled="disabled"
									style="border: none" value="<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "6", "")?>" />
									
<?php if(QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "6", "") != ''){?>
<a title="Download" class="fa fa-download"
									href="<?php echo ConfigUtil::getAppName().''. QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "6", "")?>">&nbsp;</a>
									<input type="hidden" name="tmpPath[6]" value="<?php echo ConfigUtil::getAppName().''. QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "6", "")?>">
<?php }?>

										

							</div>
							<div class="choice">
								<input type="radio" value="0" name="answer_choices[6]" id="6.2"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "6", "0") ?> /><label
									for="2">ไม่มี &nbsp;&nbsp;</label><input type="text"
									name="answer_texts[6]"
									value="<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::TEXT_TYPE, "6", "") ?>"
									placeholder="คาดว่าจะดำเนินการเสร็จสิ้นภายใน"
									style="border: none; border-bottom-style: dotted; width: 250px;" />
							</div>
						</div>
					</div>
					<div class="poll">
						<div class="num">7</div>
						<h2>มีการตรวจสอบประเมินคุณภาพจากหน่วยงานที่ได้รับการรับรองมาตรฐานตามข้อกำหนดของสำนักงานปรมาณูเพื่อสันติ?</h2>
						<div class="choices">
							<div class="choice">

								<input type="radio" value="1" id="7.1" name="answer_choices[7]"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "7", "1") ?> />
								<label for="1">มี จากหน่วยงาน</label> <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="checkbox" value="1" id="7.1.1"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "7.1.1", "1")?>
									name="answer_choices[7.1.1]" /><label for="1">กรมวิทยาศาสตร์การแพทย์
								</label> <label> <input name="answer_files[]" type="file"
									style="display: none;" onchange='getFilename(711,this)' /><a>
										&nbsp;&nbsp;<i class="fa fa-paperclip"></i>
								</a></label> 
	<input id="txt_updesc_711" placeholder="" disabled="disabled"
									style="border: none" value="<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "7.1.1", "")?>" />
																
<?php if(QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "7.1.1", "") != ''){?>
<a title="Download" class="fa fa-download"
									href="<?php echo ConfigUtil::getAppName().''. QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "7.1.1", "")?>">&nbsp;</a>
									<input type="hidden" name="tmpPath[7.1.1]" value="<?php echo ConfigUtil::getAppName().''. QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "7.1.1", "")?>">
<?php }?>


									
									<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox"
									value="1" id="7.1.2"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "7.1.2", "1")?>
									name="answer_choices[7.1.2]" /> <label for="1">อื่น ๆ โปรดระบุ
									<input type="text" name="answer_texts[7.1.2]"
									value="<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::TEXT_TYPE, "7.1.2", "") ?>"
									style="border: none; border-bottom-style: dotted;" />

								</label> <label> <input name="answer_files[]" type="file"
									style="display: none;" onchange='getFilename(712,this)' /><a>
										&nbsp;&nbsp;<i class="fa fa-paperclip"></i>
								</a></label> 
								
								<input id="txt_updesc_712" placeholder="" disabled="disabled"
									style="border: none" value="<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "7.1.2", "")?>" />
									
									
<?php if(QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "7.1.2", "") != ''){?>
<a title="Download" class="fa fa-download"
									href="<?php echo ConfigUtil::getAppName().''. QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "7.1.2", "")?>">&nbsp;</a>
									<input type="hidden" name="tmpPath[7.1.2]" value="<?php echo ConfigUtil::getAppName().''. QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "7.1.2", "")?>">
<?php }?>



							</div>
							<div class="choice">
								<input type="radio" value="0" name="answer_choices[7]" id="7.2"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "7", "0") ?> /><label
									for="2">ไม่มี &nbsp;&nbsp;</label><input type="text"
									name="answer_texts[7.2]"
									value="<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::TEXT_TYPE, "7.2", "") ?>"
									placeholder="คาดว่าจะดำเนินการเสร็จสิ้นภายใน"
									style="border: none; border-bottom-style: dotted; width: 250px;" />
							</div>
						</div>
					</div>
					<div class="poll">
						<div class="num">8</div>
						<h2>มีการจัดหาวัสดุที่สามารถป้องกันรังสีได้อย่างปลอดภัย
							และไม่เป็นอันตรายต่อบุคลากรและบุคคลภายนอก (เช่น เสื้อตะกั่ว
							แว่นตากันรังสี ฉากกำบังรังสี)</h2>
						<div class="choices">
							<div class="choice">

								<input type="radio" value="1" id="8.1" name="answer_choices[8]"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "8", "1") ?> /><label
									for="1">มี </label>

							</div>
							<div class="choice">
								<input type="radio" value="0" name="answer_choices[8]" id="8.2"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "8", "0") ?> /><label
									for="2">ไม่มี &nbsp;&nbsp;</label><input type="text"
									name="answer_texts[8.2]"
									value="<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::TEXT_TYPE, "8.2", "") ?>"
									placeholder="คาดว่าจะดำเนินการเสร็จสิ้นภายใน"
									style="border: none; border-bottom-style: dotted; width: 250px;" />
							</div>
						</div>
					</div>
					<div class="poll">
						<div class="num">9</div>
						<h2>มีการควบคุมการใช้ปริมาณรังสี ตามหลัก As Low As Reasonably
							Achievable (ALARA) และกำกับ
							ดูแลให้ผลการตรวจวัดรังสีส่วนบุคคลไม่เกินเกณฑ์ที่กำหนด</h2>
						<div class="choices">
							<div class="choice">

								<input type="radio" value="1" id="9.1" name="answer_choices[9]"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "9", "1") ?> /><label
									for="1">มี (โปรดแนบเอกสารแนวทางหรือวิธีการควบคุม) </label> <label>
									<input name="answer_files[]" type="file" style="display: none;"
									onchange='getFilename(9,this)' /><a> &nbsp;&nbsp;<i
										class="fa fa-paperclip"></i>
								</a>
								</label> 
								<input id="txt_updesc_9" placeholder="" disabled="disabled"
									style="border: none" value="<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "9", "")?>" />
<?php if(QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "9", "") != ''){?>
<a title="Download" class="fa fa-download"
									href="<?php echo ConfigUtil::getAppName().''. QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "9", "")?>">&nbsp;</a>
									<input type="hidden" name="tmpPath[9]" value="<?php echo ConfigUtil::getAppName().''. QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "9", "")?>">
<?php }?>
								

							</div>
							<div class="choice">
								<input type="radio" value="0" name="answer_choices[9]" id="9.2"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "9", "0") ?> /><label
									for="2">ไม่มี </label>
							</div>
						</div>
					</div>

					<div class="poll">
						<div class="num">10</div>
						<h2>มีการจัดหาอุปกรณ์วัดรังสีประจำตัวบุคคลให้กับบุคลากรที่ปฏิบัติงานเกี่ยวกับรังสี</h2>
						<div class="choices">
							<div class="choice">
								<input type="radio" value="1" name="answer_choices[10]"
									id="10.1"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "10", "1") ?> /><label
									for="2">มี &nbsp;&nbsp;</label><input type="text"
									name="answer_texts[10.1]"
									value="<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::TEXT_TYPE, "10.1", "") ?>"
									placeholder="โปรดระบุชนิดอุปกรณ์"
									style="border: none; border-bottom-style: dotted; width: 250px;" />
							</div>
							<div class="choice">
								<input type="radio" value="0" name="answer_choices[10]"
									id="10.2"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "10", "0") ?> /><label
									for="2">ไม่มี </label>
							</div>
						</div>
					</div>
					<div class="poll">
						<div class="num">11</div>
						<h2>มีการอบรมความปลอดภัยในการใช้รังสีให้กับบุคลากรก่อนปฏิบัติงานครั้งแรก</h2>
						<div class="choices">
							<div class="choice">

								<input type="radio" value="1" id="11.1"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "11", "1")?>
									name="answer_choices[11]" /><label for="1">มี </label>
							</div>
							<div class="choice">
								<input type="radio" value="0" name="answer_choices[11]"
									id="11.2"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "11", "0") ?> /><label
									for="2">ไม่มี </label>
							</div>
						</div>
					</div>
					<div class="poll">
						<div class="num">12</div>
						<h2>
							มีการอบรมทบทวนความรู้ด้านความปลอดภัยในการใช้รังสีให้กับบุคลากรอย่างน้อยปีละ
							1 ครั้ง</h2>
						<div class="choices">
							<div class="choice">

								<input type="radio" value="1" id="12.1"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "12", "1")?>
									name="answer_choices[12]" /><label for="1">มี </label>
							</div>
							<div class="choice">
								<input type="radio" value="0" name="answer_choices[12]"
									id="12.2"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "12", "0") ?> /><label
									for="2">ไม่มี </label>
							</div>
						</div>
					</div>

					<div class="poll">
						<div class="num">13</div>
						<h2>มีระบบแจ้งเหตุ หรือการรายงานเกิดอุบัติเหตุทางรังสี</h2>
						<div class="choices">
							<div class="choice">

								<input type="radio" value="1" id="13.1"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "13", "1")?>
									name="answer_choices[13]" /><label for="1">มี ผ่านระบบ</label>
								<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox"
									value="1" id="13.1" name="answer_choices[13.1]"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "13.1", "1") ?> /><label
									for="1">เอกสาร </label> <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input
									type="checkbox" value="1" id="13.1" name="answer_choices[13.2]"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "13.2", "1") ?> /><label
									for="1">อื่น ๆ โปรดระบุ<input type="text"
									name="answer_texts[13.2]"
									value="<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::TEXT_TYPE, "13.2", "") ?>"
									style="border: none; border-bottom-style: dotted;" />
								</label>
							</div>
							<div class="choice">
								<input type="radio" value="0" name="answer_choices[13]"
									id="13.2"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "13", "0") ?> /><label
									for="2">ไม่มี </label>
							</div>
						</div>
					</div>
					<div class="poll">
						<div class="num">14</div>
						<h2>มีการตรวจสอบอุบัติเหตุทางรังสี</h2>
						<div class="choices">
							<div class="choice">

								<input type="radio" value="1" id="14.1"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "14", "1")?>
									name="answer_choices[14]" /><label for="1">มี </label>
							</div>
							<div class="choice">
								<input type="radio" value="0" name="answer_choices[14]"
									id="14.2"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "14", "0") ?> /><label
									for="2">ไม่มี </label>
							</div>
						</div>
					</div>
					<div class="poll">
						<div class="num">15</div>
						<h2>
							การเยี่ยมสำรวจความปลอดภัยทางรังสี ปี <input type="text"
								name="answer_texts[15]"
								value="<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::TEXT_TYPE, "15", "") ?>"
								style="border: none; border-bottom-style: dotted;" />
						</h2>

						<div class="choices">
							<div class="choice">

								<input type="radio" value="1" id="15.1"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "15", "1")?>
									name="answer_choices[15]" /><label for="1">เข้าร่วม</label> <br>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label for="1">-
									ผลการเยี่ยมตรวจ</label><label> <input name="answer_files[]"
									type="file" style="display: none;"
									onchange='getFilename(151,this)' /><a> &nbsp;&nbsp;<i
										class="fa fa-paperclip"></i></a></label> 
										
<input id="txt_updesc_151" placeholder="" disabled="disabled"
									style="border: none" value="<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "15.1", "")?>" />
									
										<?php if(QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "15.1", "") != ''){?>
<a title="Download" class="fa fa-download"
									href="<?php echo ConfigUtil::getAppName().''. QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "15.1", "")?>">&nbsp;</a>
									<input type="hidden" name="tmpPath[15.1]" value="<?php echo ConfigUtil::getAppName().''. QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "15.1", "")?>">
<?php }?>
								
									
									
									<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label for="1">-
									ภายหลังการเยี่ยมตรวจ</label><label> <input
									name="answer_files[]" type="file" style="display: none;"
									onchange='getFilename(152,this)' /><a> &nbsp;&nbsp;<i
										class="fa fa-paperclip"></i>
								</a></label> 
								
								<input id="txt_updesc_152" placeholder="" disabled="disabled"
									style="border: none" value="<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "15.2", "")?>" />
									
<?php if(QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "15.2", "") != ''){?>
<a title="Download" class="fa fa-download"
									href="<?php echo ConfigUtil::getAppName().''. QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "15.2", "")?>">&nbsp;</a>
									<input type="hidden" name="tmpPath[15.2]" value="<?php echo ConfigUtil::getAppName().''. QuestionUtil::getAnswerValue($answers, CommonUtil::FILE_TYPE, "15.2", "")?>">
<?php }else{?>

<?php }?>
								


							</div>
							<div class="choice">
								<input type="radio" value="0" name="answer_choices[15]"
									<?php echo QuestionUtil::getAnswerValue($answers, CommonUtil::CHECKBOX_TYPE, "15", "0") ?>
									id="15.2" /><label for="2">ไม่เข้าร่วม</label>
							</div>
						</div>
						<div class="form-actions">
							<button type="submit" class="btn blue"><?php echo ConfigUtil::getBtnSaveButton();?></button>
							<button type="button" class="btn default"><?php echo ConfigUtil::getBtnCloseName();?></button>
						</div>



					</div>

				</div>
			</div>
		</div>
	</div>


	<script
		src="<?php echo ConfigUtil::getAppName();?>/assets/global/plugins/jquery.min.js"
		type="text/javascript"></script>

<script>
jQuery(document).ready(function () {
	
});
</script>
</form>