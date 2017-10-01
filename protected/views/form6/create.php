<?php
$criteria = new CDbCriteria ();
$criteria->condition = " branch_group_id like '%" . UserLoginUtils::getBranchId() . "%'";

$units = MUnit::model ()->findAll ( $criteria );
$materialTypes = MaterialType::model ()->findAll ( $criteria );
$position = MPosition::model ()->findAll ();
?>
<form id="Form1" method="post" enctype="multipart/form-data"
	class="form-horizontal">

	<div class="portlet box blue">
		<div class="portlet-title">
			<div class="caption">
				<?php echo  MenuUtil::getMenuName($_SERVER['REQUEST_URI'])?>

			</div>
			<div class="actions">
			<?php echo CHtml::link('ย้อนกลับ',array('Form6/'),array('class'=>'btn btn-default btn-sm'));?>
			</div>
		</div>
		<div class="portlet-body form">
			<div class="form-body">

				<div id="responsive" class="modal fade" tabindex="-1"
					data-width="760">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"
							aria-hidden="true"></button>
						<h4 class="modal-title">
							<i class="fa fa-flask"></i> รายละเอียดวัสดุกัมมันตรังสี
						</h4>
					</div>
					<div class="modal-body">

						<table class="table table-striped table-hover table-bordered"
							id="gvResult">
							<thead>
								<tr>
									<th class="no-sort"></th>
						<th>ลำดับ</th>
						<th>ชนิด</th>
						<th>ธาตุ-เลขมวล</th>
						<th>หมายเลขวัสดุ (SN)</th>
						<th>สถานภาพวัสดุ</th>
						<th>สมบัติทางกายภาพ</th>
						<th>กัมมันตภาพหรือน้ำหนัก</th>
						<th>ปริมาณ</th>
						<th>สถานที่จัดเก็บ</th>
								</tr>
							</thead>
							<tbody>
	<?php
	$criF2 = new CDbCriteria ();
	$criF2->condition = " status ='T' and owner_department_id = " . UserLoginUtils::getDepartmentId ();
	$modalForm2 = Form2::model ()->findAll ($criF2);
	$counter = 1;
	foreach ( $modalForm2 as $modalValue ) {
		?>
				<tr>
									<td class="center"><a href="#"
										onclick="return selectedValue('<?php echo $modalValue->id.','.$modalValue->bpm_radioactive_elements->name.' ('.$modalValue->bpm_no .'),'. $modalValue->type.','.$modalValue->bpm_volume,','.$modalValue->bpm_volume_unit_id; ?>')"><i
											class="fa fa-check-square-o"></i></a></td>
									<td class="center"><?php echo  $counter;?></td>
									<td class="center"><?php echo $modalValue->type == 1 ? "ปิดผนึก":"ไม่ปิดผนึก"?></td>
									<td class="center"><?php echo (isset($modalValue->bpm_radioactive_elements->name)? $modalValue->bpm_radioactive_elements->name:'')?></td>
									<td class="center"><?php echo $modalValue->bpm_no?></td>
									<td class="center"><?php echo (isset($modalValue->materialStatus->name)? $modalValue->materialStatus->name:'')?></td>
									<td class="center"><?php echo (isset($modalValue->phisicalStatus->name)? $modalValue->phisicalStatus->name:'')?></td>
									<td class="center"><?php echo $modalValue->bpm_volume.' '.(isset($modalValue->unit->name)? $modalValue->unit->name:'')?></td>
									<td class="center"><?php echo $modalValue->bpm_number?></td>
									<td class="center"><?php echo 'ชื่อห้อง'.$modalValue->room->name. (CommonUtil::IsNullOrEmptyString($modalValue->room->number)? '':' เลขห้อง '.$modalValue->room->number.'').' ชั้น'. $modalValue->room->floor.' อาคาร'.$modalValue->room->building_id.' คณะ'.$modalValue->room->fac;?></td>

								</tr>
			<?php
		$counter ++;
	}
	?>
					
						</tbody>
						</table>

					</div>
					<div class="modal-footer">
						<button type="button" data-dismiss="modal"
							class="btn btn-outline dark">Close</button>
					</div>
				</div>



				<!-- BEGIN FORM-->
				<div class="panel-group accordion" id="accordion1">

					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a class="accordion-toggle" data-toggle="collapse"
									data-parent="#accordion1" href="#collapse_1"> <i
									class="fa fa-flask"></i> รายละเอียดวัสดุกัมมันตรังสี
								</a>
							</h4>
						</div>
						<div id="collapse_1" class="panel-collapse in">
							<br>
							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">ชื่อวัสดุกัมมันตรังสี (ธาตุ-เลขมวล):<span
											class="required">*</span></label>
										<div class="col-md-6">
											<input id="form2_name" type="text" value=""
												name="Form6[form2_name]" class="form-control"
												disabled="disabled"> <input id="form2_id" type="hidden"
												value="" name="Form6[form2_id]" class="form-control">
										</div>

										<div>
											<a class="btn btn-outline dark" data-toggle="modal"
												href="#responsive"> ค้นหา </a>
										</div>
										<div id="divReq-form2_id">


											<!-- 											* <span> เมื่อเลือก ระบบจะต้องดึงตำแหน่งเดิมมาให้อัตโนมัติ</span> -->
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">ประเภทวัสดุกัมมันตรังสี<span
											class="required">*</span></label>
										<div class="col-md-6">
											<select class="form-control select2"
												name="Form6[material_type_id]" id="material_type_id">
												<option value="0">-- โปรดเลือก --</option>
	<?php foreach($materialTypes as $item) {?>
<option value="<?php echo $item->id?>"><?php echo $item->name.' '.$item->description?></option>
<?php }?>
								</select>
										</div>
										<div id="divReq-material_type_id"></div>
									</div>
								</div>
							</div>
														<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4"><span
											class="required"></span></label>
										<div class="col-md-5">
											<a
											href="<?php echo  ConfigUtil::getAppName().'/docs/file01.pdf'?>"
											target="_blank"> ดาวโหลดไฟล์ (มาตรฐานความปลอดภัยเกี่ยวกับรังสี)</a>
										</div>

										<div class="col-md-4"></div>
									</div>
								</div>
							</div>
						</div>

					</div>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a class="accordion-toggle" data-toggle="collapse"
									data-parent="#accordion1" href="#collapse_2"> <i
									class="fa fa-balance-scale"></i> ปริมาณกัมมันตภาพหรือน้ำหนัก
								</a>
							</h4>
						</div>
						<div id="collapse_2" class="panel-collapse in">
							<br>
							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">ก่อนการรั่วไหล:<span
											class="required">*</span></label>
										<div class="col-md-4">
											<input id="previous_leaks" type="text" value=""
												class="grpOfDouble form-control" placeholder=""
												name="Form6[previous_leaks]">
										</div>
										<div class="col-md-4">
											<select class="form-control select2"
												name="Form6[previous_leaks_unit_id]"
												id="previous_leaks_unit_id">
												<option value="0">-- โปรดเลือก --</option>
