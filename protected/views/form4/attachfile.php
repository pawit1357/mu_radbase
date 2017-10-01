<?php
$positions = MPosition::model ()->findAll ();
?>

<form id="Form1" method="post" enctype="multipart/form-data"
	class="form-horizontal">

	<div class="portlet box blue">
		<div class="portlet-title">
			<div class="caption">
				<?php echo  MenuUtil::getMenuName($_SERVER['REQUEST_URI'])?>

			</div>
			<div class="actions">
			<?php echo CHtml::link('ย้อนกลับ',array('Form4/'),array('class'=>'btn btn-default btn-sm'));?>
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
									class="fa fa-file-code-o"></i> ข้อมูลไฟล์แนบ
								</a>
							</h4>
						</div>

						<div id="collapse_1" class="panel-collapse in">
							<br>

							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">รายละเอียดไฟล์แนบ:<span
											class="required">*</span></label>
										<div class="col-md-4">
											<input id="description" type="text" value="" class="form-control"
												name="Form4[name]">
										</div>
										<div id="divReq-description"></div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">เลือกไฟล์ที่ต้องการอัพโหลด:
										</label>

										<div class="col-md-2">
											<div class="fileinput fileinput-new"
												data-provides="fileinput">
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
														name="file_path[]" id="file_path" size="25">


													</span> <a href="javascript:;"
														class="input-group-addon btn red fileinput-exists"
														data-dismiss="fileinput">Remove </a>

												</div>
											</div>
											<!--                                                 <p class="text-success">อัพโหลดไฟล์ที่ได้ทำการแก้ไขเสร็จแล้ว</p> -->

										</div>
										<div id="divReq-document_path"></div>
									</div>
								</div>
							</div>


						</div>
					</div>


					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a class="accordion-toggle" data-toggle="collapse"
									data-parent="#accordion1" href="#collapse_1"> <i
									class="fa fa-bar-chart"></i> การรายงานผล
								</a>
							</h4>
						</div>

						<div id="collapse_1" class="panel-collapse in">
							<br>
