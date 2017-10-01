<?php
$criteria = new CDbCriteria ();
$criteria->condition = " branch_group_id like '%" . UserLoginUtils::getBranchId() . "%'";

$units = MUnit::model ()->findAll ( $criteria );

// $rooms = MRoom::model ()->findAll ();
$materialTypes = MaterialType::model ()->findAll ( $criteria );
$position = MPosition::model ()->findAll ();
?>
<form id="Form3" method="post" enctype="multipart/form-data"
	class="form-horizontal">

	<!-- POPUP -->

	<div id="responsiveRoom" class="modal fade" tabindex="-1"
		data-width="760">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"
				aria-hidden="true"></button>
			<h4 class="modal-title">
				<i class="fa fa-building-o"></i> รายชื่อห้อง
			</h4>
		</div>
		<div class="modal-body">

			<table class="table table-striped table-hover table-bordered"
				id="gvResult">
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
	
	foreach ( $dataProvider->data as $room) {
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
	<div id="responsiveRoom2" class="modal fade" tabindex="-1"
		data-width="760">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"
				aria-hidden="true"></button>
			<h4 class="modal-title">
				<i class="fa fa-building-o"></i> รายชื่อห้อง
			</h4>
		</div>
		<div class="modal-body">

			<table class="table table-striped table-hover table-bordered"
				id="gvResult2">
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
	
	foreach ( $dataProvider->data as $room) {
		?>
				<tr>
						<td class="center"><a href="#"
							onclick="return selectedRoomValue2('<?php echo $room->id.', ชื่อห้อง'.$room->name. (CommonUtil::IsNullOrEmptyString($room->number)? '':' เลขห้อง '.$room->number.'').' ชั้น'. $room->floor.' อาคาร'.$room->building_id.' คณะ'.$room->fac;?>')"><i
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

	<div id="responsive" class="modal fade" tabindex="-1" data-width="760">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"
				aria-hidden="true"></button>
			<h4 class="modal-title">
				<i class="fa fa-flask"></i> รายละเอียดวัสดุกัมมันตรังสี
			</h4>
		</div>
		<div class="modal-body">

			<table class="table table-striped table-hover table-bordered"
				id="gvResult3">
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

	<!-- END POPUP -->


	<div class="portlet box blue">
		<div class="portlet-title">
			<div class="caption">
				<?php echo  MenuUtil::getMenuName($_SERVER['REQUEST_URI'])?>

			</div>
			<div class="actions">
			<?php echo CHtml::link('ย้อนกลับ',array('Form3/'),array('class'=>'btn btn-default btn-sm'));?>
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
								<div class="col-md-8">
									<div class="form-group">
										<label class="control-label col-md-4">ชื่อวัสดุกัมมันตรังสี:<span
											class="required">*</span></label>
										<div class="col-md-6">
											<input id="form2_name" type="text" value=""
												name="Form3[form2_name]" class="form-control"
												disabled="disabled"> <input id="form2_id" type="hidden"
												value="" name="Form3[form2_id]" class="form-control">
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
								<div class="col-md-8">
									<div class="form-group">
										<label class="control-label col-md-4">ประเภทวัสดุกัมมันตรังสี<span
											class="required">*</span></label>
										<div class="col-md-6">
											<select class="form-control select2"
												name="Form3[material_type_id]" id="material_type_id">
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
								<div class="col-md-8">
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

							<div class="row">
								<div class="col-md-8">
									<div class="form-group">
										<label class="control-label col-md-4">ปริมาณ(ความแรงรังสี):<span
											class="required">*</span></label>
										<div class="col-md-4">
											<input id="strength_radiation" type="text" value=""
												class="form-control grpOfDouble"
												name="Form3[strength_radiation]">
										</div>

										<div class="col-md-4">

											<select class="form-control select2"
												name="Form3[strength_radiation_unit_id]"
												id="strength_radiation_unit_id">
												<option value="0">-- โปรดเลือก --</option>
												<?php foreach($units as $item) {?>
												<option value="<?php echo $item->id?>"><?php echo  $item->name; ?></option>
												<?php }?>
												</select>

										</div>
										<div id="divReq-strength_radiation_unit_id"></div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-8">
									<div class="form-group">
										<label class="control-label col-md-4"> วันที่ทำการตรวจวัด:<span
											class="required">*</span>
										</label>
										<div class="col-md-4">
											<input type="text" value="" id="check_date"
												style="width: 80px !important" name="Form3[check_date]" />

										</div>
										<div id="divReq-check_date"></div>
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
									class="fa fa-truck"></i> รายละเอียดการเคลื่อนย้าย
								</a>
							</h4>
						</div>
						<div id="collapse_2" class="panel-collapse in">
							<br>
							<div class="row">
								<div class="col-md-8">
									<div class="form-group">
										<label class="control-label col-md-4">เดิม:<span
											class="required">*</span></label>
										<div class="col-md-6">
											<input id="from_room_name" type="text" value=""
												name="Form3[from_room_name]" class="form-control"
												disabled="disabled"> <input id="from_room_id" type="hidden"
												value="" name="Form3[from_room_id]" class="form-control">
										</div>
										<div>
											<a class="btn btn-outline dark" data-toggle="modal"
												href="#responsiveRoom"> ค้นหา </a>
										</div>






										<div id="divReq-from_room_id"></div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-8">
									<div class="form-group">
										<label class="control-label col-md-4">ไปที่:<span
											class="required">*</span></label>
										<div class="col-md-6">
											<input id="to_room_name" type="text" value=""
												name="Form3[to_room_name]" class="form-control"
												disabled="disabled"> <input id="to_room_id" type="hidden"
												value="" name="Form3[to_room_id]" class="form-control">
										</div>
										<div>
											<a class="btn btn-outline dark" data-toggle="modal"
												href="#responsiveRoom2"> ค้นหา </a>
										</div>
										<div id="divReq-to_room_id"></div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-8">
									<div class="form-group">
										<label class="control-label col-md-4"> ตั้งแต่วันที่ :<span
											class="required">*</span>
										</label>
										<div class="col-md-8">
											<input type="text" value="" id="date_from"
												style="width: 80px !important" name="Form3[date_from]" />
											&nbsp;&nbsp;ถึง&nbsp;&nbsp; <input type="text" value=""
												id="date_to" style="width: 80px !important"
												name="Form3[date_to]" />
										</div>
										<div id="divReq-date_from"></div>
										<div id="divReq-date_to"></div>
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
									class="fa fa-user"></i> ผู้ควบคุม
								</a>
							</h4>
						</div>
						<div id="collapse_3" class="panel-collapse in">
							<br>

							<div class="row">
								<div class="col-md-8">
									<div class="form-group">
										<label class="control-label col-md-4">ชื่อ-นามสกุล :<span
											class="required"></span>
										</label>
										<div class="col-md-6">
											<input id="supervisor_name" type="text" value=""
												name="Form3[supervisor_name]" class="form-control">
										</div>
										<div id="divReq-supervisor_name"></div>
									</div>
								</div>
							</div>
														<div class="row">
								<div class="col-md-8">
									<div class="form-group">
										<label class="control-label col-md-4">ตำแหน่ง:<span
											class="required">*</span></label>
										<div class="col-md-6">

											<select class="form-control select2"
												name="Form3[position_id]" id="position_id">
												<option value="0">-- โปรดเลือก --</option>			<?php foreach($position as $item) {?>
			<option value="<?php echo $item->id?>"><?php echo $item->name?></option>
			<?php }?>
									</select>
										</div>
										<div id="divReq-position_id"></div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-8">
									<div class="form-group">
										<label class="control-label col-md-4">หมายเลขโทรศัพท์ :<span
											class="required"></span>
										</label>

										<div class="col-md-6">
											<input id="supervisor_phone" type="text" value=""
												name="Form3[supervisor_phone]" placeholder=""
												class="form-control">
										</div>
										<div id="divReq-supervisor_phone"></div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-8">
									<div class="form-group">
										<label class="control-label col-md-4">อีเมล์:<span
											class="required"></span>
										</label>
										<div class="col-md-6">
											<input id="supervisor_email" type="text" value=""
												name="Form3[supervisor_email]" placeholder=""
												class="form-control">
										</div>
										<div id="divReq-supervisor_name"></div>
									</div>
								</div>
							</div>

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
								<?php echo CHtml::link(ConfigUtil::getBtnCancelButton(),array('Form3/'),array('class'=>'btn btn-default uppercase'));?>
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
		   $('#from_room_id').val($arrDatas[0]);
		   $('#from_room_name').val($arrDatas[1]);
		   $('#responsiveRoom').modal('hide');
	   }
	   function selectedRoomValue2($vals){

		   var $arrDatas = $vals.split(",");
		   $('#to_room_id').val($arrDatas[0]);
		   $('#to_room_name').val($arrDatas[1]);
		   $('#responsiveRoom2').modal('hide');
	   }
	   
	   function selectedValue($vals){

		   var $arrDatas = $vals.split(",");
		   $('#form2_id').val($arrDatas[0]);
		   $('#form2_name').val($arrDatas[1]);
		   $('#responsive').modal('hide');
	   }
	
    jQuery(document).ready(function () {
	    $('.grpOfInt').keypress(function (event) {
            return isNumber(event);
        });
	    $('.grpOfDouble').keypress(function (event) {
            return isDouble(event,this);
        });


    	var table = $('#gvResult');
    	var table2 = $('#gvResult2');
    	var table3 = $('#gvResult3');

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
    	var oTable = table2.dataTable({

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
    	var oTable = table3.dataTable({

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
			

	    $( "#date_from" ).datepicker( $.datepicker.regional["th"] ); // Set ภาษาที่เรานิยามไว้ด้านบน
	    $( "#date_from" ).datepicker("setDate", new Date()); //Set ค่าวันปัจจุบัน

	    $( "#date_to" ).datepicker( $.datepicker.regional["th"] ); // Set ภาษาที่เรานิยามไว้ด้านบน
	    $( "#date_to" ).datepicker("setDate", new Date()); //Set ค่าวันปัจจุบัน
	    
	    $( "#check_date" ).datepicker( $.datepicker.regional["th"] ); // Set ภาษาที่เรานิยามไว้ด้านบน
	    $( "#check_date" ).datepicker("setDate", new Date()); //Set ค่าวันปัจจุบัน


	     
//     	$("#bpm_model").attr('maxlength','45');
//     	$("#bpm_volume").attr('maxlength','10');
//     	$("#bpm_number").attr('maxlength','10');
//     	$("#machine_model").attr('maxlength','45');
//     	$("#machine_number").attr('maxlength','45');
//     	$("#machine_radioactive_highest").attr('maxlength','10');

    	$( "#Form3" ).submit(function( event ) {
     		//Validate date format

    	   	if(!moment($("#date_from").val(), 'DD/MM/YYYY',true).isValid()){
        		$("#date_from").closest('.form-group').addClass('has-error');
        		$("#divReq-date_from").html("<span id=\"id-error\" class=\"help-block help-block-error\">รูปแบบวันที่ผิด จะต้องอยู่ในรูปแบบ dd/mm/yyyy เช่น 18/02/2526.</span>");
        		$("#date_from").focus();
        		return false;
            }else{
            	$("#divReq-date_from").html('');
            	$("#date_from").closest('.form-group').removeClass('has-error');
        	}
    	   	if(!moment($("#date_to").val(), 'DD/MM/YYYY',true).isValid()){
        		$("#date_to").closest('.form-group').addClass('has-error');
        		$("#divReq-date_to").html("<span id=\"id-error\" class=\"help-block help-block-error\">รูปแบบวันที่ผิด จะต้องอยู่ในรูปแบบ dd/mm/yyyy เช่น 18/02/2526.</span>");
        		$("#date_to").focus();
        		return false;
            }else{
            	$("#divReq-date_to").html('');
            	$("#date_to").closest('.form-group').removeClass('has-error');
        	}
    	   	if(!moment($("#check_date").val(), 'DD/MM/YYYY',true).isValid()){
        		$("#check_date").closest('.form-group').addClass('has-error');
        		$("#divReq-check_date").html("<span id=\"id-error\" class=\"help-block help-block-error\">รูปแบบวันที่ผิด จะต้องอยู่ในรูปแบบ dd/mm/yyyy เช่น 18/02/2526.</span>");
        		$("#check_date").focus();
        		return false;
            }else{
            	$("#divReq-check_date").html('');
            	$("#check_date").closest('.form-group').removeClass('has-error');
        	}
        	
        	if($("#form2_id").val().length ==0){
        		$("#form2_id").closest('.form-group').addClass('has-error');
        		$("#divReq-form2_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#form2_id").focus();
        		return false;
            }else{
            	$("#divReq-form2_id").html('');
            	$("#form2_id").closest('.form-group').removeClass('has-error');
        	}
        	if($("#material_type_id").val() =="0"){
        		$("#material_type_id").closest('.form-group').addClass('has-error');
        		$("#divReq-material_type_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#material_type_id").focus();
        		return false;
            }else{
            	$("#divReq-material_type_id").html('');
            	$("#material_type_id").closest('.form-group').removeClass('has-error');
        	}

        	if($("#strength_radiation").val().length ==0){
        		$("#strength_radiation").closest('.form-group').addClass('has-error');
        		$("#divReq-strength_radiation_unit_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#strength_radiation").focus();
        		return false;
            }else{
            	$("#divReq-strength_radiation_unit_id").html('');
            	$("#strength_radiation").closest('.form-group').removeClass('has-error');
        	}

        	
        	if($("#strength_radiation_unit_id").val() =="0"){
        		$("#strength_radiation_unit_id").closest('.form-group').addClass('has-error');
        		$("#divReq-strength_radiation_unit_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#strength_radiation_unit_id").focus();
        		return false;
            }else{
            	$("#divReq-strength_radiation_unit_id").html('');
            	$("#strength_radiation_unit_id").closest('.form-group').removeClass('has-error');
        	}

        	
        	if($("#from_room_id").val().length == 0){
        		$("#from_room_id").closest('.form-group').addClass('has-error');
        		$("#divReq-from_room_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#from_room_id").focus();
        		return false;
            }else{
            	$("#divReq-from_room_id").html('');
            	$("#from_room_id").closest('.form-group').removeClass('has-error');
        	}
//
        	if($("#to_room_id").val().length == 0){
        		$("#to_room_id").closest('.form-group').addClass('has-error');
        		$("#divReq-to_room_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#to_room_id").focus();
        		return false;
            }else{
            	$("#divReq-to_room_id").html('');
            	$("#to_room_id").closest('.form-group').removeClass('has-error');
        	}
//
        	if($("#date_from").val().length == 0){
        		$("#date_from").closest('.form-group').addClass('has-error');
        		$("#divReq-date_from").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#date_from").focus();
        		return false;
            }else{
            	$("#divReq-date_from").html('');
            	$("#date_from").closest('.form-group').removeClass('has-error');
        	}
        	
        	if($("#date_to").val().length == 0){
        		$("#date_to").closest('.form-group').addClass('has-error');
        		$("#divReq-date_to").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#date_to").focus();
        		return false;
            }else{
            	$("#divReq-date_to").html('');
            	$("#date_to").closest('.form-group').removeClass('has-error');
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

   
</script>

</form>