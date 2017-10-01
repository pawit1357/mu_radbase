<?php
$departments = MDepartment::model ()->findAll ();
?>
<form id="Form1" method="post" enctype="multipart/form-data"
	class="form-horizontal">

	<div class="portlet box blue">
		<div class="portlet-title">
			<div class="caption">
				
				<?php echo  MenuUtil::getMenuName($_SERVER['REQUEST_URI'])?>

			</div>
			<div class="actions">
			<?php echo CHtml::link('ย้อนกลับ',array('Form7/'),array('class'=>'btn btn-default btn-sm'));?>
			</div>
		</div>
		<div class="portlet-body form">
			<div class="form-body">
				<!-- BEGIN FORM-->

				<div class="help-block">
					ดาวน์โหลดไฟล์ตัวอย่าง <a
						href="<?php echo  ConfigUtil::getAppName().'/docs/sample_file_02.xlsx'?>"
						target="_blank"> ดาวน์โหลด</a>
				</div>
				<div class="panel-group accordion" id="accordion1">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a class="accordion-toggle" data-toggle="collapse"
									data-parent="#accordion1" href="#collapse_1"> <i
									class="fa fa-file-code-o"></i> ข้อมูลไฟล์ (เฉพาะไฟล์
									*.xls,*.xlsx)
								</a>
							</h4>
						</div>

						<div id="collapse_1" class="panel-collapse in">
							<br>
							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label class="control-label col-md-4">เลือกไฟล์
											: </label>

										<div class="col-md-4">
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
														name="fileupload1" id="fileupload1" size="25">


													</span> <a href="javascript:;"
														class="input-group-addon btn red fileinput-exists"
														data-dismiss="fileinput">Remove </a>

												</div>
											</div>
											<!-- <p class="text-success">อัพโหลดไฟล์ที่ได้ทำการแก้ไขเสร็จแล้ว</p> -->

										</div>
										<div id="divReq-document_path"></div>
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
								<?php echo CHtml::link(ConfigUtil::getBtnCancelButton(),array('Form7/'),array('class'=>'btn btn-default uppercase'));?>
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
        
	    $('.grpOfInt').keypress(function (event) {
            return isNumber(event);
        });
	    $('.grpOfDouble').keypress(function (event) {
            return isDouble(event,this);
        });
//     	$("#name").attr('maxlength','100');
//     	$("#course_id").attr('maxlength','200');


// 		 $.datepicker.regional['th'] ={
// 			        changeMonth: true,
// 			        changeYear: true,
// 			        //defaultDate: GetFxupdateDate(FxRateDateAndUpdate.d[0].Day),
// 			        yearOffSet: 543,
// 			        showOn: "button",
// 			        buttonImage: '/images/calendar.gif',
// 			        buttonImageOnly: true,
// 			        dateFormat: 'dd/mm/yy',
// 			        dayNames: ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'],
// 			        dayNamesMin: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'],
// 			        monthNames: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
// 			        monthNamesShort: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'],
// 			        constrainInput: true,
			       
// 			        prevText: 'ก่อนหน้า',
// 			        nextText: 'ถัดไป',
// 			        yearRange: '-20:+20',
// 			        buttonText: 'เลือก',
			      
// 			    };
		    
// 			$.datepicker.setDefaults($.datepicker.regional['th']);
			
// 	    $( "#training_date" ).datepicker( $.datepicker.regional["th"] ); // Set ภาษาที่เรานิยามไว้ด้านบน
// 	    $( "#training_date" ).datepicker("setDate", new Date()); //Set ค่าวันปัจจุบัน

	    

  	$( "#Form1" ).submit(function( event ) {

 		//Validate date format
// 	   	if(!moment($("#training_date").val(), 'DD/MM/YYYY',true).isValid()){
//     		$("#training_date").closest('.form-group').addClass('has-error');
//     		$("#divReq-training_date").html("<span id=\"id-error\" class=\"help-block help-block-error\">รูปแบบวันที่ผิด จะต้องอยู่ในรูปแบบ dd/mm/yyyy เช่น 18/02/2526.</span>");
//     		$("#training_date").focus();
//     		return false;
//         }else{
//         	$("#divReq-training_date").html('');
//         	$("#training_date").closest('.form-group').removeClass('has-error');
//     	}

    	
//       if(isNullOrEmpty($('#fileupload1').val())){
          
//       	if($("#name").val().length==0){
//       		$("#name").closest('.form-group').addClass('has-error');
//       		$("#divReq-name").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//       		$("#name").focus();
//       		return false;
//           }else{
//           	$("#divReq-name").html('');
//           	$("#name").closest('.form-group').removeClass('has-error');
//       	}
//       	//
//       	if($("#course_id").val() == "0"){
//       		$("#course_id").closest('.form-group').addClass('has-error');
//       		$("#divReq-course_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//       		$("#course_id").focus();
//       		return false;
//           }else{
//           	$("#divReq-course_id").html('');
//           	$("#course_id").closest('.form-group').removeClass('has-error');
//       	}
      	
//       	if($("#department_type_id").val() == "0"){
//       		$("#department_type_id").closest('.form-group').addClass('has-error');
//       		$("#divReq-department_type_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//       		$("#department_type_id").focus();
//       		return false;
//           }else{
//           	$("#divReq-department_type_id").html('');
//           	$("#department_type_id").closest('.form-group').removeClass('has-error');
//       	}
//       	if($("#department_id").val() == "0"){
//       		$("#department_id").closest('.form-group').addClass('has-error');
//       		$("#divReq-department_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//       		$("#department_id").focus();
//       		return false;
//           }else{
//           	$("#divReq-department_id").html('');
//           	$("#department_id").closest('.form-group').removeClass('has-error');
//       	}

//       	if($("#training_date").val().length ==0){
//       		$("#training_date").closest('.form-group').addClass('has-error');
//       		$("#divReq-training_date").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//       		$("#training_date").focus();
//       		return false;
//           }else{
//           	$("#divReq-training_date").html('');
//           	$("#training_date").closest('.form-group').removeClass('has-error');
//       	}
//       }
      	this.submit();
  	});



      
//     	initDepartment();

    });
    function isNullOrEmpty( s ) 
    {
        return ( s == null || s === "" );
    }
//     function initDepartment(){
//     	$.ajax({
// 		     url: host+"/index.php/AjaxRequest/GetDepartment",
// 		     type: "GET",
// 		     dataType: "json",
// 		     success: function (json) {
// 		            $('#department_type_id').empty();
// 		            $('#department_type_id').append($('<option>').text("Select"));
// 		            $.each(json, function(i, obj){
// 		                    $('#department_type_id').append($('<option>').text(obj.name).attr('value', obj.id));
// 		            });
     	
// 		     },
// 		     error: function (xhr, ajaxOptions, thrownError) {
// 				alert('ERROR');
// 		     }
//     	});
//     }
    
    
</script>

</form>