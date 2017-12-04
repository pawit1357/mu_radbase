<?php
$criteria = new CDbCriteria ();
$criteria->condition = " branch_group_id like '%" . UserLoginUtils::getBranchId () . "%'";

$typeOfRadWastes = MTypesOfRadioactiveWaste::model ()->findAll ();
// $materialTypes = MaterialType::model ()->findAll ();
$phisical_status = MPhisicalStatus::model ()->findAll ( $criteria );
$units = MUnit::model ()->findAll ( $criteria );

// $form2 = Form2::model ()->findAll ();
$companyOperates = MCompanyOperates::model ()->findAll ( $criteria ); // บริษัทผุ้แทนจำหน่าย
$inspectionAgencys = MInspectionAgency::model ()->findAll ( $criteria ); // หน่วยงานตรวจสอบคุณภาพ/หน่วยงานรับกำจัด
$criteriaDeps = new CDbCriteria ();
$criteriaDeps->condition = " id <> -1";
$deps = MDepartment::model ()->findAll ( $criteriaDeps );
?>

<form id="Form1" method="post" enctype="multipart/form-data"
	class="form-horizontal">

	<input type="hidden" value="<?php echo $data->status;?>"
		name="Form5[status]" id="status" /> <input type="hidden"
		value="<?php echo  UserLoginUtils::getUserRoleName();?>"
		name="Form5[userRoleName]" id="userRoleName" />

	<div class="portlet box blue" id="divView">
		<div class="portlet-title">
			<div class="caption">
				<?php echo  MenuUtil::getMenuName($_SERVER['REQUEST_URI'])?>

			</div>
			<div class="actions">
			<?php echo CHtml::link('ย้อนกลับ',array('Form5/'),array('class'=>'btn btn-default btn-sm'));?>
			</div>
		</div>
		<div class="portlet-body form">
			<div class="form-body">
				<!-- BEGIN FORM-->
				<div id="modalHelp" class="modal fade" tabindex="-1"
					data-width="760">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"
							aria-hidden="true"></button>
						<h4 class="modal-title">
							<i class="fa fa-flask"></i> ประเภทกากกัมมันตรังสี
						</h4>
					</div>
					<div class="modal-body">
						<table>
							<tr>
								<td></td>
								<td>- กากกัมมันตรังสีระดับสูง (104-106 Ci/m3) ได้แก่
									กากกัมมันตรังสีในสภาพของแข็งและของเหลวที่ได้จากการฟอกเชื้อเพลิงนิวเคลียร์
									และกากกัมมันตรังสีอื่นๆ ที่มีระดับรังสีสูง</td>
							</tr>
							<tr>
								<td></td>
								<td>- กากกัมมันตรังสีระดับต่ำและปานกลาง (10-6 -1 Ci/m3) ได้แก่
									กากกัมมันตรังสีระดับต่ำและของเสียที่เกิดจากการปฏิบัติงานที่เกี่ยวข้องกับวัสดุกัมมันตรังสี
									เช่น ถุงมือ เสื้อผ้า อุปกรณ์ที่ทำจากกระดาษ
									ส่วนกากกัมมันตรังสีระดับปานกลางเป็นกากกัมมันตรังสีและของเสียระดับปานกลางที่เกิดจากการปฏิบัติงานที่เกี่ยวข้องกับวัสดุกัมมันตรังสี
									เช่น เศษโลหะ กากตะกอนที่ได้จากการบำบัด กากของเสีย ของเหลว
									สารแลกเปลี่ยนไอออน และต้นกำเนิดรังสีใช้แล้ว</td>
							</tr>
						</table>

					</div>
					<div class="modal-footer">
						<button type="button" data-dismiss="modal"
							class="btn btn-outline dark">Close</button>
					</div>
				</div>


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

				<div class="row">
					<div class="col-md-10">
						<div class="form-group">
							<label class="control-label col-md-4"> วัน/เดือน/ปี ที่กำจัด:<span
								class="required">*</span></label>
							<div class="col-md-4">
								<input type="text"
									value="<?php echo (isset($data->clear_date)? CommonUtil::getDateThai($data->clear_date):'');?>"
									id="clear_date" name="Form5[clear_date]" />

							</div>
							<div id="divReq-clear_date"></div>
						</div>
					</div>
				</div>



				<div class="row">
					<div class="col-md-10">
						<div class="form-group">
							<label class="control-label col-md-4">ประเภทกากกัมมันตรังสี<span
								class="required">*</span></label>
							<div class="col-md-4">
								<select class="form-control select2"
									name="Form5[types_of_radioactive_waste_id]"
									id="types_of_radioactive_waste_id">
									<option value="0">-- โปรดเลือก --</option>
	<?php foreach($typeOfRadWastes as $item) {?>
<option value="<?php echo $item->id?>"
										<?php echo ($item->id == $data->types_of_radioactive_waste_id ? 'selected="selected"':'')?>><?php echo $item->name?></option>
<?php }?>
								</select>
							</div>
							<a href="#modalHelp" class="mt-sweetalert mt-intalic"
								data-toggle="modal">help</a>
							<div id="divReq-types_of_radioactive_waste_id"></div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-10">
						<div class="form-group">
							<label class="control-label col-md-4">ชื่อกากกัมมันตรังสี
								(ธาตุ-เลขมวล):<span class="required">*</span>
							</label>
							<div class="col-md-4">
								<input id="form2_name" type="text"
									value="<?php echo (isset($data->rad->bpm_radioactive_elements->name)? $data->rad->bpm_radioactive_elements->name:'').(isset($data->rad->bpm_no )? ' ('.$data->rad->bpm_no .')':'');?>"
									name="Form5[form2_name]" class="form-control"
									disabled="disabled"> <input id="form2_id" type="hidden"
									value="<?php echo $data->form2_id;?>" name="Form5[form2_id]"
									class="form-control"> <input id="radType" type="hidden"
									value="<?php echo (isset($data->rad->type)? $data->rad->type:'');?>"
									name="" class="form-control">
							</div>
							<div>
								<a class="btn btn-outline dark" data-toggle="modal"
									href="#responsive"> ค้นหา </a>
							</div>
							<div id="divReq-form2_id"></div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-10">
						<div class="form-group">
							<label class="control-label col-md-4">สมบัติทางกายภาพ:<span
								class="required">*</span></label>
							<div class="col-md-4">
								<select class="form-control select2"
									name="Form5[phisical_status_id]" id="phisical_status_id">
									<option value="0">-- โปรดเลือก --</option>
	<?php foreach($phisical_status as $item) {?>
<option value="<?php echo $item->id?>"
										<?php echo ($item->id == $data->phisical_status_id ? 'selected="selected"':'')?>><?php echo $item->name?></option>
<?php }?>
								</select>
							</div>
							<div id="divReq-phisical_status_id"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-10">
						<div class="form-group">
							<label class="control-label col-md-4">กัมมันตภาพสูงสุดหรือน้ำหนัก
								<span class="required">*</span>
							</label>
							<div class="col-md-4">
								<input id="rad_or_maximum_weight" type="text"
									value="<?php echo $data->rad_or_maximum_weight;?>"
									class="grpOfDouble form-control" placeholder=""
									name="Form5[rad_or_maximum_weight]">
							</div>
							<div class="col-md-4">
								<select class="form-control select2"
									name="Form5[rad_or_maximum_weight_unit_id]"
									id="rad_or_maximum_weight_unit_id">
									<option value="0">-- โปรดเลือก --</option>
