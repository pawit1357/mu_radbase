<?php
$branchs = MBranch::model()->findAll(); // รหัสประเภทการใช้งาน
$criCodeUsage = new CDbCriteria();
$criCodeUsage->condition = " is_rad_machine like '%1%' ";

$code_usages = MCodeUsage::model()->findAll($criCodeUsage); // รหัสประเภทการใช้งาน
$power_units = MPowerUnit::model()->findAll(); // กำลัง/พลังงานสูงสุด
?>
<form id="Form1" method="post" enctype="multipart/form-data"
	class="form-horizontal">
	<!-- 	<div class="alert alert-danger display-hide"> -->
	<!-- 		<button class="close" data-close="alert"></button> -->
	<!-- 		You have some form errors. Please check below. -->
	<!-- 	</div> -->
	<!-- 	<div class="alert alert-success display-hide"> -->
	<!-- 		<button class="close" data-close="alert"></button> -->
	<!-- 		Your form validation is successful! -->
	<!-- 	</div> -->

	<div class="portlet box blue">
		<div class="portlet-title">
			<div class="caption">
			<?php echo  MenuUtil::getMenuName($_SERVER['REQUEST_URI'])?>

			</div>
			<div class="actions">
			<?php echo CHtml::link('ย้อนกลับ',array('MRadMachine/'),array('class'=>'btn btn-default btn-sm'));?>
			</div>
		</div>
		<div class="portlet-body form">
			<div class="form-body">
				<!-- BEGIN FORM-->
				<div class="row">
					<div class="col-md-10">
						<div class="form-group">
							<label class="control-label col-md-4">รหัส:<span class="required">*</span></label>
							<div class="col-md-6">
								<input id="id" type="text"
									value="<?php echo MRadMachine::getMax();?>"
									class="grpOfInt form-control" name="MRadMachine[id]" readonly>
							</div>
							<div id="divReq-id"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-10">
						<div class="form-group">
							<label class="control-label col-md-4">ชื่อ:<span class="required">*</span></label>
							<div class="col-md-6">
								<input id="name" type="text" value="" class="form-control"
									name="MRadMachine[name]">
							</div>
							<div id="divReq-name"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-10">
						<div class="form-group">
							<label class="control-label col-md-4">ลำดับเมนู:<span
								class="required"></span></label>
							<div class="col-md-6">
								<input id="seq" type="text" value="0"
									class="form-control grpOfInt" name="MRadMachine[seq]">
							</div>
							<div id="divReq-seq"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-10">
						<div class="form-group">
							<label class="control-label col-md-4">กำลัง/พลังงานสูงสุด:<span
								class="required">*</span></label>
							<div class="col-md-4">

								<select class="form-control select2"
									name="MRadMachine[power_unit_id]" id="power_unit_id">
									<option value="0">-- โปรดเลือก --</option>
			<?php foreach($power_units as $item) {?>
			<option value="<?php echo $item->id?>"><?php echo  $item->name_en?></option>
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
							<label class="control-label col-md-4">รหัสประเภทการใช้งาน: <span
								class="required">*</span>
							</label>
							<div class="col-md-6">

								<select class="form-control select2"
									name="MRadMachine[code_usage_id]" id="code_usage_id" onchange="onchangeCodeUsage(this.value)">
									<option value="0">-- โปรดเลือก --</option>
			<?php foreach($code_usages as $item) {?>
			<option value="<?php echo $item->id?>"><?php echo sprintf('%02d', $item->code).'-'. $item->name?></option>
			<?php }?>
			</select>

							</div>

							<div id="divReq-code_usage_id"></div>
						</div>
					</div>

				</div>
				<div class="row" id="div-code_usage_other">
					<div class="col-md-10">
						<div class="form-group">
							<label class="control-label col-md-4">อื่น ๆ ระบุ: <span
								class="required">*</span>
							</label>
							<div class="col-md-6">
								<input id="code_usage_other" type="text" value=""
									class="form-control" name="MRadMachine[code_usage_other]">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-10">
						<div class="form-group">
							<label class="control-label col-md-4"> ใช้สำหรับสาขา:<span
								class="required"> * </span>
							</label>
							<div class="radio-list">
								<label class="radio-inline">
								<?php foreach($branchs as $item) {?>
									 <input type="checkbox" id="status" name="branch_group_id[]"
									value="<?php echo $item->id?>" /> <?php echo $item->name?>
								
								<?php }?>
									</label>

							</div>
						</div>
					</div>
				</div>

				<!-- END FORM-->

			</div>
			<div class="form-actions">
				<div class="row">
					<div class="col-md-9">
						<div class="row">
							<div class="col-md-offset-3 col-md-9">
								<button type="submit" class="btn green uppercase"><?php echo ConfigUtil::getBtnSaveButton();?></button>
								<?php echo CHtml::link(ConfigUtil::getBtnCancelButton(),array('MRadMachine/'),array('class'=>'btn btn-default uppercase'));?>
							</div>
						</div>
					</div>
					<div class="col-md-9"></div>
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
        
	    //init
	    onchangeCodeUsage($('#code_usage_id').val());


	    



    	
	    $('.grpOfInt').keypress(function (event) {
            return isNumber(event);
        });
        
   	 $("#id").attr('maxlength','3');
	 $("#name").attr('maxlength','200');
    	$( "#Form1" ).submit(function( event ) {

    		   var countBranch =  $('input[name="branch_group_id[]"]:checked').length;
    		   
    		  if(countBranch==0){
        		  alert('โปรดระบุ (ประเภทการใช้งาน)');
				return false;
        	  }
    		   
        	if($("#id").val().length==0){
        		$("#id").closest('.form-group').addClass('has-error');
        		$("#divReq-id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		return false;
            }else{
            	$("#divReq-id").html('');
            	$("#id").closest('.form-group').removeClass('has-error');
        		
        	}
        	if($("#name").val().length==0){
        		$("#name").closest('.form-group').addClass('has-error');
        		$("#divReq-name").html("<span id=\"name-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		return false;
                }else{
            	$("#divReq-name").html('');
        		$("#name").closest('.form-group').removeClass('has-error');
        		
        	}
        	if($("#seq").val().length==0){
        		$("#seq").closest('.form-group').addClass('has-error');
        		$("#divReq-seq").html("<span id=\"name-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		return false;
                }else{
            	$("#divReq-seq").html('');
        		$("#seq").closest('.form-group').removeClass('has-error');
        		
        	}
        	this.submit();
    	});
    });
    
    function onchangeCodeUsage($id){
        if($id == 34){
        	 $('#div-code_usage_other').show();
        }else{
        	$('#code_usage_other').val('');
        	$('#div-code_usage_other').hide();
        }
    }
//     function initDepartment(){
//     	$.ajax({
// 		     url: host+"/index.php/AjaxRequest/GetDepartment",
// 		     type: "GET",
// 		     dataType: "json",
// 		     success: function (json) {
// 		            $('#department_id').empty();
// 		            $('#department_id').append($('<option>').text("Select"));
// 		            $.each(json, function(i, obj){
// 		                    $('#department_id').append($('<option>').text(obj.name).attr('value', obj.id));
// 		            });
     	
// 		     },
// 		     error: function (xhr, ajaxOptions, thrownError) {
// 				alert('ERROR');
// 		     }
//     	});
//     }
    
    
</script>

</form>