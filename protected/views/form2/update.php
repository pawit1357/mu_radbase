<?php
$criCodeUsage = new CDbCriteria ();
$criCodeUsage->condition = " branch_group_id like '%" . UserLoginUtils::getBranchId() . "%' and is_rad_machine like '%2%' and is_sealsource like '%1%' ";

$criteria = new CDbCriteria ();
$criteria->condition = " branch_group_id like '%" . UserLoginUtils::getBranchId() . "%'";

$criteriaRad = new CDbCriteria ();
$criteriaRad->condition = " branch_group_id like '%" . UserLoginUtils::getBranchId() . "%' and type=1";

$codeUsages = MCodeUsage::model ()->findAll ( $criCodeUsage );
$radioactive_elements = MRadioactiveElement::model ()->findAll ( $criteriaRad );
$phisical_status = MPhisicalStatus::model ()->findAll ( $criteria );
// $usage_statuss = MUsageStatus::model ()->findAll ( $criteria );
$materialStatus = MaterialStatus::model()->findAll($criteria);

$criteriaUnit = new CDbCriteria ();
$criteriaUnit->condition = " branch_group_id like '%" . UserLoginUtils::getBranchId() . "%'";
$criteriaUnit->order = 'name ASC';
$units = MUnit::model ()->findAll ( $criteriaUnit );
$unit2s = MUnit2::model ()->findAll ( $criteriaUnit );

$maufacturer_company = Manufacturer::model ()->findAll ( $criteria ); // บริษัทผู้ผลิต
$dealer_company = MDealerCompany::model ()->findAll ( $criteria ); // บริษัทผุ้แทนจำหน่าย

