<form id="Form1" method="post" enctype="multipart/form-data"
	class="form-horizontal">

	<div class="portlet box blue">
		<div class="portlet-title">
			<div class="caption">
				<?php echo  MenuUtil::getMenuName($_SERVER['REQUEST_URI'])?>

			</div>
			<div class="actions">
			<?php echo CHtml::link('ย้อนกลับ',array('Form8/'),array('class'=>'btn btn-default btn-sm'));?>
			</div>
		</div>
		<div class="portlet-body form">
			<div class="form-body">
				<!-- BEGIN FORM-->

<h4 class="form-section">&nbsp;มีคณะกรรมการ</h4>


				<div class="row">
					<div class="col-md-10">
						<div class="form-group">
							<label class="control-label col-md-4">ชื่อชุดคณะกรรมการ:<span
								class="required">*</span></label>
							<div class="col-md-6">
	<input id="name" type="text" value="" class="form-control"
									name="Form8[name]">

						

							</div>
							<div id="divReq-name"></div>
						</div>
					</div>
				</div>
<!-- 				<div class="row"> -->
<!-- 					<div class="col-md-10"> -->
<!-- 						<div class="form-group"> -->
<!-- 							<label class="control-label col-md-4">รายละเอียดคำสั่งแต่งตั้ง(โปรดแนบเอกสาร):<span -->
<!-- 								class="required">*</span></label> -->
<!-- 							<div class="col-md-6"> -->
<!-- 								<input id="name" type="text" value="" class="form-control" -->
<!-- 									name="Form8[name]"> -->
<!-- 							</div> -->
<!-- 							<div id="divReq-name"></div> -->
<!-- 						</div> -->
<!-- 					</div> -->
<!-- 				</div> -->



				<div class="row">
					<div class="col-md-10">
						<div class="form-group">
							<label class="control-label col-md-4">รายละเอียดคำสั่งแต่งตั้ง(โปรดแนบเอกสาร): </label>

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
											name="file_path[]" id="document_path" size="25">


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

<h4 class="form-section">&nbsp;ไม่มีคณะกรรมการ</h4>

				<div class="row">
					<div class="col-md-10">
						<div class="form-group">
							<label class="control-label col-md-4">เนื่องจาก โปรดระบุ
							</label>
							<div class="col-md-4">
								<textarea rows="5" cols="70" id="no_committee_reason"
									name="Form8[no_committee_reason]"></textarea>

							</div>
							<div id="divReq-no_committee_reason"></div>
						</div>
					</div>
				</div>
			</div>

			<div class="form-actions">
				<div class="row">
					<div class="col-md-9">
						<div class="row">
							<div class="col-md-offset-3 col-md-9">
								<button type="submit" class="btn green uppercase"><?php echo ConfigUtil::getBtnSaveButton();?></button>
								<?php echo CHtml::link(ConfigUtil::getBtnCancelButton(),array('Form8/'),array('class'=>'btn btn-default uppercase'));?>
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
	    $('.grpOfDouble').keypress(function (event) {
            return isDouble(event,this);
        });
        
    	$("#name").attr('maxlength','200');
    	$("#file_path").attr('maxlength','100');



    	$( "#Form1" ).submit(function( event ) {
        	
//         	if($("#name").val().length == 0){
//         		$("#name").closest('.form-group').addClass('has-error');
//         		$("#divReq-name").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
//         		$("#name").focus();
//         		return false;
//             }else{
//             	$("#divReq-name").html('');
//             	$("#name").closest('.form-group').removeClass('has-error');
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


        	this.submit();
    	});
    });
</script>

</form>