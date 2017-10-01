<?php 
$branchs = MBranch::model ()->findAll ();
$mfacs = MFaculty::model ()->findAll ();
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
			<?php echo CHtml::link('ย้อนกลับ',array('MDepartment/'),array('class'=>'btn btn-default btn-sm'));?>
			</div>
		</div>
		<div class="portlet-body form">
			<div class="form-body">
				<!-- BEGIN FORM-->
				<h4>ข้อมูลหน่วยงาน</h4>
				
				<div class="row">
					<div class="col-md-10">
						<div class="form-group">
							<label class="control-label col-md-4">รหัส:<span class="required">*</span></label>
							<div class="col-md-6">
								<input id="id" type="text" value="<?php echo $data->id;?>"
									class="grpOfInt form-control" name="MDepartment[id]" readonly>
							</div>
							<div id="divReq-id"></div>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-10">
						<div class="form-group">
							<label class="control-label col-md-4">คณะ /ส่วนงาน<span
								class="required">*</span></label>
							<div class="col-md-6">
								<select class="form-control select2"
									name="MDepartment[faculty_id]" id="faculty_id">
									<option value="0">-- โปรดเลือก --</option>
			<?php foreach($mfacs as $item) {?>
			<option value="<?php echo $item->id?>" <?php echo ($item->id == $data->faculty_id? 'selected="selected"':'')?>><?php echo $item->name ?></option>
			<?php }?>
								</select>
							</div>
							<div id="divReq-faculty_id"></div>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-10">
						<div class="form-group">
							<label class="control-label col-md-4">ภาควิชา:<span
								class="required">*</span></label>
							<div class="col-md-6">
								<input id="name" type="text" value="<?php echo $data->name;?>"
									class="form-control" name="MDepartment[name]">
							</div>
							<div id="divReq-name"></div>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-10">
						<div class="form-group">
							<label class="control-label col-md-4">สาขา:<span
								class="required">*</span></label>
							<div class="col-md-6">
								<input id="branch_id" type="text" value="<?php echo $data->branch_id;?>"
									class="form-control" name="MDepartment[branch_id]">
							</div>
							<div id="divReq-name"></div>
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
								<?php echo CHtml::link(ConfigUtil::getBtnCancelButton(),array('MDepartment/'),array('class'=>'btn btn-default uppercase'));?>
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
	    $('.grpOfInt').keypress(function (event) {
            return isNumber(event);
        });
   	 $("#id").attr('maxlength','3');
	 $("#name").attr('maxlength','200');
    	$( "#Form1" ).submit(function( event ) {
        	
        	if($("#id").val().length==0){
        		$("#id").closest('.form-group').addClass('has-error');
        		$("#divReq-id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#id").focus();
        		return false;
            }else{
            	$("#divReq-id").html('');
            	$("#id").closest('.form-group').removeClass('has-error');
        		
        	}
        	if($("#name").val().length==0){
        		$("#name").closest('.form-group').addClass('has-error');
        		$("#divReq-name").html("<span id=\"name-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#name").focus();
        		return false;
                }else{
            	$("#divReq-name").html('');
        		$("#name").closest('.form-group').removeClass('has-error');
        	}
        	if($("#faculty_id").val().length==0){
        		$("#faculty_id").closest('.form-group').addClass('has-error');
        		$("#divReq-faculty_id").html("<span id=\"name-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#faculty_id").focus();
        		return false;
                }else{
            	$("#divReq-faculty_id").html('');
        		$("#faculty_id").closest('.form-group').removeClass('has-error');
        	}
        	if($("#branch_id").val().length==0){
        		$("#branch_id").closest('.form-group').addClass('has-error');
        		$("#divReq-branch_id").html("<span id=\"name-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#branch_id").focus();
        		return false;
                }else{
            	$("#divReq-branch_id").html('');
        		$("#branch_id").closest('.form-group').removeClass('has-error');
        	}

        	
        	this.submit();
    	});
    });
    
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