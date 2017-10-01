		<?php
		$criCodeUsage = new CDbCriteria ();
		$criCodeUsage->condition = " branch_group_id like '%" . UserLoginUtils::getBranchId() . "%' and is_rad_machine like '%1%' ";
		
		$criteria = new CDbCriteria ();
		$criteria->condition = " branch_group_id like '%" . UserLoginUtils::getBranchId() . "%'";
		
		$code_usages = MCodeUsage::model ()->findAll ( $criCodeUsage ); // รหัสประเภทการใช้งาน
		
		$maufacturer_company = Manufacturer::model ()->findAll ( $criteria ); // บริษัทผู้ผลิต
		$dealer_company = MDealerCompany::model ()->findAll ( $criteria ); // บริษัทผุ้แทนจำหน่าย
		$utilizations = MUtilization::model ()->findAll ( $criteria ); // การใช้ประโยชน์
		$inspectionAgencys = MInspectionAgency::model ()->findAll ( $criteria ); // หน่วยงานตรวจสอบคุณภาพ/หน่วยงานรับกำจัด
		
		$use_types = MUseType::model ()->findAll ( $criteria );
		$power_units = MPowerUnit::model ()->findAll ( $criteria ); // กำลัง/พลังงานสูงสุด
		$usage_statuss = MUsageStatus::model ()->findAll ( $criteria );
		
// 		$deps = MDepartment::model ()->findAll ();
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
									<th>ลำดับ</th>
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
								<td class="center"><?php echo $counter;?></td>
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


	<div class="portlet box blue">
		<div class="portlet-title">
			<div class="caption">
				<?php echo  MenuUtil::getMenuName($_SERVER['REQUEST_URI'])?>
			</div>
			<div class="actions">
			<?php echo CHtml::link('ย้อนกลับ',array('Form1/'),array('class'=>'btn btn-default btn-sm'));?>
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
									class="fa fa-cog"></i> ข้อมูลเครื่องกำเนิดรังสี
								</a>
							</h4>
						</div>
						<div id="collapse_1" class="panel-collapse in">
							<br>
							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">เลขที่ใบอนุญาต:
<!-- 										<span class="required">*</span> -->
										</label>
										<div class="col-md-4">
											<input id="license_no" type="text" value=""
												class="form-control" name="Form1[license_no]">

										</div>
										<div id="divReq-license_no"></div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4"> ใบอนุญาตหมดอายุ:
<!-- 										<span class="required">*</span> -->
											</label>
										<div class="col-md-4">
											<input type="text"
												value=""
												id="license_expire_date" name="Form1[license_expire_date]" />

										</div>
										<div id="divReq-license_expire_date"></div>
									</div>
								</div>
							</div>
							
		
							
							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">รหัสประเภทการใช้งาน: <span
											class="required">*</span>
										</label>
										<div class="col-md-4">

											<select class="form-control select2"
												name="Form1[code_usage_id]" id="code_usage_id"
												onchange="onchangeCodeUsage(this.value)">
												<option value="0">-- โปรดเลือก --</option>
			<?php foreach($code_usages as $item) {?>
			<option value="<?php echo $item->id?>"><?php echo sprintf('%02d', $item->code).'-'. $item->name?></option>
			<?php }?>
			</select>

										</div>
										<div class="col-md-4">
											<input id="code_usage_other" type="text" value=""
												class="form-control" name="Form1[code_usage_other]">
										</div>
										<div id="divReq-code_usage_id"></div>
									</div>
								</div>

							</div>
							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">ชื่อเครื่องมือ: <span
											class="required">*</span></label>
										<div class="col-md-4">

											<select class="form-control select2"
												name="Form1[rad_machine_id]" id="rad_machine_id"
												onchange="onchangeRadMachine(this.value)">