<?php foreach($units as $item) {?>
<option value="<?php echo $item->id?>"
										<?php echo ($item->id == $data->rad_or_maximum_weight_unit_id ? 'selected="selected"':'')?>><?php echo $item->name?></option>
<?php }?>
								</select>
							</div>
							<!-- 							<span class="required">ป้อนข้อมูลเฉพาะตัวเลข</span> -->
							<div id="divReq-rad_or_maximum_weight"></div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-10">
						<div class="form-group">
							<label class="control-label col-md-4">หน่วยงานผู้รับผิดชอบ<span
								class="required">*</span></label>

							<div class="col-md-6">
								<select class="form-control select2"
									name="Form5[responsible_department_id]"
									id="responsible_department_id">
									<option value="0">-- โปรดเลือก --</option>
<?php foreach($deps as $item) {?>
<option value="<?php echo $item->id?>"
										<?php echo ($item->id == $data->responsible_department_id ? 'selected="selected"':'')?>><?php echo 'สาขา '.$item->branch_id.'ภาควิชา '.$item->name.' '.(isset($item->faculty->name)?  $item->faculty->name:'');?></option>
<?php }?>
								</select>
							</div>

							<div id="divReq-training_department_id"></div>
						</div>
					</div>
				</div>

				<div class="row" id="divComOperates">
					<div class="col-md-10">
						<div class="form-group">
							<label class="control-label col-md-4">บริษัทผู้ดำเนินการ :<span
								class="required">*</span>
							</label>
							<div class="col-md-4">
								<select class="form-control select2"
									name="Form5[company_operates_id]"
									onchange="onchangeCompanyOperates(this.value)"
									id="company_operates_id">
									<option value="0">-- โปรดเลือก --</option>
			<?php foreach($companyOperates as $item) {?>
			<option value="<?php echo $item->id?>"
										<?php echo ($item->id == $data->company_operates_id ? 'selected="selected"':'')?>><?php echo sprintf('%02d', $item->id).'-'. $item->name?></option>
			<?php }?>
			</select>

							</div>
							<div class="col-md-4">
								<input id="company_operates_other" type="text"
									value="<?php echo $data->company_operates_other;?>"
									class="form-control" name="Form5[company_operates_other]">
							</div>
							<div id="divReq-company_operates_id"></div>
						</div>
					</div>
				</div>

				<div class="row" id="divDept">
					<div class="col-md-10">
						<div class="form-group">
							<label class="control-label col-md-4">หน่วยงาน/บริษัทผู้รับกำจัดกำจัด:<span
								class="required">*</span></label>
							<div class="col-md-4">

								<select class="form-control select2"
									onchange="onchangeInspectionAgency(this.value)"
									name="Form5[inspection_agency_id]" id="inspection_agency_id">
									<option value="0">-- โปรดเลือก --</option>			
									<?php foreach($inspectionAgencys as $item) {?>
			<option value="<?php echo $item->id?>"
										<?php echo ($item->id == $data->inspection_agency_id ? 'selected="selected"':'')?>><?php echo $item->name?></option>
			<?php }?>
									</select>


							</div>
							<div class="col-md-4">
								<input id="inspection_agency_other" type="text"
									value="<?php echo $data->inspection_agency_other;?>"
									class="form-control" name="Form5[inspection_agency_other]">
							</div>
							<div id="divReq-inspection_agency_id"></div>
						</div>
					</div>
				</div>

				<div class="row" id="divMethod">
					<div class="col-md-10">
						<div class="form-group">
							<label class="control-label col-md-4">วิธีการกำจัด:<span
								class="required">*</span></label>
							<div class="col-md-4">
								<select class="form-control select2" name="Form5[rid_method]"
									id="rid_method">
									<option value="0"
										<?php echo ("0" == $data->rid_method ? 'selected="selected"':'')?>>--
										โปรดเลือก --</option>
									<option value="1"
										<?php echo ("1" == $data->rid_method ? 'selected="selected"':'')?>>concentrate
										and contain</option>
									<option value="2"
										<?php echo ("2" == $data->rid_method ? 'selected="selected"':'')?>>dilute
										and disperse</option>
									<option value="3"
										<?php echo ("3" == $data->rid_method ? 'selected="selected"':'')?>>delay
										and decay</option>
								</select>
							</div>
							<div id="divReq-rid_method"></div>
						</div>
					</div>
				</div>


				<!-- END FORM-->

			</div>
			<div class="form-actions">
				<div class="row">
					<div class="col-md-10">
						<div class="row">
							<div class="col-md-offset-3 col-md-10">
								<button type="submit" class="btn green uppercase"><?php echo ConfigUtil::getBtnSaveButton();?></button>
								<?php echo CHtml::link(ConfigUtil::getBtnCancelButton(),array('Form5/'),array('class'=>'btn btn-default uppercase'));?>
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
		   
		 
		   
			if($arrDatas[2] == '1'){
				$('#divDept').show();
		   		$('#divComOperates').show();
		   		$('#divMethod').hide();
			}else{
				$('#divDept').hide();
				$('#divComOperates').hide();
				$('#divMethod').show();
			}
	   }
	   
	function onchangeRad($id){
// 		alert($id);
//TODO
	}
	
    function onchangeCompanyOperates($id){
        //fix 27 = other
        if($id == 999){
        	 $('#company_operates_other').show();
        }else{
        	$('#company_operates_other').val('');
        	$('#company_operates_other').hide();
        }
    }
    function onchangeInspectionAgency($id){
        //fix 27 = other
        if($id == 999){
        	 $('#inspection_agency_other').show();
        }else{
        	$('#inspection_agency_other').val('');
        	$('#inspection_agency_other').hide();
        }
    }
    
    jQuery(document).ready(function () {
        
		if($('#status').val() == 'F'|| $('#userRoleName').val() == 'EXECUTIVE'){
			
			$('#divView').find('input, textarea, button, select').attr('disabled','disabled');
		}
		
		$('#divDept').hide();
   		$('#divComOperates').hide();
   		$('#divMethod').hide();
    	$('#company_operates_other').hide();
    	$('#inspection_agency_other').hide();

   	
        if($('#company_operates_id').val() == 999){
       	 $('#company_operates_other').show();
       }else{
       	$('#company_operates_other').val('');
       	$('#company_operates_other').hide();
       }
        
		if($('#inspection_agency_id').val() == 999){
			 $('#inspection_agency_other').show();
		}else{
			$('#inspection_agency_other').val('');
			$('#inspection_agency_other').hide();
		}


		if($('#radType').val() == '1'){
			$('#divDept').show();
	   		$('#divComOperates').show();
	   		$('#divMethod').hide();
		}else{
			$('#divDept').hide();
			$('#divComOperates').hide();
			$('#divMethod').show();
		}
		
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
//     	$("#name").attr('maxlength','20');
    	$("#rad_or_maximum_weight").attr('maxlength','10');

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
			
	    $( "#clear_date" ).datepicker( $.datepicker.regional["th"] ); // Set ภาษาที่เรานิยามไว้ด้านบน
// 	    $( "#clear_date" ).datepicker("setDate", new Date()); //Set ค่าวันปัจจุบัน


	    

	$( "#Form1" ).submit(function( event ) {
 		//Validate date format
	   	if(!moment($("#clear_date").val(), 'DD/MM/YYYY',true).isValid()){
    		$("#clear_date").closest('.form-group').addClass('has-error');
    		$("#divReq-clear_date").html("<span id=\"id-error\" class=\"help-block help-block-error\">รูปแบบวันที่ผิด จะต้องอยู่ในรูปแบบ dd/mm/yyyy เช่น 18/02/2526.</span>");
    		$("#clear_date").focus();
    		return false;
        }else{
        	$("#divReq-clear_date").html('');
        	$("#clear_date").closest('.form-group').removeClass('has-error');
    	}



    	
 	if($("#clear_date").val().length==0){
 		$("#clear_date").closest('.form-group').addClass('has-error');
 		$("#divReq-clear_date").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
 		$("#clear_date").focus();
 		return false;
     }else{
     	$("#divReq-clear_date").html('');
     	$("#clear_date").closest('.form-group').removeClass('has-error');
 	}

 	if($("#types_of_radioactive_waste_id").val() == "0"){
 		$("#types_of_radioactive_waste_id").closest('.form-group').addClass('has-error');
 		$("#divReq-types_of_radioactive_waste_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
 		$("#types_of_radioactive_waste_id").focus();
 		return false;
     }else{
     	$("#divReq-types_of_radioactive_waste_id").html('');
     	$("#types_of_radioactive_waste_id").closest('.form-group').removeClass('has-error');
 	}
 	if($("#form2_id").val().length == 0){
 		$("#form2_id").closest('.form-group').addClass('has-error');
 		$("#divReq-form2_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
 		$("#form2_id").focus();
 		return false;
     }else{
     	$("#divReq-form2_id").html('');
     	$("#form2_id").closest('.form-group').removeClass('has-error');
 	}


 	if($("#phisical_status_id").val() =="0"){
 		$("#phisical_status_id").closest('.form-group').addClass('has-error');
 		$("#divReq-phisical_status_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
 		$("#phisical_status_id").focus();
 		return false;
     }else{
     	$("#divReq-phisical_status_id").html('');
     	$("#phisical_status_id").closest('.form-group').removeClass('has-error');
 	}

 	if($("#rad_or_maximum_weight").val().length ==0){
 		$("#rad_or_maximum_weight").closest('.form-group').addClass('has-error');
 		$("#divReq-rad_or_maximum_weight").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
 		$("#rad_or_maximum_weight").focus();
 		return false;
     }else{
     	$("#divReq-rad_or_maximum_weight").html('');
     	$("#rad_or_maximum_weight").closest('.form-group').removeClass('has-error');
 	}


 	
//
 	if($("#rad_or_maximum_weight_unit_id").val() == "0"){
 		$("#rad_or_maximum_weight_unit_id").closest('.form-group').addClass('has-error');
 		$("#divReq-rad_or_maximum_weight_unit_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
 		$("#rad_or_maximum_weight_unit_id").focus();
 		return false;
     }else{
     	$("#divReq-rad_or_maximum_weight_unit_id").html('');
     	$("#rad_or_maximum_weight_unit_id").closest('.form-group').removeClass('has-error');
 	}
//  	if($("#company_operates_id").val() == "0"){
//  		$("#company_operates_id").closest('.form-group').addClass('has-error');
//  		$("#divReq-company_operates_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//  		$("#company_operates_id").focus();
//  		return false;
//      }else{
//      	$("#divReq-company_operates_id").html('');
//      	$("#company_operates_id").closest('.form-group').removeClass('has-error');
//  	}
//  	if($("#department_id_text").val().length == 0){
//  		$("#department_id_text").closest('.form-group').addClass('has-error');
//  		$("#divReq-department_id_text").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//  		$("#department_id_text").focus();
//  		return false;
//      }else{
//      	$("#divReq-department_id_text").html('');
//      	$("#department_id_text").closest('.form-group').removeClass('has-error');
//  	}
 	


 	
 	this.submit();
	});



 
    	
//     	initDepartment();
//     	initPhisicalStatus();
//     	initMaterialType();
    });
    
//     function initDepartment(){
//     	$.ajax({
// 		     url: host+"/index.php/AjaxRequest/GetDepartment",
// 		     type: "GET",
// 		     dataType: "json",
// 		     success: function (json) {
// 		            $('#department_id_text').empty();
// 		            $('#department_id_text').append($('<option>').text("Select"));
// 		            $.each(json, function(i, obj){
// 		                    $('#department_id_text').append($('<option>').text(obj.name).attr('value', obj.id));
// 		            });
     	
// 		     },
// 		     error: function (xhr, ajaxOptions, thrownError) {
// 				alert('ERROR');
// 		     }
//     	});
//     }
//     function initMaterialType(){
//     	$.ajax({
// 		     url: host+"/index.php/AjaxRequest/GetMaterialType",
// 		     type: "GET",
// 		     dataType: "json",
// 		     success: function (json) {
// 		            $('#types_of_radioactive_waste_id').empty();
// 		            $('#types_of_radioactive_waste_id').append($('<option>').text("Select"));
// 		            $.each(json, function(i, obj){
// 		                    $('#types_of_radioactive_waste_id').append($('<option>').text(obj.name).attr('value', obj.id));
// 		            });
     	
// 		     },
// 		     error: function (xhr, ajaxOptions, thrownError) {
// 				alert('ERROR');
// 		     }
//     	});
//     }
//     function initPhisicalStatus(){
//     	$.ajax({
// 		     url: host+"/index.php/AjaxRequest/GetPhisicalStatus",
// 		     type: "GET",
// 		     dataType: "json",
// 		     success: function (json) {
// 		            $('#phisical_status_id').empty();
// 		            $('#phisical_status_id').append($('<option>').text("Select"));
// 		            $.each(json, function(i, obj){
// 		                    $('#phisical_status_id').append($('<option>').text(obj.name).attr('value', obj.id));
// 		            });
     	
// 		     },
// 		     error: function (xhr, ajaxOptions, thrownError) {
// 				alert('ERROR');
// 		     }
//     	});
//     }
</script>

</form>