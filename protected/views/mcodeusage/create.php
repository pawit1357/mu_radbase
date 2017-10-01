<?php
$branchs = MBranch::model ()->findAll (); // รหัสประเภทการใช้งาน
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
			<?php echo CHtml::link('ย้อนกลับ',array('MCodeUsage/'),array('class'=>'btn btn-default btn-sm'));?>
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
								<input id="id" type="hidden"
									value="<?php echo MCodeUsage::getMax();?>"
									class="grpOfInt form-control" name="MCodeUsage[id]" readonly>
											
								<input id="id" type="text" value="" class="form-control" name="MCodeUsage[code]">
							</div>
							<div id="divReq-id"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-10">
						<div class="form-group">
							<label class="control-label col-md-4">ชื่อประเภท:<span
								class="required">*</span></label>
							<div class="col-md-6">
								<input id="name" type="text" value="" class="form-control"
									name="MCodeUsage[name]">
							</div>
							<div id="divReq-name"></div>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-10">
						<div class="form-group">
							<label class="control-label col-md-4"> ใช้ใน:<span
								class="required"> * </span>
							</label>
							<div class="radio-list">
								<label class="radio-inline"> 
								<input type="checkbox" id="status"
									name="is_rad_machine[]" value="1"
									 /> เครื่องกำเนิดรังสี
						
								 <input type="checkbox"
									id="status" name="is_rad_machine[]" value="2"
									 />วัสดุกัมมันตรังสี
								</label>

							</div>
						</div>
					</div>
				</div>

				<div class="row" id="divIsSealSource">
					<div class="col-md-10">
						<div class="form-group">
							<label class="control-label col-md-4"> วัสดุกัมมันตรังสี(ชนิด):<span
								class="required"> * </span>
							</label>
							<div class="radio-list">
								<label class="radio-inline"> 
<!-- 								<input type="checkbox" id="status" -->
<!-- 									name="is_sealsource[]" value="0" checked="checked" /> ไม่มีระบุ -->
									<input type="checkbox" id="status"
									name="is_sealsource[]" value="1" /> ปิดผนึก
								<input type="checkbox"
									id="status" name="is_sealsource[]" value="2" />ไม่ปิดผนึก
								</label>

							</div>
						</div>
					</div>
				</div>
				<div class="row" id="divIsSealSource">
					<div class="col-md-10">
						<div class="form-group">
							<label class="control-label col-md-4"> ใช้สำหรับสาขา:<span
								class="required"> * </span>
							</label>
							<div class="radio-list">
							<label class="radio-inline">
								<?php foreach($branchs as $item) {?>
									 <input type="checkbox" id="status"
									name="branch_group_id[]" value="<?php echo $item->id?>" /> <?php echo $item->name?>
								
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
								<?php echo CHtml::link(ConfigUtil::getBtnCancelButton(),array('MCodeUsage/'),array('class'=>'btn btn-default uppercase'));?>
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
        
//     	$('#divIsSealSource').hide();
    	
	    $('.grpOfInt').keypress(function (event) {
            return isNumber(event);
        });
        
   	 $("#id").attr('maxlength','3');
	 $("#name").attr('maxlength','200');
    	$( "#Form1" ).submit(function( event ) {
        	
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
        	this.submit();
    	});
		


    });

//     function onChangeRadOrMachine($id){

//         switch($id.value){
//         case "1":
//           	if($id.checked){
//         		$('#divIsSealSource').hide();
//         	}
//             break;
//         case "2":
//         	if($id.checked){
//         		$('#divIsSealSource').show();
//         	}else{
//         		$('#divIsSealSource').hide();
//             }
//             break;
//         }
        
//     	$.ajax({
// 		     url: host+"/labbase/index.php/AjaxRequest/GetAmphur",
// 		     type: "GET",
// 		     data: { province_id: $id },
// 		     dataType: "json",
// 		     success: function (json) {
// 		            $('#dep_amphur_id').empty();
// 		            $('#dep_amphur_id').append($('<option>').text('--โปรดเลือก--').attr('value', '0'));
// 		            $.each(json, function(i, obj){
// 		                    $('#dep_amphur_id').append($('<option>').text(obj.AMPHUR_NAME).attr('value', obj.AMPHUR_ID));
// 		            });
// 		     },
// 		     error: function (xhr, ajaxOptions, thrownError) {
// 				alert('ERROR');
// 		     }
//     	});
//     }
    
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