<!-- 												<option value="0">-- โปรดเลือก --</option> -->
											</select>


										</div>
										<div class="col-md-2">
											<input id="rad_machine_other" type="text" value=""
												class="form-control" name="Form1[rad_machine_other]">
										</div>
										<div id="divReq-rad_machine_id"></div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10">
									<div class="form-group last">
										<label class="control-label col-md-4">ภาพเครื่องมือ</label>
										<div class="col-md-4">
											<div class="fileinput fileinput-new"
												data-provides="fileinput">
												<div class="fileinput-new thumbnail"
													style="width: 200px; height: 150px;">
													<img
														src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image"
														alt="" />
												</div>
												<div class="fileinput-preview fileinput-exists thumbnail"
													style="max-width: 200px; max-height: 150px;"></div>
												<div>
													<span class="btn default btn-file"> <span
														class="fileinput-new"> Select image </span> <span
														class="fileinput-exists"> Change </span> <input
														type="file" name="machine_file[]">
													</span> <a href="javascript:;"
														class="btn red fileinput-exists" data-dismiss="fileinput">
														Remove </a>
												</div>
												<span class="required"> ไฟล์ไม่เกิน 1MB</span>
											</div>
											
											<div class="clearfix margin-top-10">
												<!-- 												<span class="label label-danger">NOTE!</span> Image preview -->
												<!-- 												only works in IE10+, FF3.6+, Safari6.0+, Chrome6.0+ and -->
												<!-- 												Opera11.1+. In older browsers the filename is shown instead. -->
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">รุ่น(Model):<span
											class="required">*</span></label>
										<div class="col-md-4">
											<input id="model" type="text" value="" name="Form1[model]"
												class="form-control">
										</div>
										<div id="divReq-model"></div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">หมายเลขเครื่องกำเนิดรังสี(SN):
											<span class="required">*</span>
										</label>
										<div class="col-md-4">
											<input id="serial_number" type="text" value=""
												class="form-control" name="Form1[serial_number]">
										</div>
										<div id="divReq-serial_number"></div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">กำลัง/พลังงานสูงสุด:<span
											class="required">*</span></label>
										<div class="col-md-4">
											<input id="power" type="text" value=""
												class="form-control grpOfDouble" name="Form1[power]">
										</div>

										<div class="col-md-4">

											<select class="form-control select2"
												name="Form1[power_unit_id]" id="power_unit_id">
												<option value="0">-- โปรดเลือก --</option>
			<?php foreach($power_units as $item) {?>
			<option value="<?php echo $item->id?>"><?php echo  $item->name.' ('. $item->name_en.')'?></option>
			<?php }?>
			</select>

										</div>
										<div id="divReq-power_unit_id"></div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">กำลัง/พลังงานสูงสุด(2):<span
											class="required">*</span></label>
										<div class="col-md-4">
											<input id="power2" type="text" value=""
												class="form-control grpOfDouble" name="Form1[power2]">
										</div>

										<div class="col-md-4">

											<select class="form-control select2"
												name="Form1[power_unit_id2]" id="power_unit_id2">
												<option value="0">-- โปรดเลือก --</option>
			<?php foreach($power_units as $item) {?>
			<option value="<?php echo $item->id?>"><?php echo  $item->name.' ('. $item->name_en.')'?></option>
			<?php }?>
			</select>

										</div>
										<div id="divReq-power_unit_id"></div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">ผู้ผลิต:<span
											class="required">*</span></label>
										<div class="col-md-4">
											<select class="form-control select2"
												name="Form1[maufacturer_id]" id="maufacturer_id"
												onchange="onchangeManuFacturer(this.value)">
												<option value="0">-- โปรดเลือก --</option>
			<?php foreach($maufacturer_company as $item) {?>
			<option value="<?php echo $item->id?>"><?php echo $item->name?></option>
			<?php }?>
			</select>
										</div>
										<div class="col-md-4">
											<input id="maufacturer_other" type="text" value=""
												class="form-control" name="Form1[maufacturer_other]">
										</div>
										<div id="divReq-maufacturer_id"></div>
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
											<select class="form-control select2" name="Form1[dealer_id]"
												onchange="onchangeDealer(this.value)" id="dealer_id">
												<option value="0">-- โปรดเลือก --</option>
												<?php foreach($dealer_company as $item) {?>
												<option value="<?php echo $item->id?>"><?php echo $item->name?></option>
												<?php }?>
											</select>

										</div>
										<div class="col-md-4">
											<input id="dealer_other" type="text" value=""
												class="form-control" name="Form1[dealer_other]">
										</div>
										<div id="divReq-dealer_id"></div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">ลักษณะการใช้งาน:<span
											class="required">*</span></label>
										<div class="col-md-4">
											<select class="form-control select2"
												name="Form1[use_type_id]" id="use_type_id">
												<option value="0">-- โปรดเลือก --</option>			
												<?php foreach($use_types as $item) {?>
													<option value="<?php echo $item->id?>"><?php echo $item->name.' ( '.$item->description.' )'?></option>
												<?php }?>
									</select>
										</div>
										<div id="divReq-use_type_id"></div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">การใช้ประโยชน์:<span
											class="required">*</span></label>
										<div class="col-md-4">

											<select class="form-control select2"
												name="Form1[utilization_id]" id="utilization_id"
												onchange="onchangeUtilization(this.value)">
												<option value="0">-- โปรดเลือก --</option>			
									<?php foreach($utilizations as $item) {?>
									<option value="<?php echo $item->id?>"><?php echo $item->name?></option>
									<?php }?>
									</select>
										</div>
										<div class="col-md-4">
											<input id="utilization_other" type="text" value=""
												class="form-control" name="Form1[utilization_other]">
										</div>
										<div id="divReq-utilization_id"></div>
									</div>
								</div>
							</div>

						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a class="accordion-toggle" data-toggle="collapse"
									data-parent="#accordion2" href="#collapse_2"><i
									class="fa fa-user"></i> ข้อมูลผู้รับผิดชอบ/บริษัทผู้ดูแลประจำเครื่อง</a>
							</h4>
						</div>
						<div id="collapse_2" class="panel-collapse in">
							<br>
							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">ชื่อ-นามสกุล :<span
											class="required"></span>
										</label>
										<div class="col-md-4">
											<input id="machine_owner" type="text" value=""
												name="Form1[machine_owner]" class="form-control">
										</div>
										<div id="divReq-machine_owner"></div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">หมายเลขโทรศัทพ์ :<span
											class="required"></span>
										</label>

										<div class="col-md-4">
											<input id="machine_owner_phone" type="text" value=""
												name="Form1[machine_owner_phone]" placeholder=""
												class="form-control">
										</div>
										<div id="divReq-machine_owner_phone"></div>
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
											<input id="machine_owner_email" type="text" value=""
												name="Form1[machine_owner_email]" placeholder=""
												class="form-control">
										</div>
										<div id="divReq-machine_owner_email"></div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a class="accordion-toggle" data-toggle="collapse"
									data-parent="#accordion3" href="#collapse_3"><i
									class="fa fa-info"></i> ข้อมูลอื่น ๆ </a>
							</h4>
						</div>
						<div id="collapse_3" class="panel-collapse in">
							<br>

							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">สถานะการใช้งาน:<span
											class="required">*</span></label>
										<div class="col-md-4">
											<select class="form-control select2"
												onchange="onchangeUsage(this.value)"
												name="Form1[usage_status_id]" id="usage_status_id">
												<option value="0">-- โปรดเลือก --</option>			
												<?php foreach($usage_statuss as $item) {?>
												<option value="<?php echo $item->id?>"><?php echo $item->name?></option>
												<?php }?>
											</select>
										</div>

										<div class="col-md-4" id="divUsageStatusRemark">
											<table>
												<tr>
													<td><input id="usage_status_remark" type="text" value=""
														name="Form1[usage_status_remark]" class="form-control"
														disabled="disabled"> <input id="usage_status_to_department_id"
														type="hidden" value=""
														name="Form1[usage_status_to_department_id]" class="form-control">
													</td>
													<td><a class="btn btn-outline dark" data-toggle="modal"
														href="#modalDepartment"> ค้นหา </a></td>
												</tr>
											</table>
										</div>

										<div id="divReq-usage_status_id"></div>
									</div>
								</div>
							</div>
							
							<div class="row">

								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">สถานที่ติดตั้งเครื่องมือ
											<span class="required">*</span>
										</label>
										<div class="col-md-4">

											<input id="room_name" type="text" value=""
												name="Form1[room_name]" class="form-control"
												disabled="disabled"> <input id="room_id" type="hidden"
												value="" name="Form1[room_id]" class="form-control">
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
							<label class="control-label col-md-4">แนบไฟล์แผนผังห้อง:
							</label>

							<div class="col-md-4">
								<div class="fileinput fileinput-new" data-provides="fileinput">
									<div class="input-group input-large">
										<div
											class="form-control uneditable-input input-fixed input-large"
											data-trigger="fileinput">
											<i class="fa fa-file fileinput-exists"></i>&nbsp; <span
												class="fileinput-filename"></span>
										</div>
										<span class="input-group-addon btn default btn-file"> <span
											class="fileinput-new">Select file </span> <span
											class="fileinput-exists">Change </span> <input type="file"
											name="room_plan[]" id="room_plan" size="25">


										</span> <a href="javascript:;"
											class="input-group-addon btn red fileinput-exists"
											data-dismiss="fileinput">Remove </a>
									</div>
								</div>
							</div>
							<div id="divReq-document_path"></div>
						</div>
					</div>
				</div>

							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">
											วันที่ส่งมอบ/ติดตั้งเครื่องมือ:<span class="required">*</span>
										</label>
										<div class="col-md-2">
											<table>
												<tr>
													<td><select class="form-control select2"
														name="Form1[delivery_date_day]" id="delivery_date_day"
														placeholder="-">
															<option value="0">ไม่ระบุ</option>
												<?php for ($x = 1; $x <= cal_days_in_month(CAL_GREGORIAN, date( "m" ), (date( "Y" )+543)) ; $x++) {?>
												<option value="<?php echo $x?>"
																<?php echo ((date( "d" )) == $x)? 'selected="selected"':'' ?>>
													<?php echo $x;?></option>
												<?php }?>
											</select></td>
													<td><select class="form-control select2"
														name="Form1[delivery_date_month]" id="delivery_date_month"
														onchange="onChangeMonth(this.value)">
															<!-- 												<option>เดือน</option> -->
												<?php for ($x = 1; $x <= 12; $x++) {?>
												<option value="<?php echo $x?>"
																<?php echo ((date( "m" )) == $x)? 'selected="selected"':'' ?>><?php echo CommonUtil::getMonthById($x-1)?></option>
												<?php }?>
											</select></td>
													<td><select class="form-control select2"
														style="width: 80px; !important"
														name="Form1[delivery_date_year]" id="delivery_date_year">
															<!-- 												<option>ปี</option> -->
												<?php for ($x = 2500; $x <= (date( "Y" )+543)+10; $x++) {?>
												<option value="<?php echo $x?>"
																<?php echo ((date( "Y" )+543) == $x)? 'selected="selected"':'' ?>><?php echo $x?></option>
												<?php }?>
											</select></td>
											


												</tr>
											</table>



											<!-- 											<input type="text" value="" id="delivery_date" -->
											<!-- 												name="Form1[delivery_date]" /> -->
										</div>
										<div id="divReq-delivery_date"></div>
									</div>
								</div>
							</div>

							<table class="table table-striped table-hover">
								<thead>
									<tr>
										<th>หน่วยงานผู้ตรวจสอบคุณภาพ</th>
										<th>วันที่ตรวจสอบคุณภาพ</th>
									</tr>
								</thead>
								<tbody>
								<?php
								$index = 1;
								foreach ( $inspectionAgencys as $item ) {
									?>
									<tr>
										<td><input type="checkbox" id="status"
											name="inspection_agency_id[]" value="<?php echo $item->id?>" /> <?php echo $item->name?>
									</td>
										<td><input type="text" value=""
											id="quality_check_date_<?php echo $index;?>"
											name="quality_check_date[<?php echo $item->id?>]" style="width: 80px;" /></td>
									</tr>
									
									<?php $index++; }?>
