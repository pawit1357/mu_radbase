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
			<?php echo CHtml::link('ย้อนกลับ',array('MRoom/'),array('class'=>'btn btn-default btn-sm'));?>
			</div>
		</div>
		<div class="portlet-body form">
			<div class="form-body">
				<!-- BEGIN FORM-->
				<div class="row">
					<div class="col-md-10">
						<div class="form-group">
							<label class="control-label col-md-3">รหัส:<span class="required">*</span></label>
							<div class="col-md-6">
								<input id="id" type="text" value="<?php echo $data->id;?>"
									class="grpOfInt form-control" name="MRoom[id]" readonly>
							</div>
							<div id="divReq-id"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-10">
						<div class="form-group">
							<label class="control-label col-md-3">ชื่อห้อง:<span class="required">*</span></label>
							<div class="col-md-6">
								<input id="name" type="text" value="<?php echo $data->name;?>"
									class="form-control" name="MRoom[name]">
							</div>
							<div id="divReq-name"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-10">
						<div class="form-group">
							<label class="control-label col-md-3">เลขห้อง:<span
								class="required">*</span></label>
							<div class="col-md-6">
								<input id="number" type="text"
									value="<?php echo $data->number;?>" class="form-control"
									name="MRoom[number]">
							</div>
							<div id="divReq-number"></div>
						</div>
					</div>
				</div>
												<div class="row">
					<!-- 				</div> -->
					<!-- 				<div class="row"> -->
					<div class="col-md-10">
						<div class="form-group">
							<label class="control-label col-md-3">คณะ:<span
								class="required">*</span></label>
							<div class="col-md-6">
								<input id="building_id" type="text" value="<?php echo $data->fac?>"
									class="form-control" name="MRoom[fac]">
							</div>
							<div id="divReq-building_id"></div>
						</div>
					</div>

				</div>
				<div class="row">
					<!-- 				</div> -->
					<!-- 				<div class="row"> -->
					<div class="col-md-10">
						<div class="form-group">
							<label class="control-label col-md-3">อาคาร:<span
								class="required">*</span></label>
							<div class="col-md-6">
								<input id="building_id" type="text"
									value="<?php echo $data->building_id;?>" class="form-control"
									name="MRoom[building_id]">
							</div>
							<div id="divReq-building_id"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<!-- 				</div> -->
					<!-- 				<div class="row"> -->
					<div class="col-md-10">
						<div class="form-group">
							<label class="control-label col-md-3">ชั้น:<span class="required">*</span></label>
							<div class="col-md-6">
								<input id="floor" type="text" value="<?php echo $data->floor?>" class="form-control"
									name="MRoom[floor]">
							</div>
							<div id="divReq-building_id"></div>
						</div>
					</div>

				</div>
				<div class="row">
					<div class="col-md-10">
						<div class="form-group">
							<label class="control-label col-md-3">เลือกไฟล์ที่ต้องการอัพโหลด:
							</label>

							<div class="col-md-3">
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
											name="room_plan" id="room_plan" size="25">


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
				<!-- END FORM-->


				<!-- END FORM-->

			</div>
			<div class="form-actions">
				<div class="row">
					<div class="col-md-9">
						<div class="row">
							<div class="col-md-offset-3 col-md-9">
								<button type="submit" class="btn green uppercase"><?php echo ConfigUtil::getBtnSaveButton();?></button>
								<?php echo CHtml::link(ConfigUtil::getBtnCancelButton(),array('MRoom/'),array('class'=>'btn btn-default uppercase'));?>
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
        	
        	if($("#number").val().length==0){
        		$("#number").closest('.form-group').addClass('has-error');
        		$("#divReq-number").html("<span id=\"name-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#number").focus();
        		return false;
                }else{
            	$("#divReq-number").html('');
        		$("#number").closest('.form-group').removeClass('has-error');
        	}
        	if($("#building_id").val().length==0){
        		$("#building_id").closest('.form-group').addClass('has-error');
        		$("#divReq-building_id").html("<span id=\"name-error\" class=\"help-block help-block-error\">This field is required.</span>");
        		$("#building_id").focus();
        		return false;
                }else{
            	$("#divReq-building_id").html('');
        		$("#building_id").closest('.form-group').removeClass('has-error');
        	}
        	this.submit();
    	});
    });
    
//     function initMRoom(){
//     	$.ajax({
// 		     url: host+"/index.php/AjaxRequest/GetMRoom",
// 		     type: "GET",
// 		     dataType: "json",
// 		     success: function (json) {
// 		            $('#MRoom_id').empty();
// 		            $('#MRoom_id').append($('<option>').text("Select"));
// 		            $.each(json, function(i, obj){
// 		                    $('#MRoom_id').append($('<option>').text(obj.name).attr('value', obj.id));
// 		            });
     	
// 		     },
// 		     error: function (xhr, ajaxOptions, thrownError) {
// 				alert('ERROR');
// 		     }
//     	});
//     }
    
    
</script>

</form>