<?php foreach($units as $item) {?>
<option value="<?php echo $item->id?>"><?php echo $item->name?></option>
<?php }?>
								</select>
										</div>
										<div id="divReq-previous_leaks"></div>
										<div id="divReq-previous_leaks_unit_id"></div>
									</div>
								</div>
							</div>


							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">หลังการรั่วไหล:<span
											class="required">*</span></label>
										<div class="col-md-4">
											<input id="after_leaks" type="text" value=""
												class="grpOfDouble form-control" placeholder=""
												name="Form6[after_leaks]">
										</div>
										<div class="col-md-4">
											<select class="form-control select2"
												name="Form6[after_leaks_unit_id]" id="after_leaks_unit_id">
												<option value="0">-- โปรดเลือก --</option>
<?php foreach($units as $item) {?>
<option value="<?php echo $item->id?>"><?php echo $item->name?></option>
<?php }?>
								</select>
										</div>
										<div id="divReq-after_leaks_unit_id"></div>
										<div id="divReq-after_leaks"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a class="accordion-toggle" data-toggle="collapse"
									data-parent="#accordion1" href="#collapse_3"> <i
									class="fa fa-ambulance"></i> รายละเอียด
								</a>
							</h4>
						</div>
						<div id="collapse_3" class="panel-collapse in">
							<br>
							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">ประเภท :<span
											class="required">*</span>
										</label>
										<div class="col-md-4">
											<select class="form-control select2"
												name="Form6[accident_type_id]" id="accident_type_id">
												<option value="0">-- โปรดเลือก --</option>

												<option value="1">อุบัติการณ์ (Incident)</option>
												<option value="2">อุบัติเหตุ (Accident)</option>
											</select>
										</div>
										<div id="divReq-accident_type_id"></div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4"> วัน/เดือน/ปี ที่เกิด:<span
											class="required">*</span>
										</label>
										<div class="col-md-4">
											<input type="text" value="" id="accident_date"
												name="Form6[accident_date]" />




										</div>
										<div id="divReq-accident_date"></div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">สถานที่ :<span
											class="required">*</span>
										</label>
										<div class="col-md-4">
											<input type="text" value="" id="accident_room_id_text"
												class="form-control" name="Form6[accident_room_id_text]" />

										</div>
										<div id="divReq-accident_room_id_text"></div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">สถานการณ์: <!-- 							<span class="required">*</span> -->
										</label>
										<div class="col-md-4">
											<textarea rows="5" cols="70" id="accident_situation"
												name="Form6[accident_situation]"></textarea>

										</div>
										<div id="divReq-accident_situation"></div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">สาเหตุที่ทำให้เกิด: <!-- 							<span class="required">*</span> -->
										</label>
										<div class="col-md-4">
											<textarea rows="5" cols="70" id="accident_cause"
												name="Form6[accident_cause]"></textarea>

										</div>
										<div id="divReq-accident_cause"></div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">การบริหารจัดการหลังจากเกิดเหตุ:
											<!-- 							<span class="required">*</span> -->
										</label>
										<div class="col-md-4">
											<textarea rows="5" cols="70" id="accident_management"
												name="Form6[accident_management]"></textarea>

										</div>
										<div id="divReq-accident_management"></div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">จำนวนผู้ได้รับอันตราย: <!-- 							<span class="required">*</span> -->
										</label>
										<div class="col-md-4">
											<input id="accident_count" type="text" value=""
												class="form-control" name="Form6[accident_count]">
										</div>
										<div id="divReq-accident_count"></div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">ประมาณการค่าเสียหาย(บาท):
											<!-- 							<span class="required">*</span> -->
										</label>
										<div class="col-md-4">
											<input id="accident_estimated_loss" type="text" value=""
												class="grpOfInt form-control" placeholder=""
												name="Form6[accident_estimated_loss]">
										</div>
										<div id="divReq-accident_estimated_loss"></div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">แนวทางป้องกันในอนาคต: <!-- 							<span class="required">*</span> -->
										</label>
										<div class="col-md-4">
											<textarea rows="5" cols="70" id="accident_Prevention"
												name="Form6[accident_Prevention]"></textarea>


											<!-- 								<input id="accident_Prevention" type="text" value="" -->
											<!-- 									class="form-control" name="Form6[accident_Prevention]"> -->
										</div>
										<div id="divReq-accident_Prevention"></div>
									</div>
								</div>
							</div>

						</div>
					</div>




					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a class="accordion-toggle" data-toggle="collapse"
									data-parent="#accordion1" href="#collapse_3"> <i
									class="fa fa-user"></i> ผู้ดำเนินการ
								</a>
							</h4>
						</div>
						<div id="collapse_3" class="panel-collapse in">
							<br>

							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">ชื่อ-นามสกุล :<span
											class="required"></span>
										</label>
										<div class="col-md-6">
											<input id="management_name" type="text" value=""
												name="Form6[management_name]" class="form-control">
										</div>
										<div id="divReq-management_name"></div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">ตำแหน่ง:<span
											class="required">*</span></label>
										<div class="col-md-6">

											<select class="form-control select2"
												name="Form6[management_position_id]"
												id="management_position_id">
												<option value="0">-- โปรดเลือก --</option>			<?php foreach($position as $item) {?>
			<option value="<?php echo $item->id?>"><?php echo $item->name?></option>
			<?php }?>
									</select>
										</div>
										<div id="divReq-management_position_id"></div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">เลขที่ใบอนุญาต RSO :<span
											class="required"></span>
										</label>

										<div class="col-md-6">
											<input id="management_rso_license" type="text" value=""
												name="Form6[management_rso_license]" placeholder=""
												class="form-control">
										</div>
										<div id="divReq-management_rso_license"></div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">หมายเลขโทรศัพท์ :<span
											class="required"></span>
										</label>

										<div class="col-md-6">
											<input id="management_phone" type="text" value=""
												name="Form6[management_phone]" placeholder=""
												class="form-control">
										</div>
										<div id="divReq-management_phone"></div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">อีเมล์:<span
											class="required"></span>
										</label>
										<div class="col-md-6">
											<input id="management_email" type="text" value=""
												name="Form6[management_email]" placeholder=""
												class="form-control">
										</div>
										<div id="divReq-management_name"></div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">การรายงานต่อผู้บังคับบัญชา
											<span class="required">*</span>
										</label>
										<div class="col-md-6">
											<select class="form-control select2"
												name="Form6[isReportCommander]" id="isReportCommander">
												<option value="0">-- โปรดเลือก --</option>
												<option value="1">มีการรายงาน</option>
												<option value="2">ไม่มีการรายงาน</option>

											</select>
										</div>
										<div id="divReq-isReportCommander"></div>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>

			</div>

			<div class="form-actions">
				<div class="row">
					<div class="col-md-10">
						<div class="row">
							<div class="col-md-offset-3 col-md-10">
								<button type="submit" class="btn green uppercase"><?php echo ConfigUtil::getBtnSaveButton();?></button>
								<?php echo CHtml::link(ConfigUtil::getBtnCancelButton(),array('Form6/'),array('class'=>'btn btn-default uppercase'));?>
							</div>
						</div>
					</div>
					<div class="col-md-10"></div>
				</div>
			</div>
		</div>
	</div>



	<script
		src="<?php echo ConfigUtil::getAppName();?>/assets/global/plugins/jquery.min.js"
		type="text/javascript"></script>

	<script>
	var host = 'http://localhost:81/mu_rad';

	   function selectedValue($vals){

		   var $arrDatas = $vals.split(",");
		   $('#form2_id').val($arrDatas[0]);
		   $('#form2_name').val($arrDatas[1]);
		   $('#responsive').modal('hide');

		   $('#previous_leaks').val($arrDatas[3]);
		   $("#previous_leaks_unit_id").val($arrDatas[4]).change();
		   $("#after_leaks_unit_id").val($arrDatas[4]).change();
		   

		   
	   }
    jQuery(document).ready(function () {

    	var table = $('#gvResult');

    	var oTable = table.dataTable({

    	    // Internationalisation. For more info refer to http://datatables.net/manual/i18n
    	    "language": {
    	        "aria": {
    	            "sortAscending": ": activate to sort column ascending",
    	            "sortDescending": ": activate to sort column descending"
    	        },
    	        "emptyTable": "ไม่พบข้อมูล",
    	        "info": "แสดง  _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ",
    	        "infoEmpty": "No entries found",
    	        "infoFiltered": "(filtered1 from _MAX_ total entries)",
    	        "lengthMenu": "แสดงข้อมูล  _MENU_ รายการ",
    	        "search": "ใส่คำที่ต้องการค้นหา:",
    	        "zeroRecords": "ไม่พบรายการที่ค้นหา"
    	    },


    	    // setup responsive extension: http://datatables.net/extensions/responsive/
    	    responsive: true,

    	    //"ordering": false, disable column ordering 
    	    //"paging": false, disable pagination

    	    "order": [
    	        [0, 'asc']
    	    ],
    	    
    	    "lengthMenu": [
    	        [5, 10, 15, 20, -1],
    	        [5, 10, 15, 20, "ทั้งหมด"] // change per page values here
    	    ],
    	    // set the initial value
    	    "pageLength": 10 ,
    	    "columnDefs": [ {
    	        "targets": 'no-sort',
    	        "orderable": false,
    	  } ]
    		});

        
	    $('.grpOfInt').keypress(function (event) {
            return isNumber(event);
        });
	    $('.grpOfDouble').keypress(function (event) {
            return isDouble(event,this);
        });
        
//     	$("#ref_doc").attr('maxlength','20');
//     	$("#bpm_model").attr('maxlength','20');
//     	$("#bpm_volume").attr('maxlength','10');
//     	$("#bpm_number").attr('maxlength','10');
//     	$("#machine_model").attr('maxlength','20');
//     	$("#machine_number").attr('maxlength','20');
//     	$("#machine_radioactive_highest").attr('maxlength','10');
    	$("#previous_leaks").attr('maxlength','10');
    	$("#after_leaks").attr('maxlength','10');
    	$("#accident_situation").attr('maxlength','200');
    	$("#accident_cause").attr('maxlength','200');
    	$("#accident_count").attr('maxlength','10');
    	$("#accident_estimated_loss").attr('maxlength','10');
    	$("#accident_Prevention").attr('maxlength','200');
//     	$("#document_path").attr('maxlength','100');

		 $.datepicker.regional['th'] ={
			        changeMonth: true,
			        changeYear: true,
			        //defaultDate: GetFxupdateDate(FxRateDateAndUpdate.d[0].Day),
			        yearOffSet: 543,
			        showOn: "button",
			        buttonImage: '/images/calendar.gif',
			        buttonImageOnly: true,
			        dateFormat: 'dd/mm/yy',
			        dayNames: ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'],
			        dayNamesMin: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'],
			        monthNames: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
			        monthNamesShort: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'],
			        constrainInput: true,
			       
			        prevText: 'ก่อนหน้า',
			        nextText: 'ถัดไป',
			        yearRange: '-20:+20',
			        buttonText: 'เลือก',
			      
			    };
		    
			$.datepicker.setDefaults($.datepicker.regional['th']);
			
// 	    $( "#bpm_as_of_date" ).datepicker( $.datepicker.regional["th"] ); // Set ภาษาที่เรานิยามไว้ด้านบน
// 	    $( "#bpm_as_of_date" ).datepicker("setDate", new Date()); //Set ค่าวันปัจจุบัน
	    
	    $( "#accident_date" ).datepicker( $.datepicker.regional["th"] ); // Set ภาษาที่เรานิยามไว้ด้านบน
	    $( "#accident_date" ).datepicker("setDate", new Date()); //Set ค่าวันปัจจุบัน

	    

    	$( "#Form1" ).submit(function( event ) {

     		//Validate date format
//     	   	if(!moment($("#bpm_as_of_date").val(), 'DD/MM/YYYY',true).isValid()){
//         		$("#bpm_as_of_date").closest('.form-group').addClass('has-error');
//         		$("#divReq-bpm_as_of_date").html("<span id=\"id-error\" class=\"help-block help-block-error\">รูปแบบวันที่ผิด จะต้องอยู่ในรูปแบบ dd/mm/yyyy เช่น 18/02/2526.</span>");
//         		$("#bpm_as_of_date").focus();
//         		return false;
//             }else{
//             	$("#divReq-bpm_as_of_date").html('');
//             	$("#bpm_as_of_date").closest('.form-group').removeClass('has-error');
//         	}

    	   	if(!moment($("#accident_date").val(), 'DD/MM/YYYY',true).isValid()){
        		$("#accident_date").closest('.form-group').addClass('has-error');
        		$("#divReq-accident_date").html("<span id=\"id-error\" class=\"help-block help-block-error\">รูปแบบวันที่ผิด จะต้องอยู่ในรูปแบบ dd/mm/yyyy เช่น 18/02/2526.</span>");
        		$("#accident_date").focus();
        		return false;
            }else{
            	$("#divReq-accident_date").html('');
            	$("#accident_date").closest('.form-group').removeClass('has-error');
        	}

//         	if($("#bpm_radioactive_name").val().length == 0){
//         		$("#bpm_radioactive_name").closest('.form-group').addClass('has-error');
//         		$("#divReq-bpm_radioactive_name").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#bpm_radioactive_name").focus();
//         		return false;
//             }else{
//             	$("#divReq-bpm_radioactive_name").html('');
//             	$("#bpm_radioactive_name").closest('.form-group').removeClass('has-error');
//         	}
        	
//         	if($("#bpm_radioactive_elements_id").val() =="0"){
//         		$("#bpm_radioactive_elements_id").closest('.form-group').addClass('has-error');
//         		$("#divReq-bpm_radioactive_elements_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#bpm_radioactive_elements_id").focus();
//         		return false;
//             }else{
//             	$("#divReq-bpm_radioactive_elements_id").html('');
//             	$("#bpm_radioactive_elements_id").closest('.form-group').removeClass('has-error');
//         	}
        	
//         	if($("#bpm_manufacturer_id_text").val().length ==0){
//         		$("#bpm_manufacturer_id_text").closest('.form-group').addClass('has-error');
//         		$("#divReq-bpm_manufacturer_id_text").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#bpm_manufacturer_id_text").focus();
//         		return false;
//             }else{
//             	$("#divReq-bpm_manufacturer_id_text").html('');
//             	$("#bpm_manufacturer_id_text").closest('.form-group').removeClass('has-error');
//         	}
        	
//         	if($("#bpm_model").val().length ==0){
//         		$("#bpm_model").closest('.form-group').addClass('has-error');
//         		$("#divReq-bpm_model").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#bpm_model").focus();
//         		return false;
//             }else{
//             	$("#divReq-bpm_model").html('');
//             	$("#bpm_model").closest('.form-group').removeClass('has-error');
//         	}

//         	if($("#bpm_no").val().length == 0){
//         		$("#bpm_no").closest('.form-group').addClass('has-error');
//         		$("#divReq-bpm_no").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#bpm_no").focus();
//         		return false;
//             }else{
//             	$("#divReq-bpm_no").html('');
//             	$("#bpm_no").closest('.form-group').removeClass('has-error');
//         	}
//         	if($("#bpm_phisical_id").val() == "0"){
//         		$("#bpm_phisical_id").closest('.form-group').addClass('has-error');
//         		$("#divReq-bpm_phisical_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#bpm_phisical_id").focus();
//         		return false;
//             }else{
//             	$("#divReq-bpm_phisical_id").html('');
//             	$("#bpm_phisical_id").closest('.form-group').removeClass('has-error');
//         	}
        
//         	if($("#bpm_volume").val().length == 0){
//         		$("#bpm_volume").closest('.form-group').addClass('has-error');
//         		$("#divReq-bpm_volume").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#bpm_volume").focus();
//         		return false;
//             }else{
//             	$("#divReq-bpm_volume").html('');
//             	$("#bpm_volume").closest('.form-group').removeClass('has-error');
//         	}
//         	if($("#bpm_volume_unit_id").val() == "0"){
//         		$("#bpm_volume_unit_id").closest('.form-group').addClass('has-error');
//         		$("#divReq-bpm_volume_unit_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#bpm_volume_unit_id").focus();
//         		return false;
//             }else{
//             	$("#divReq-bpm_volume_unit_id").html('');
//             	$("#bpm_volume_unit_id").closest('.form-group').removeClass('has-error');
//         	}
        	
        	
//         	if($("#bpm_as_of_date").val().length == 0){
//         		$("#bpm_as_of_date").closest('.form-group').addClass('has-error');
//         		$("#divReq-bpm_as_of_date").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#bpm_as_of_date").focus();
//         		return false;
//             }else{
//             	$("#divReq-bpm_as_of_date").html('');
//             	$("#bpm_as_of_date").closest('.form-group').removeClass('has-error');
//         	}

//         	if($("#bpm_number").val().length == 0){
//         		$("#bpm_number").closest('.form-group').addClass('has-error');
//         		$("#divReq-bpm_number").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#bpm_number").focus();
//         		return false;
//             }else{
//             	$("#divReq-bpm_number").html('');
//             	$("#bpm_number").closest('.form-group').removeClass('has-error');
//         	}

//         	if($("#machine_manufacturer_id_text").val().length == 0){
//         		$("#machine_manufacturer_id_text").closest('.form-group').addClass('has-error');
//         		$("#divReq-machine_manufacturer_id_text").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#machine_manufacturer_id_text").focus();
//         		return false;
//             }else{
//             	$("#divReq-machine_manufacturer_id_text").html('');
//             	$("#machine_manufacturer_id_text").closest('.form-group').removeClass('has-error');
//         	}
//         	//
//         	if($("#machine_model").val().length == 0){
//         		$("#machine_model").closest('.form-group').addClass('has-error');
//         		$("#divReq-machine_model").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#machine_model").focus();
//         		return false;
//             }else{
//             	$("#divReq-machine_model").html('');
//             	$("#machine_model").closest('.form-group').removeClass('has-error');
//         	}
        	
//         	if($("#machine_number").val().length == 0){
//         		$("#machine_number").closest('.form-group').addClass('has-error');
//         		$("#divReq-machine_number").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#machine_number").focus();
//         		return false;
//             }else{
//             	$("#divReq-machine_number").html('');
//             	$("#machine_number").closest('.form-group').removeClass('has-error');
//         	}

//         	if($("#machine_radioactive_highest").val().length == 0){
//         		$("#machine_radioactive_highest").closest('.form-group').addClass('has-error');
//         		$("#divReq-machine_radioactive_highest").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#machine_radioactive_highest").focus();
//         		return false;
//             }else{
//             	$("#divReq-machine_radioactive_highest").html('');
//             	$("#machine_radioactive_highest").closest('.form-group').removeClass('has-error');
//         	}
        	
//         	if($("#machine_radioactive_highest_volumne").val().length == 0){
//         		$("#machine_radioactive_highest_volumne").closest('.form-group').addClass('has-error');
//         		$("#divReq-machine_radioactive_highest_volumne").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#machine_radioactive_highest_volumne").focus();
//         		return false;
//             }else{
//             	$("#divReq-machine_radioactive_highest_volumne").html('');
//             	$("#machine_radioactive_highest_volumne").closest('.form-group').removeClass('has-error');
//         	}
//         	if($("#machine_radioactive_highest_unit_id").val() == "0"){
//         		$("#machine_radioactive_highest_unit_id").closest('.form-group').addClass('has-error');
//         		$("#divReq-machine_radioactive_highest_unit_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#machine_radioactive_highest_unit_id").focus();
//         		return false;
//             }else{
//             	$("#divReq-machine_radioactive_highest_unit_id").html('');
//             	$("#machine_radioactive_highest_unit_id").closest('.form-group').removeClass('has-error');
//         	}
        	

        	
        	if($("#form2_id").val().length == 0){
        		$("#form2_id").closest('.form-group').addClass('has-error');
        		$("#divReq-form2_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#form2_id").focus();
        		return false;
            }else{
            	$("#divReq-form2_id").html('');
            	$("#form2_id").closest('.form-group').removeClass('has-error');
        	}
        	if($("#material_type_id").val() == "0"){
        		$("#material_type_id").closest('.form-group').addClass('has-error');
        		$("#divReq-material_type_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#material_type_id").focus();
        		return false;
            }else{
            	$("#divReq-material_type_id").html('');
            	$("#material_type_id").closest('.form-group').removeClass('has-error');
        	}
        	
        	
        	if($("#previous_leaks").val().length == 0){
        		$("#previous_leaks").closest('.form-group').addClass('has-error');
        		$("#divReq-previous_leaks").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#previous_leaks").focus();
        		return false;
            }else{
            	$("#divReq-previous_leaks").html('');
            	$("#previous_leaks").closest('.form-group').removeClass('has-error');
        	}
        	if($("#previous_leaks").val().length == 0){
        		$("#previous_leaks").closest('.form-group').addClass('has-error');
        		$("#divReq-previous_leaks").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#previous_leaks").focus();
        		return false;
            }else{
            	$("#divReq-previous_leaks").html('');
            	$("#previous_leaks").closest('.form-group').removeClass('has-error');
        	}

        	if($("#previous_leaks_unit_id").val() == "0"){
        		$("#previous_leaks_unit_id").closest('.form-group').addClass('has-error');
        		$("#divReq-previous_leaks_unit_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#previous_leaks_unit_id").focus();
        		return false;
            }else{
            	$("#divReq-previous_leaks_unit_id").html('');
            	$("#previous_leaks_unit_id").closest('.form-group').removeClass('has-error');
        	}


        	

        	if($("#after_leaks").val().length == 0){
        		$("#after_leaks").closest('.form-group').addClass('has-error');
        		$("#divReq-after_leaks").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#after_leaks").focus();
        		return false;
            }else{
            	$("#divReq-after_leaks").html('');
            	$("#after_leaks").closest('.form-group').removeClass('has-error');
        	}
        	
        	if($("#after_leaks_unit_id").val() == "0"){
        		$("#after_leaks_unit_id").closest('.form-group').addClass('has-error');
        		$("#divReq-after_leaks_unit_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#after_leaks_unit_id").focus();
        		return false;
            }else{
            	$("#divReq-after_leaks_unit_id").html('');
            	$("#after_leaks_unit_id").closest('.form-group').removeClass('has-error');
        	}
        	
        	if($("#accident_type_id").val() == "0"){
        		$("#accident_type_id").closest('.form-group').addClass('has-error');
        		$("#divReq-accident_type_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#accident_type_id").focus();
        		return false;
            }else{
            	$("#divReq-accident_type_id").html('');
            	$("#accident_type_id").closest('.form-group').removeClass('has-error');
        	}



        	if($("#accident_date").val().length == 0){
        		$("#accident_date").closest('.form-group').addClass('has-error');
        		$("#divReq-accident_date").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#accident_date").focus();
        		return false;
            }else{
            	$("#divReq-accident_date").html('');
            	$("#accident_date").closest('.form-group').removeClass('has-error');
        	}

//         	if($("#accident_date").val().length == 0){
//         		$("#accident_date").closest('.form-group').addClass('has-error');
//         		$("#divReq-accident_date").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#accident_date").focus();
//         		return false;
//             }else{
//             	$("#divReq-accident_date").html('');
//             	$("#accident_date").closest('.form-group').removeClass('has-error');
//         	}
        	
        	if($("#accident_room_id_text").val().length == 0){
        		$("#accident_room_id_text").closest('.form-group').addClass('has-error');
        		$("#divReq-accident_room_id_text").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#accident_room_id_text").focus();
        		return false;
            }else{
            	$("#divReq-accident_room_id_text").html('');
            	$("#accident_room_id_text").closest('.form-group').removeClass('has-error');
        	}

   
        	this.submit();
    	});



    });


    
</script>

</form>