?>
<form id="Form1" method="post" enctype="multipart/form-data"
	class="form-horizontal">
	
	<!-- POPUP -->
	<div id="modalRoom" class="modal fade" tabindex="-1" data-width="760">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"
				aria-hidden="true"></button>
			<h4 class="modal-title">
				<i class="fa fa-building-o"></i> รายชื่อห้อง
			</h4>
		</div>
		<div class="modal-body">

			<table class="table table-striped table-hover table-bordered"
				id="gvResultRoom">
				<thead>
					<tr>
						<th class="no-sort"></th>
						<th>ชื่อห้อง</th>
						<th>เลขห้อง</th>
						<th>ชั้น</th>
						<th>อาคาร</th>
						<th>คณะ</th>
					</tr>
				</thead>
				<tbody>
	<?php
	$model = new MRoom ();
	$counter = 1;
	$dataProvider = $model->search ();
	
	foreach ( $dataProvider->data as $room ) {
		?>
				<tr>
						<td class="center"><a href="#"
							onclick="return selectedRoomValue('<?php echo $room->id.', ชื่อห้อง'.$room->name. (CommonUtil::IsNullOrEmptyString($room->number)? '':' เลขห้อง '.$room->number.'').' ชั้น'. $room->floor.' อาคาร'.$room->building_id.' คณะ'.$room->fac;?>')"><i
								class="fa fa-check-square-o"></i></a></td>

						<td class="center"><?php echo $room->name?></td>
						<td class="center"><?php echo $room->number?></td>
						<td class="center"><?php echo $room->floor?></td>
						<td class="center"><?php echo $room->building_id?></td>
						<td class="center"><?php echo $room->fac?></td>

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
	<!--  -->

	<div id="modalDepartment" class="modal fade" tabindex="-1" data-width="760">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"
				aria-hidden="true"></button>
			<h4 class="modal-title">
				<i class="fa fa-bank"></i> หน่วยงาน
			</h4>
		</div>
		<div class="modal-body">

			<table class="table table-striped table-hover table-bordered"
						id="gvResultDept">
						<thead>
							<tr>
								<th class="no-sort"></th>
								<th>สาขา</th>
								<th>ภาควิชา/หน่วยงาน</th>
								<th>คณะ</th>
							</tr>
						</thead>
						<tbody>
	<?php
	$counter = 1;
	$depts = new  MDepartment();
	
	$dataProvider = $depts->search ();
	
	foreach ( $dataProvider->data as $dept ) {
		?>
				<tr>
								<td class="center"><a href="#" onclick="return selectedDepartmentValue('<?php echo $dept->id.', สาขา '.$dept->branch_id.'ภาควิชา '.$dept->name.' '.(isset($dept->faculty->name)?  $dept->faculty->name:'');?>')"><i class="fa fa-check-square-o"></i></a></td>
								<td class="center"><?php echo $dept->branch_id?></td>
								<td class="center"><?php echo $dept->name?></td>
								<td class="center"><?php echo $dept->faculty->name?></td>

							</tr>
			<?php
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
	<!-- END POPUP -->

	<input type="hidden" value="<?php echo $data->status;?>" name="Form2[status]" id="status" />
		<input type="hidden" value="<?php echo UserLoginUtils::getUserRoleName();?>" name="Form2[userRoleName]" id="userRoleName" />
	
	<div class="portlet box blue" id="divView">
		<div class="portlet-title">
			<div class="caption">
				<?php echo  MenuUtil::getMenuName($_SERVER['REQUEST_URI'])?>

			</div>
			<div class="actions">
			<?php echo CHtml::link('ย้อนกลับ',array('Form2/'),array('class'=>'btn btn-default btn-sm'));?>
			</div>
		</div>
		<div class="portlet-body form">
			<div class="form-body">
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
										<label class="control-label col-md-4">เลขที่ใบอนุญาต:
<!-- 										<span -->
<!-- 											class="required">*</span> -->
											</label>
										<div class="col-md-4">
											<input id="license_no" type="text" value="<?php echo $data->license_no; ?>"
												class="form-control" name="Form2[license_no]">

										</div>
										<div id="divReq-license_no"></div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4"> ใบอนุญาตหมดอายุ:
<!-- 										<span -->
<!-- 											class="required">*</span> -->
											</label>
										<div class="col-md-4">
											<input type="text" value="<?php echo ($data->license_expire_date =='0000-00-00'? '': CommonUtil::getDateThai($data->license_expire_date));?>" id="license_expire_date"
												name="Form2[license_expire_date]" />
										</div>
										<div id="divReq-license_expire_date"></div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">รหัสประเภทการใช้งาน:<span
											class="required">*</span></label>
										<div class="col-md-4">
											<select class="form-control select2"
												name="Form2[code_usage_id]"
												onchange="onchangeCodeUsage(this.value)" id="code_usage_id">
												<option value="0">-- โปรดเลือก --</option>
			<?php foreach($codeUsages as $item) {?>
			<option value="<?php echo $item->id?>" <?php echo (($item->id == $data->code_usage_id)? 'selected="selected"':''); ?>><?php echo sprintf('%02d', $item->code).'-'. $item->name?></option>
			<?php }?>
			</select>
										</div>
										<div class="col-md-4">
											<input id="code_usage_other" type="text" value="<?php echo $data->code_usage_other; ?>"
												class="form-control" name="Form2[code_usage_other]">
										</div>
										<div id="divReq-code_usage_id"></div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">ชื่อวัสดุกัมมันตรังสี
											(ธาตุ-เลขมวล):<span class="required">*</span>
										</label>
										<div class="col-md-4">
											<select class="form-control select2"
												onchange="onchangeRadioactiveElement(this.value)"
												name="Form2[bpm_radioactive_elements_id]"
												id="bpm_radioactive_elements_id">
												<option value="0">-- โปรดเลือก --</option>
<?php foreach($radioactive_elements as $item) {?>
<option value="<?php echo $item->id?>" <?php echo ($item->id == $data->bpm_radioactive_elements_id ? 'selected="selected"':'')?>><?php echo $item->name?></option>
<?php }?>
								</select>
										</div>
										<div class="col-md-4">
											<input id="bpm_radioactive_elements_other" type="text"
												value="<?php echo $data->bpm_radioactive_elements_other?>" class="form-control"
												name="Form2[bpm_radioactive_elements_other]">
										</div>
										<div id="divReq-bpm_radioactive_elements_id"></div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">รุ่น/รหัสสินค้า:<span
											class="required">*</span></label>
										<div class="col-md-4">
											<input id="bpm_model" type="text" value="<?php echo $data->bpm_model;?>"
												class="form-control" name="Form2[bpm_model]">
										</div>
										<div id="divReq-bpm_model"></div>
									</div>
								</div>
							</div>

							<table class="table table-striped table-hover">
								<thead>
									<tr>
										<th>หมายเลข<br>วัสดุ (SN)</th>
										<th>ปริมาณ<br>(ความแรงของรังสี)</th>
										<th>หน่วย</th>
										<?php if(UserLoginUtils::getBranchId()==4){?>
										<th>ปริมาตร</th>
										<th>หน่วย</th>
										<?php }?>
										<th>วันที่ทำการ<br>ตรวจวัด</th>
										<th>จำนวน</th>
									</tr>
								</thead>
								<tbody>
								<?php for($i=0;$i<1;$i++){?>
									<tr>
										<td>
											<input id="bpm_no" type="text" value="<?php echo $data->bpm_no;?>" class="form-control" name="bpm_no[]">
										</td>
										<td>
											<input id="bpm_volume" type="text" value="<?php echo $data->bpm_volume; ?>" class="form-control" placeholder="" name="bpm_volume[]">
										</td>
										<td>
											<select class="form-control select2"
												name="bpm_volume_unit_id[]" id="bpm_volume_unit_id">
													<option value="0">-- โปรดเลือก --</option>
													<?php foreach($units as $item) {?>
													<option value="<?php echo $item->id?>" <?php echo ($item->id == $data->bpm_volume_unit_id ? 'selected="selected"':'')?>><?php echo $item->name?></option>
													<?php }?>
											</select>
										</td>
										<?php if(UserLoginUtils::getBranchId()==4){?>
										<td>
											<input id="bpm_volume2" type="text" value="<?php echo $data->bpm_volume2; ?>" class="form-control" placeholder="" name="bpm_volume2[]">
										</td>
										<td>
											<select class="form-control select2"
												name="bpm_volume_unit_id2[]" id="bpm_volume_unit_id2">
													<option value="0">-- โปรดเลือก --</option>
													<?php foreach($unit2s as $item) {?>
													<option value="<?php echo $item->id?>" <?php echo ($item->id == $data->bpm_volume_unit_id2 ? 'selected="selected"':'')?>><?php echo $item->name?></option>
													<?php }?>
											</select>
										</td>
										<?php }?>
										<td>
											<input type="text" value="<?php echo CommonUtil::getDateThai($data->bpm_as_of_date); ?>" id="bpm_as_of_date_<?php echo $i?>"
											name="bpm_as_of_date[]" style="width: 80px;" />
										</td>
										<td>
											<input id="bpm_number" type="text" value="<?php echo $data->bpm_number;?>"
											class="form-control" placeholder="" name="bpm_number[]">
										</td>
									</tr>
								<?php }?>
								</tbody>
							</table>

							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">ผู้ผลิต:<span
											class="required">*</span></label>
										<div class="col-md-4">
											<select class="form-control select2"
												name="Form2[manufacturer_id]" id="manufacturer_id"
												onchange="onchangeManuFacturer(this.value)">
												<option value="0">-- โปรดเลือก --</option>
			<?php foreach($maufacturer_company as $item) {?>
			<option value="<?php echo $item->id?>" <?php echo (($item->id == $data->manufacturer_id)? 'selected="selected"':''); ?> ><?php echo  $item->name?></option>
			<?php }?>
			</select>
										</div>
										<div class="col-md-4">
											<input id="manufacturer_other" type="text" value="<?php echo $data->manufacturer_other; ?>"
												class="form-control" name="Form2[manufacturer_other]">
										</div>
										<div id="divReq-manufacturer_id"></div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">บริษัทผู้แทนจำหน่าย
											(ที่อยู่):<span class="required">*</span>
										</label>
										<div class="col-md-4">
											<select class="form-control select2" name="Form2[dealer_id]"
												onchange="onchangeDealer(this.value)" id="dealer_id">
												<option value="0">-- โปรดเลือก --</option>
			<?php foreach($dealer_company as $item) {?>
			<option value="<?php echo $item->id?>" <?php echo (($item->id == $data->dealer_id)? 'selected="selected"':''); ?>><?php echo  $item->name?></option>
			<?php }?>
			</select>

										</div>
										<div class="col-md-4">
											<input id="dealer_other" type="text" value="<?php echo $data->dealer_other; ?>"
												class="form-control" name="Form2[dealer_other]">
										</div>
										<div id="divReq-dealer_id"></div>
									</div>
								</div>
							</div>







							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">สมบัติทางกายภาพ :<span class="required">*</span>
										</label>
										<div class="col-md-4">
											<select class="form-control select2"
												name="Form2[bpm_phisical_id]" id="bpm_phisical_id">
												<option value="0">-- โปรดเลือก --</option>
												<?php foreach($phisical_status as $item) {?>
												<option value="<?php echo $item->id?>" <?php echo (($item->id == $data->bpm_phisical_id)? 'selected="selected"':''); ?>><?php echo $item->name?></option>
												<?php }?>
											</select>
										</div>
										<div id="divReq-bpm_phisical_id"></div>
									</div>
								</div>
							</div>
							<!-- XXXXx -->
						</div>
					</div>


					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a class="accordion-toggle" data-toggle="collapse"
									data-parent="#accordion1" href="#collapse_2"> <i
									class="fa fa-building-o"></i>
									ชื่อห้อง/สถานที่เก็บ/ติดตั้งหรือใช้งาน
								</a>
							</h4>
						</div>
						<div id="collapse_2" class="panel-collapse in">
							<br>
							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">ชื่อห้อง/สถานที่เก็บ/ติดตั้งหรือใช้งาน
											:<span class="required">*</span>
										</label>
										<div class="col-md-4">


											<input id="room_name" type="text" value="<?php echo ' ห้อง'.$data->room->name. (CommonUtil::IsNullOrEmptyString($data->room->number)? '':'('.$data->room->number.')') .' อาคาร'.$data->room->building_id.'  คณะ'.$data->room->fac?>"
												name="Form2[room_name]" class="form-control"
												disabled="disabled"> <input id="room_id" type="hidden"
												value="<?php echo $data->room_id;?>" name="Form2[room_id]" class="form-control">


										</div>
										<div>
											<a class="btn btn-outline dark" data-toggle="modal"
												href="#modalRoom"> ค้นหา </a>
										</div>
										<div id="divReq-room_id"></div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">สถานภาพวัสดุ:<span
											class="required">*</span></label>
										<div class="col-md-4">
											<select class="form-control select2" onchange="onChangeMaterialStatus(this.value)"
												name="Form2[material_status_id]" id="material_status_id">


												<option value="0">-- โปรดเลือก --</option>			<?php foreach($materialStatus as $item) {?>
			<option value="<?php echo $item->id?>" <?php echo ($item->id == $data->material_status_id ? 'selected="selected"':'')?>><?php echo $item->name?></option>
			<?php }?>
									
									
									
									</select>


										</div>
										<div class="col-md-4" id="divUsageStatusRemark">
											<table>
												<tr>
													<td><input id="usage_status_remark" type="text" value="<?php echo 'สาขา '.(isset($data->donateToDepartment->branch_id)? $data->donateToDepartment->branch_id:'').' ภาควิชา '.(isset($data->donateToDepartment->name)? $data->donateToDepartment->name:'').' '.(isset($data->donateToDepartment->faculty->name)?  $data->donateToDepartment->faculty->name:'');?>"
														name="Form2[usage_status_remark]" class="form-control"
														disabled="disabled"> <input id="usage_status_to_department_id"
														type="hidden" value="<?php echo $data->usage_status_to_department_id;?>"
														name="Form2[usage_status_to_department_id]" class="form-control">
													</td>
													<td><a class="btn btn-outline dark" data-toggle="modal"
														href="#modalDepartment"> ค้นหา </a></td>
												</tr>
											</table>
										</div>
										<div id="divReq-material_status_id"></div>
									</div>
								</div>
							</div>

						</div>
					</div>


					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a class="accordion-toggle" data-toggle="collapse"
									data-parent="#accordion1" href="#collapse_4"> <i
									class="fa fa-user"></i> ผู้รับผิดชอบวัสดุกัมมันตรังสี
								</a>
							</h4>
						</div>
						<div id="collapse_4" class="panel-collapse in">
							<br>

							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">ชื่อ-นามสกุล :<span
											class="required"></span>
										</label>
										<div class="col-md-4">
											<input id="supervisor_name" type="text" value="<?php echo $data->supervisor_name;?>"
												name="Form2[supervisor_name]" class="form-control">
										</div>
										<div id="divReq-supervisor_name"></div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">หมายเลขโทรศัพท์ :<span
											class="required"></span>
										</label>

										<div class="col-md-4">
											<input id="supervisor_phone" type="text" value="<?php echo $data->supervisor_phone; ?>"
												name="Form2[supervisor_phone]" placeholder=""
												class="form-control">
										</div>
										<div id="divReq-supervisor_phone"></div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">อีเมล์:<span
											class="required"></span>
										</label>
										<div class="col-md-4">
											<input id="supervisor_email" type="text" value="<?php echo $data->supervisor_email;?>"
												name="Form2[supervisor_email]" placeholder=""
												class="form-control">
										</div>
										<div id="divReq-supervisor_name"></div>
									</div>
								</div>
							</div>
						</div>
					</div>



				</div>









				<h4></h4>



				<!-- END FORM-->

			</div>

			<div class="form-actions">
				<div class="row">
					<div class="col-md-10">
						<div class="row">
							<div class="col-md-offset-3 col-md-10">
								<button type="submit" class="btn green uppercase"><?php echo ConfigUtil::getBtnSaveButton();?></button>
								<?php echo CHtml::link(ConfigUtil::getBtnCancelButton(),array('Form2/'),array('class'=>'btn btn-default uppercase'));?>
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
	
	   function selectedRoomValue($vals){

		   var $arrDatas = $vals.split(",");
		   $('#room_id').val($arrDatas[0]);
		   $('#room_name').val($arrDatas[1]);
		   $('#modalRoom').modal('hide');
	   }
	   function selectedDepartmentValue($vals){

		   var $arrDatas = $vals.split(",");
		   $('#usage_status_to_department_id').val($arrDatas[0]);
		   $('#usage_status_remark').val($arrDatas[1]);
		   $('#modalDepartment').modal('hide');
	   }
	   
		function onChangeMaterialStatus($id){
			switch($id){
			case "3"://บริจาค
			case "5"://ยกเลิกการใช้งาน
					$('#divUsageStatusRemark').show();
				break;
				default:
					$('#divUsageStatusRemark').hide();
				break;
			}
		}

		
    function onchangeCodeUsage($id){

        if($id == 999){
        	 $('#code_usage_other').show();
        }else{
        	$('#code_usage_other').val('');
        	$('#code_usage_other').hide();
        }        
    }
    function onchangeRadioactiveElement($id){

        if($id == 999){
        	 $('#bpm_radioactive_elements_other').show();
        }else{
        	$('#bpm_radioactive_elements_other').val('');
        	$('#bpm_radioactive_elements_other').hide();
        }        
    }
    function onchangeManuFacturer($id){

        if($id == 999){
        	 $('#manufacturer_other').show();
        }else{
        	$('#manufacturer_other').val('');
        	$('#manufacturer_other').hide();
        }        
    }
    function onchangeDealer($id){

        if($id == 999){
        	 $('#dealer_other').show();
        }else{
        	$('#dealer_other').val('');
        	$('#dealer_other').hide();
        }        
    }
    
    jQuery(document).ready(function () {

		if($('#status').val() == 'F'|| $('#userRoleName').val() == 'EXECUTIVE'){
			
			$('#divView').find('input, textarea, button, select').attr('disabled','disabled');
		}


    	
    	
    	$('#code_usage_other').hide();
    	$('#bpm_radioactive_elements_other').hide();
    	$('#manufacturer_other').hide();
    	$('#dealer_other').hide();
     	$('#divUsageStatusRemark').hide();

     	$material_status_id = $('#material_status_id').val();

		switch($material_status_id){
		case "3"://บริจาค
		case "5"://ยกเลิกการใช้งาน
				$('#divUsageStatusRemark').show();
			break;
			default:
				$('#divUsageStatusRemark').hide();
			break;
		}
     
        
	    $('.grpOfInt').keypress(function (event) {
            return isNumber(event);
        });
	    $('.grpOfDouble').keypress(function (event) {
            return isDouble(event,this);
        });

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


	    	var tableRoom = $('#gvResultRoom');

	    	var oTable = tableRoom.dataTable({

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



	    	var tableDept = $('#gvResultDept');

	    	var oTableDept = tableDept.dataTable({

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
			
	    $( "#bpm_as_of_date_0" ).datepicker( $.datepicker.regional["th"] ); // Set ภาษาที่เรานิยามไว้ด้านบน
// 	    $( "#bpm_as_of_date_0" ).datepicker("setDate", new Date()); //Set ค่าวันปัจจุบัน
	    $( "#bpm_as_of_date_1" ).datepicker( $.datepicker.regional["th"] ); // Set ภาษาที่เรานิยามไว้ด้านบน
// 	    $( "#bpm_as_of_date_1" ).datepicker("setDate", new Date()); //Set ค่าวันปัจจุบัน
	    $( "#bpm_as_of_date_2" ).datepicker( $.datepicker.regional["th"] ); // Set ภาษาที่เรานิยามไว้ด้านบน
// 	    $( "#bpm_as_of_date_2" ).datepicker("setDate", new Date()); //Set ค่าวันปัจจุบัน
	    $( "#bpm_as_of_date_3" ).datepicker( $.datepicker.regional["th"] ); // Set ภาษาที่เรานิยามไว้ด้านบน
// 	    $( "#bpm_as_of_date_3" ).datepicker("setDate", new Date()); //Set ค่าวันปัจจุบัน
	    $( "#bpm_as_of_date_4" ).datepicker( $.datepicker.regional["th"] ); // Set ภาษาที่เรานิยามไว้ด้านบน
// 	    $( "#bpm_as_of_date_4" ).datepicker("setDate", new Date()); //Set ค่าวันปัจจุบัน

	    $( "#license_expire_date" ).datepicker( $.datepicker.regional["th"] ); // Set ภาษาที่เรานิยามไว้ด้านบน
// 	    $( "#license_expire_date" ).datepicker("setDate", new Date()); //Set ค่าวันปัจจุบัน

	    
    	$("#bpm_model").attr('maxlength','20');
    	$("#bpm_volume").attr('maxlength','100');
    	$("#bpm_number").attr('maxlength','10');
    	$("#license_no").attr('maxlength','20');


    	if($('#bpm_radioactive_elements_id').val()=='999'){
    		 $('#bpm_radioactive_elements_other').show();
    	}

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
//     	   	if(!moment($("#license_expire_date").val(), 'DD/MM/YYYY',true).isValid()){
//         		$("#license_expire_date").closest('.form-group').addClass('has-error');
//         		$("#divReq-license_expire_date").html("<span id=\"id-error\" class=\"help-block help-block-error\">รูปแบบวันที่ผิด จะต้องอยู่ในรูปแบบ dd/mm/yyyy เช่น 18/02/2526.</span>");
//         		$("#license_expire_date").focus();
//         		return false;
//             }else{
//             	$("#divReq-license_expire_date").html('');
//             	$("#license_expire_date").closest('.form-group').removeClass('has-error');
//         	}

//         	if($("#license_no").val().length == 0){
//         		$("#license_no").closest('.form-group').addClass('has-error');
//         		$("#divReq-license_no").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#license_no").focus();
//         		return false;
//             }else{
//             	$("#divReq-license_no").html('');
//             	$("#license_no").closest('.form-group').removeClass('has-error');
//         	}
//         	if($("#license_expire_date").val().length == 0){
//         		$("#license_expire_date").closest('.form-group').addClass('has-error');
//         		$("#divReq-license_expire_date").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#license_expire_date").focus();
//         		return false;
//             }else{
//             	$("#divReq-license_expire_date").html('');
//             	$("#license_expire_date").closest('.form-group').removeClass('has-error');
//         	}
        	if($("#code_usage_id").val() == "0"){
        		$("#code_usage_id").closest('.form-group').addClass('has-error');
        		$("#divReq-code_usage_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#code_usage_id").focus();
        		return false;
            }else{
            	$("#divReq-code_usage_id").html('');
            	$("#code_usage_id").closest('.form-group').removeClass('has-error');
        	}
        	
        	if($("#bpm_radioactive_elements_id").val() =="0"){
        		$("#bpm_radioactive_elements_id").closest('.form-group').addClass('has-error');
        		$("#divReq-bpm_radioactive_elements_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#bpm_radioactive_elements_id").focus();
        		return false;
            }else{
            	$("#divReq-bpm_radioactive_elements_id").html('');
            	$("#bpm_radioactive_elements_id").closest('.form-group').removeClass('has-error');
        	}
        	if($("#bpm_model").val().length ==0){
        		$("#bpm_model").closest('.form-group').addClass('has-error');
        		$("#divReq-bpm_model").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#bpm_model").focus();
        		return false;
            }else{
            	$("#divReq-bpm_model").html('');
            	$("#bpm_model").closest('.form-group').removeClass('has-error');
        	}
        	
//         	if($("#bpm_no").val().length == 0){
//         		$("#bpm_no").closest('.form-group').addClass('has-error');
//         		$("#divReq-bpm_no").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#bpm_no").focus();
//         		return false;
//             }else{
//             	$("#divReq-bpm_no").html('');
//             	$("#bpm_no").closest('.form-group').removeClass('has-error');
//         	}
        	
        	if($("#manufacturer_id").val() == "0"){
        		$("#manufacturer_id").closest('.form-group').addClass('has-error');
        		$("#divReq-manufacturer_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#manufacturer_id").focus();
        		return false;
            }else{
            	$("#divReq-manufacturer_id").html('');
            	$("#manufacturer_id").closest('.form-group').removeClass('has-error');
        	}
        	
        	if($("#dealer_id").val() == "0"){
        		$("#dealer_id").closest('.form-group').addClass('has-error');
        		$("#divReq-dealer_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#dealer_id").focus();
        		return false;
            }else{
            	$("#divReq-dealer_id").html('');
            	$("#dealer_id").closest('.form-group').removeClass('has-error');
        	}
        	
        	if($("#bpm_phisical_id").val() == "0"){
        		$("#bpm_phisical_id").closest('.form-group').addClass('has-error');
        		$("#divReq-bpm_phisical_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#bpm_phisical_id").focus();
        		return false;
            }else{
            	$("#divReq-bpm_phisical_id").html('');
            	$("#bpm_phisical_id").closest('.form-group').removeClass('has-error');
        	}
        	
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
        	
        	if($("#room_id").val() == "0"){
        		$("#room_id").closest('.form-group').addClass('has-error');
        		$("#divReq-room_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#room_id").focus();
        		return false;
            }else{
            	$("#divReq-room_id").html('');
            	$("#room_id").closest('.form-group').removeClass('has-error');
        	}
        	
        	if($("#material_status_id").val() == "0"){
        		$("#material_status_id").closest('.form-group').addClass('has-error');
        		$("#divReq-material_status_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#material_status_id").focus();
        		return false;
            }else{
            	$("#divReq-material_status_id").html('');
            	$("#material_status_id").closest('.form-group').removeClass('has-error');
        	}

//         	if($("#supervisor_name").val().length == 0){
//         		$("#supervisor_name").closest('.form-group').addClass('has-error');
//         		$("#divReq-supervisor_name").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#supervisor_name").focus();
//         		return false;
//             }else{
//             	$("#divReq-supervisor_name").html('');
//             	$("#supervisor_name").closest('.form-group').removeClass('has-error');
//         	}
//         	if($("#supervisor_phone").val().length == 0){
//         		$("#supervisor_phone").closest('.form-group').addClass('has-error');
//         		$("#divReq-supervisor_phone").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#supervisor_phone").focus();
//         		return false;
//             }else{
//             	$("#divReq-supervisor_phone").html('');
//             	$("#supervisor_phone").closest('.form-group').removeClass('has-error');
//         	}
//         	if($("#supervisor_email").val().length == 0){
//         		$("#supervisor_email").closest('.form-group').addClass('has-error');
//         		$("#divReq-supervisor_email").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#supervisor_email").focus();
//         		return false;
//             }else{
//             	$("#divReq-supervisor_email").html('');
//             	$("#supervisor_email").closest('.form-group').removeClass('has-error');
//         	}
        	
        	this.submit();
    	});





    });

//     function initRadioactive(){
//     	$.ajax({
// 		     url: host+"/index.php/AjaxRequest/GetRadioactive",
// 		     type: "GET",
// 		     dataType: "json",
// 		     success: function (json) {
// 		            $('#bpm_radioactive_elements_id').empty();
// 		            $('#bpm_radioactive_elements_id').append($('<option>').text("Select"));
// 		            $.each(json, function(i, obj){
// 		                    $('#bpm_radioactive_elements_id').append($('<option>').text(obj.name).attr('value', obj.id));
// 		            });
     	
// 		     },
// 		     error: function (xhr, ajaxOptions, thrcontrol_nameror) {
// 				alert('ERROR');
// 		     }
//     	});
//     }

    
</script>

</form>