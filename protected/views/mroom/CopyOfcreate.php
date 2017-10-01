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
				<i class="fa fa-plus"></i> บันทึกห้อง

			</div>
			<div class="actions">
			<?php echo CHtml::link('ย้อนกลับ',array('MRoom/'),array('class'=>'btn btn-default btn-sm'));?>
			</div>
		</div>
		<div class="portlet-body form">
			<div class="form-body">
				<!-- BEGIN FORM-->
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-3">รหัส:<span class="required">*</span></label>
							<div class="col-md-6">
								<input id="id" type="text" value="<?php echo $data->id;?>"
									class="grpOfInt form-control" name="MRoom[id]">
							</div>
							<div id="divReq-id"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-3">ชื่อ:<span class="required">*</span></label>
							<div class="col-md-6">
								<input id="name" type="text" value="<?php echo $data->name;?>"
									class="form-control" name="MRoom[name]">
							</div>
							<div id="divReq-name"></div>
						</div>
					</div>
				</div>
				
				<div class="row">
<!-- 				</div> -->
<!-- 				<div class="row"> -->
					<div class="col-md-6">
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
				<!-- END FORM-->
				
				
				<h4>ข้อมูลที่ตั้ง</h4>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-3">ที่ตั้งเลขที่:<span
								class="required">*</span></label>
							<div class="col-md-6">
								<input id="location_addr" type="text"
									value="<?php echo $data->location_addr;?>" class="form-control"
									name="MRoom[location_addr]">
							</div>
							<div id="divReq-location_addr"></div>
						</div>
					</div>
<!-- 				</div> -->
<!-- 				<div class="row"> -->
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-3">หมู่:<span class="required">*</span></label>
							<div class="col-md-6">
								<input id="location_moo" type="text"
									value="<?php echo $data->location_moo;?>" class="form-control"
									name="MRoom[location_moo]">
							</div>
							<div id="divReq-location_moo"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-3">ตรอก/ซอย:<span
								class="required">*</span></label>
							<div class="col-md-6">
								<input id="location_soi" type="text"
									value="<?php echo $data->location_soi;?>" class="form-control"
									name="MRoom[location_soi]">
							</div>
							<div id="divReq-location_soi"></div>
						</div>
					</div>
<!-- 				</div> -->
<!-- 				<div class="row"> -->
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-3">ถนน:<span class="required">*</span></label>
							<div class="col-md-6">
								<input id="location_road" type="text"
									value="<?php echo $data->location_road;?>" class="form-control"
									name="MRoomlocation_road]">
							</div>
							<div id="divReq-location_road"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-3">แขวง/ตำบล:<span
								class="required">*</span></label>
							<div class="col-md-6">
								<input id="location_tambon_id" type="text"
									value="<?php echo $data->location_tambon_id;?>"
									class="form-control" name="MRoom[location_tambon_id]">
							</div>
							<div id="divReq-location_tambon_id"></div>
						</div>
					</div>
<!-- 				</div> -->
<!-- 				<div class="row"> -->
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-3">เขต/อำเภอ:<span
								class="required">*</span></label>
							<div class="col-md-6">
								<input id="location_amphur_id" type="text"
									value="<?php echo $data->location_amphur_id;?>"
									class="form-control" name="MRoom[location_amphur_id]">
							</div>
							<div id="divReq-location_amphur_id"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-3">จังหวัด:<span
								class="required">*</span></label>
							<div class="col-md-6">
								<input id="location_province_id" type="text"
									value="<?php echo $data->location_province_id;?>"
									class="form-control" name="MRoom[location_province_id]">
							</div>
							<div id="divReq-location_province"></div>
						</div>
					</div>
<!-- 				</div> -->
<!-- 				<div class="row"> -->
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-3">รหัสไปรษณี:<span
								class="required">*</span></label>
							<div class="col-md-6">
								<input id="location_zipcode" type="text"
									value="<?php echo $data->location_zipcode;?>"
									class="form-control" name="MRoom[location_zipcode]">
							</div>
							<div id="divReq-location_zipcode"></div>
						</div>
					</div>
				</div>


				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-3">โทรศัพท์:<span
								class="required">*</span></label>
							<div class="col-md-6">
								<input id="location_tel" type="text"
									value="<?php echo $data->location_tel;?>" class="form-control"
									name="MRoom[location_tel]">
							</div>
							<div id="divReq-location_tel"></div>
						</div>
					</div>
<!-- 				</div> -->
<!-- 				<div class="row"> -->
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-3">โทรสาร:<span
								class="required">*</span></label>
							<div class="col-md-6">
								<input id="location_fax" type="text"
									value="<?php echo $data->location_fax;?>" class="form-control"
									name="MRoom[location_fax]">
							</div>
							<div id="divReq-location_fax"></div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-3">อีเมล์:<span
								class="required">*</span></label>
							<div class="col-md-6">
								<input id="location_email" type="text"
									value="<?php echo $data->location_email;?>"
									class="form-control" name="MRoom[location_email]">
							</div>
							<div id="divReq-location_email"></div>
						</div>
					</div>
<!-- 				</div> -->
<!-- 				<div class="row"> -->
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-3">รัหสหน่วยงานเลขที่:<span
								class="required">*</span></label>
							<div class="col-md-6">
								<input id="location_MRoom_number" type="text"
									value="<?php echo $data->location_MRoom_number;?>"
									class="form-control"
									name="MRoom[location_MRoom_number]">
							</div>
							<div id="divReq-location_MRoom_number"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-3">รัหสหน่วยงานเลขที่:<span
								class="required">*</span></label>
							<div class="col-md-6">
								<input id="location_license_no" type="text"
									value="<?php echo $data->location_license_no;?>"
									class="form-control" name="MRoom[location_license_no]">
							</div>
							<div id="divReq-location_license_no"></div>
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
								<button type="submit" class="btn green uppercase">Save</button>
								<button type="reset" class="btn default uppercase">Cencel</button>
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
	 $("#name").attr('maxlength','45');
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