<!-- 							<div class="row"> -->
<!-- 								<div class="col-md-10"> -->
<!-- 									<div class="form-group"> -->
<!-- 										<label class="control-label col-md-4">ผลการประเมิณ:<span -->
<!-- 											class="required">*</span></label> -->
<!-- 										<div class="col-md-4"> -->
<!-- 											<select class="form-control select2" name="Form4[result]" -->
<!-- 												id="result"> -->
<!-- 												<option value="0">-- โปรดเลือก --</option> -->
<!-- 												<option value="1">ปลอดภัย</option> -->
<!-- 												<option value="2">ไม่ปลอดภัย</option> -->
<!-- 											</select> -->
<!-- 										</div> -->
<!-- 										<div id="divReq-result"></div> -->
<!-- 									</div> -->
<!-- 								</div> -->
<!-- 							</div> -->


							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4"> ปี/เดือน  ที่รายงาน:<span
											class="required">*</span></label>
										<div class="col-md-4">
											<select class="form-control select2"
												name="Form4[report_year]" id="report_year">
												<?php for ($x = 2500; $x <= (date( "Y" )+543)+10; $x++) {?>
												<option value="<?php echo $x?>" <?php echo ((date( "Y" )+543) == $x)? 'selected="selected"':'' ?>><?php echo $x?></option>
												<?php }?>
											</select>

										</div>
										<div class="col-md-4">
											<select class="form-control select2"
												name="Form4[report_month]" id="report_month">
												<?php for ($x = 1; $x <= 12; $x++) {?>
												<option value="<?php echo $x?>"  <?php echo ((date( "m" )) == $x)? 'selected="selected"':'' ?> ><?php echo CommonUtil::getMonthById($x-1)?></option>
												<?php }?>
											</select>

										</div>
										<div id="divReq-report_date"></div>
									</div>
								</div>
							</div>

						</div>
					</div>
					

					<!-- END -->
				</div>







				<!-- END FORM-->

			</div>
			<div class="form-actions">
				<div class="row">
					<div class="col-md-10">
						<div class="row">
							<div class="col-md-offset-3 col-md-10">
								<button type="submit" class="btn green uppercase"><?php echo ConfigUtil::getBtnSaveButton();?></button>
								<?php echo CHtml::link(ConfigUtil::getBtnCancelButton(),array('Form4/'),array('class'=>'btn btn-default uppercase'));?>
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
    jQuery(document).ready(function () {
        
// 	    $('.grpOfInt').keypress(function (event) {
//             return isNumber(event);
//         });
// 	    $('.grpOfDouble').keypress(function (event) {
//             return isDouble(event,this);
//         });
    	$("#name").attr('maxlength','250');
//     	$("#hp_10_volume").attr('maxlength','10');
//     	$("#hp_007_volume").attr('maxlength','10');
//     	$("#hp_3_volume").attr('maxlength','10');
//     	$("#rso_license_no").attr('maxlength','20');
//     	$("#result").attr('maxlength','10');

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
			

	    $( "#report_date" ).datepicker( $.datepicker.regional["th"] ); // Set ภาษาที่เรานิยามไว้ด้านบน
	    $( "#report_date" ).datepicker("setDate", new Date()); //Set ค่าวันปัจจุบัน


	    

    	$( "#Form1" ).submit(function( event ) {

//         	if($("#rso_level_id").val()=="0"){
//         		$("#rso_level_id").closest('.form-group').addClass('has-error');
//         		$("#divReq-rso_level_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#rso_level_id").focus();
//         		return false;
//             }else{
//             	$("#divReq-rso_level_id").html('');
//             	$("#rso_level_id").closest('.form-group').removeClass('has-error');
//         	}
        	
        	if($("#description").val().length==0){
        		$("#description").closest('.form-group').addClass('has-error');
        		$("#divReq-description").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#description").focus();
        		return false;
            }else{
            	$("#divReq-description").html('');
            	$("#description").closest('.form-group').removeClass('has-error');
        	}
        	



        	
        	//
//         	if($("#hp_10_volume").val().length == 0){
//         		$("#hp_10_volume").closest('.form-group').addClass('has-error');
//         		$("#divReq-hp_10_volume").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#hp_10_volume").focus();
//         		return false;
//             }else{
//             	$("#divReq-hp_10_volume").html('');
//             	$("#hp_10_volume").closest('.form-group').removeClass('has-error');
//         	}
        	//
        	
//         	if($("#hp_007_volume").val().length == 0){
//         		$("#hp_007_volume").closest('.form-group').addClass('has-error');
//         		$("#divReq-hp_007_volume").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#hp_007_volume").focus();
//         		return false;
//             }else{
//             	$("#divReq-hp_007_volume").html('');
//             	$("#hp_007_volume").closest('.form-group').removeClass('has-error');
//         	}
//
//         	if($("#hp_3_volume").val().length ==0){
//         		$("#hp_3_volume").closest('.form-group').addClass('has-error');
//         		$("#divReq-hp_3_volume").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#hp_3_volume").focus();
//         		return false;
//             }else{
//             	$("#divReq-hp_3_volume").html('');
//             	$("#hp_3_volume").closest('.form-group').removeClass('has-error');
//         	}
//
// //         	if($("#report_date").val().length ==0){
//         		$("#report_date").closest('.form-group').addClass('has-error');
//         		$("#divReq-report_date").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#report_date").focus();
//         		return false;
//             }else{
//             	$("#divReq-report_date").html('');
//             	$("#report_date").closest('.form-group').removeClass('has-error');
//         	}
// //
//         	if($("#report_period_id").val() == "0"){
//         		$("#report_period_id").closest('.form-group').addClass('has-error');
//         		$("#divReq-report_period_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#report_period_id").focus();
//         		return false;
//             }else{
//             	$("#divReq-report_period_id").html('');
//             	$("#report_period_id").closest('.form-group').removeClass('has-error');
//         	}

//
//         	if($("#is_rso_staff").val().length == 0){
//         		$("#is_rso_staff").closest('.form-group').addClass('has-error');
//         		$("#divReq-is_rso_staff").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#is_rso_staff").focus();
//         		return false;
//             }else{
//             	$("#divReq-is_rso_staff").html('');
//             	$("#is_rso_staff").closest('.form-group').removeClass('has-error');
//         	}

//         	if($("#position_id").val() == "0"){
//         		$("#position_id").closest('.form-group').addClass('has-error');
//         		$("#divReq-position_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#position_id").focus();
//         		return false;
//             }else{
//             	$("#divReq-position_id").html('');
//             	$("#position_id").closest('.form-group').removeClass('has-error');
//         	}
// //
//         	if($("#rso_license_no").val().length == 0){
//         		$("#rso_license_no").closest('.form-group').addClass('has-error');
//         		$("#divReq-rso_license_no").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#rso_license_no").focus();
//         		return false;
//             }else{
//             	$("#divReq-rso_license_no").html('');
//             	$("#rso_license_no").closest('.form-group').removeClass('has-error');
//         	}
// //
//         	if($("#rso_license_expire_date").val().length == 0){
//         		$("#rso_license_expire_date").closest('.form-group').addClass('has-error');
//         		$("#divReq-rso_license_expire_date").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#rso_license_expire_date").focus();
//         		return false;
//             }else{
//             	$("#divReq-rso_license_expire_date").html('');
//             	$("#rso_license_expire_date").closest('.form-group').removeClass('has-error');
//         	}

        	this.submit();
    	});







        
    	//initPosition();

    });
    
    function initPosition(){
    	$.ajax({
		     url: host+"/index.php/AjaxRequest/GetPosition",
		     type: "GET",
		     dataType: "json",
		     success: function (json) {
		            $('#position_id').empty();
		            $('#position_id').append($('<option>').text("Select"));
		            $.each(json, function(i, obj){
		                    $('#position_id').append($('<option>').text(obj.name).attr('value', obj.id));
		            });
     	
		     },
		     error: function (xhr, ajaxOptions, thrownError) {
				alert('ERROR');
		     }
    	});
    }
    

</script>

</form>