</tbody>
							</table>





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
								<?php echo CHtml::link(ConfigUtil::getBtnCancelButton(),array('Form1/'),array('class'=>'btn btn-default uppercase'));?>
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
	

		
	var host = 'http://radbase.mahidol/';
// 	var host = 'http://myapps1357.com/radbase';
	
	function onchangeUsage($id){
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
	
	function getFilename($elm) {
		 var fn = $($elm).val();
		 
		 $('#machine_file').val(fn);
			
	}

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
	   
	   function daysInMonth(month,year) {
		    return new Date(year, month, 0).getDate();
		}
		
	function onChangeMonth($m){

		$y = parseInt($("#delivery_date_year option:selected").text())-543;
		  $('#delivery_date_day').empty();
		  for(i=1;i<=daysInMonth($m,$y);i++){
		  	$('#delivery_date_day').append($('<option>').text(i+"").attr('value', i));
		  }

	}
	
    function onchangeCodeUsage($id){
        //fix 27 = other
        if($id == 999){
        	 $('#code_usage_other').show();
        }else{
        	$('#code_usage_other').val('');
        	$('#code_usage_other').hide();

         	$.ajax({
   		     url: host+"/index.php/AjaxRequest/GetMachine",
   		     type: "GET",
   		     data: { 
   	   		     branch_group_id: <?php echo UserLoginUtils::getBranchId() ?>,
   	   		     code_usage_id: $id 
   	   		     },
   		     dataType: "json",
   		     success: function (json) {
   	   		     
   		            $('#rad_machine_id').empty();
   		            $('#rad_machine_id').append($('<option>').text('--โปรดเลือก--').attr('value', '0'));
   		            $.each(json, function(i, obj){
   		                    $('#rad_machine_id').append($('<option>').text(obj.name).attr('value', obj.id));
   		            });
   		     },
   		     error: function (xhr, ajaxOptions, thrownError) {
   				alert('ERROR');
   		     }
    	});

        	
        }
    }

    function onchangeRadMachine($id){
        //fix 27 = other
        if($id == 999){
        	 $('#rad_machine_other').show();
        }else{
        	$('#rad_machine_other').val('');
        	$('#rad_machine_other').hide();
        }
    }
    function onchangeManuFacturer($id){
        //fix 27 = other
        if($id == 999){
        	 $('#maufacturer_other').show();
        }else{
        	$('#maufacturer_other').val('');
        	$('#maufacturer_other').hide();
        }
    }
    function onchangeDealer($id){
        //fix 27 = other
        if($id == 999){
        	 $('#dealer_other').show();
        }else{
        	$('#dealer_other').val('');
        	$('#dealer_other').hide();
        }
    }
    function onchangeUtilization($id){
       //fix 27 = other
       if($id == 999){
       	 $('#utilization_other').show();
       }else{
       	$('#utilization_other').val('');
       	$('#utilization_other').hide();
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
    	$('#code_usage_other').hide();
    	$('#rad_machine_other').hide();
    	$('#maufacturer_other').hide();
    	$('#dealer_other').hide();
     	$('#utilization_other').hide();
     	$('#inspection_agency_other').hide();
     	$('#divUsageStatusRemark').hide();
    	
	    $('.grpOfInt').keypress(function (event) {
            return isNumber(event);
        });
	    $('.grpOfDouble').keypress(function (event) {
            return isDouble(event,this);
        });


// 	    $("#delivery_date_day").select2({
// 	    	  placeholder: "Select a window",
// 	    	  allowClear: true
// 	    	});


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
			
			
	    $( "#license_expire_date" ).datepicker( $.datepicker.regional["th"] ); // Set ภาษาที่เรานิยามไว้ด้านบน
// 	    $( "#license_expire_date" ).datepicker("setDate", new Date()); //Set ค่าวันปัจจุบัน

// 	    $( "#delivery_date" ).datepicker( $.datepicker.regional["th"] ); // Set ภาษาที่เรานิยามไว้ด้านบน
// 	    $( "#delivery_date" ).datepicker("setDate", new Date()); //Set ค่าวันปัจจุบัน

	    $( "#quality_check_date_1" ).datepicker( $.datepicker.regional["th"] ); // Set ภาษาที่เรานิยามไว้ด้านบน
	    $( "#quality_check_date_1" ).datepicker("setDate", new Date()); //Set ค่าวันปัจจุบัน

	    $( "#quality_check_date_2" ).datepicker( $.datepicker.regional["th"] ); // Set ภาษาที่เรานิยามไว้ด้านบน
	    $( "#quality_check_date_2" ).datepicker("setDate", new Date()); //Set ค่าวันปัจจุบัน

	    $( "#quality_check_date_3" ).datepicker( $.datepicker.regional["th"] ); // Set ภาษาที่เรานิยามไว้ด้านบน
	    $( "#quality_check_date_3" ).datepicker("setDate", new Date()); //Set ค่าวันปัจจุบัน

	    $( "#quality_check_date_4" ).datepicker( $.datepicker.regional["th"] ); // Set ภาษาที่เรานิยามไว้ด้านบน
	    $( "#quality_check_date_4" ).datepicker("setDate", new Date()); //Set ค่าวันปัจจุบัน



    	$("#model").attr('maxlength','45');
    	$("#serial_number").attr('maxlength','45');
    	$("#power_unit_id").attr('maxlength','100');
    	$("#license_no").attr('maxlength','45');



    	
   	 
    	$( "#Form1" ).submit(function( event ) {
        	
     		//Validate date format
//     	   	if(!moment($("#license_expire_date").val(), 'DD/MM/YYYY',true).isValid()){
//         		$("#license_expire_date").closest('.form-group').addClass('has-error');
//         		$("#divReq-license_expire_date").html("<span id=\"id-error\" class=\"help-block help-block-error\">รูปแบบวันที่ผิด จะต้องอยู่ในรูปแบบ dd/mm/yyyy เช่น 18/02/2526.</span>");
//         		$("#license_expire_date").focus();
//         		return false;
//             }else{
//             	$("#divReq-license_expire_date").html('');
//             	$("#license_expire_date").closest('.form-group').removeClass('has-error');
//         	}
        	
//     	   	if(!moment($("#delivery_date").val(), 'DD/MM/YYYY',true).isValid()){
//         		$("#delivery_date").closest('.form-group').addClass('has-error');
//         		$("#divReq-delivery_date").html("<span id=\"id-error\" class=\"help-block help-block-error\">รูปแบบวันที่ผิด จะต้องอยู่ในรูปแบบ dd/mm/yyyy เช่น 18/02/2526.</span>");
//         		$("#delivery_date").focus();
//         		return false;
//             }else{
//             	$("#divReq-delivery_date").html('');
//             	$("#delivery_date").closest('.form-group').removeClass('has-error');
//         	}
        	

//     	   	if(!moment($("#quality_check_date").val(), 'DD/MM/YYYY',true).isValid()){
//         		$("#quality_check_date").closest('.form-group').addClass('has-error');
//         		$("#divReq-quality_check_date").html("<span id=\"id-error\" class=\"help-block help-block-error\">รูปแบบวันที่ผิด จะต้องอยู่ในรูปแบบ dd/mm/yyyy เช่น 18/02/2526.</span>");
//         		$("#quality_check_date").focus();
//         		return false;
//             }else{
//             	$("#divReq-quality_check_date").html('');
//             	$("#quality_check_date").closest('.form-group').removeClass('has-error');
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
        	if($("#rad_machine_id").val() == "0"){
        		$("#rad_machine_id").closest('.form-group').addClass('has-error');
        		$("#divReq-rad_machine_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#rad_machine_id").focus();
        		return false;
            }else{
            	$("#divReq-rad_machine_id").html('');
            	$("#rad_machine_id").closest('.form-group').removeClass('has-error');
        	}
        	if($("#model").val().length ==0){
        		$("#model").closest('.form-group').addClass('has-error');
        		$("#divReq-model").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#model").focus();
        		return false;
            }else{
            	$("#divReq-model").html('');
            	$("#model").closest('.form-group').removeClass('has-error');
        	}

        	if($("#serial_number").val().length ==0){
        		$("#serial_number").closest('.form-group').addClass('has-error');
        		$("#divReq-serial_number").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#serial_number").focus();
        		return false;
            }else{
            	$("#divReq-serial_number").html('');
            	$("#serial_number").closest('.form-group').removeClass('has-error');
        	}
        	if($("#power_unit_id").val() == "0"){
        		$("#power_unit_id").closest('.form-group').addClass('has-error');
        		$("#divReq-power_unit_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#power_unit_id").focus();
        		return false;
            }else{
            	$("#divReq-power_unit_id").html('');
            	$("#power_unit_id").closest('.form-group').removeClass('has-error');
        	}
        	if($("#maufacturer_id").val() == "0"){
        		$("#maufacturer_id").closest('.form-group').addClass('has-error');
        		$("#divReq-maufacturer_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#maufacturer_id").focus();
        		return false;
            }else{
            	$("#divReq-maufacturer_id").html('');
            	$("#maufacturer_id").closest('.form-group').removeClass('has-error');
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
        	if($("#use_type_id").val() == "0"){
        		$("#use_type_id").closest('.form-group').addClass('has-error');
        		$("#divReq-use_type_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#use_type_id").focus();
        		return false;
            }else{
            	$("#divReq-use_type_id").html('');
            	$("#use_type_id").closest('.form-group').removeClass('has-error');
        	}
        	if($("#utilization_id").val() == "0"){
        		$("#utilization_id").closest('.form-group').addClass('has-error');
        		$("#divReq-utilization_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#utilization_id").focus();
        		return false;
            }else{
            	$("#divReq-utilization_id").html('');
            	$("#utilization_id").closest('.form-group').removeClass('has-error');
        	}





        	


        	

        	if($("#usage_status_id").val() == "0"){
        		$("#usage_status_id").closest('.form-group').addClass('has-error');
        		$("#divReq-usage_status_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#usage_status_id").focus();
        		return false;
            }else{
            	$("#divReq-usage_status_id").html('');
            	$("#usage_status_id").closest('.form-group').removeClass('has-error');
        	}

        	if($("#room_id").val() == "0"){
        		$("#room_id").closest('.form-group').addClass('has-error');
        		$("#divReq-room_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#room_id").focus();
        		return false;
            }else{
            	$("#divReq-room_id").html('');
            	$("#room_id").closest('.form-group').removeClass('has-error');
        	}
        	
//         	if($("#delivery_date").val().length == 0){
//         		$("#delivery_date").closest('.form-group').addClass('has-error');
//         		$("#divReq-delivery_date").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#delivery_date").focus();
//         		return false;
//             }else{
//             	$("#divReq-delivery_date").html('');
//             	$("#delivery_date").closest('.form-group').removeClass('has-error');
//         	}

//         	if($("#inspection_agency_id").val() == "0"){
//         		$("#inspection_agency_id").closest('.form-group').addClass('has-error');
//         		$("#divReq-inspection_agency_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#inspection_agency_id").focus();
//         		return false;
//             }else{
//             	$("#divReq-inspection_agency_id").html('');
//             	$("#inspection_agency_id").closest('.form-group').removeClass('has-error');
//         	}



//         	if($("#quality_check_date").val().length == 0){
//         		$("#quality_check_date").closest('.form-group').addClass('has-error');
//         		$("#divReq-quality_check_date").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#quality_check_date").focus();
//         		return false;
//             }else{
//             	$("#divReq-quality_check_date").html('');
//             	$("#quality_check_date").closest('.form-group').removeClass('has-error');
//         	}

//         	if($("#machine_owner").val().length == 0){
//         		$("#machine_owner").closest('.form-group').addClass('has-error');
//         		$("#divReq-machine_owner").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#machine_owner").focus();
//         		return false;
//             }else{
//             	$("#divReq-machine_owner").html('');
//             	$("#machine_owner").closest('.form-group').removeClass('has-error');
//         	}
//         	if($("#machine_owner_phone").val().length == 0){
//         		$("#machine_owner_phone").closest('.form-group').addClass('has-error');
//         		$("#divReq-machine_owner_phone").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#machine_owner_phone").focus();
//         		return false;
//             }else{
//             	$("#divReq-machine_owner_phone").html('');
//             	$("#machine_owner_phone").closest('.form-group').removeClass('has-error');
//         	}
//         	if($("#machine_owner_email").val().length == 0){
//         		$("#machine_owner_email").closest('.form-group').addClass('has-error');
//         		$("#divReq-machine_owner_email").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#machine_owner_email").focus();
//         		return false;
//             }else{
//             	$("#divReq-machine_owner_email").html('');
//             	$("#machine_owner_email").closest('.form-group').removeClass('has-error');
//         	}
        	
        	this.submit();
    	});
    });

</script>

</form>