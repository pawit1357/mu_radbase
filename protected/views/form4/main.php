<form id="Form1" method="POST" enctype="multipart/form-data"
	class="form-horizontal">


	<div class="row">
		<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box blue">
				<div class="portlet-title">
					<div class="caption">
						<?php echo  MenuUtil::getMenuName($_SERVER['REQUEST_URI'])?>
					</div>
					<div class="actions">
						<?php echo (UserLoginUtils::canCreate($_SERVER['REQUEST_URI']) == false)? "":  CHtml::link(ConfigUtil::getBtnAddName().'(รายบุคคล)',array('Form4/Create'),array('class'=>'btn btn-default btn-sm'));?>
						<?php echo (UserLoginUtils::canCreate($_SERVER['REQUEST_URI']) == false)? "":  CHtml::link("แนบไฟล์",array('Form4/AttachFile'),array('class'=>'btn btn-default btn-sm'));?>
						<?php echo (UserLoginUtils::canCreate($_SERVER['REQUEST_URI']) == false)? "":  CHtml::link("อัพโหลดจากไฟล์",array('Form4/ImportFile'),array('class'=>'btn btn-default btn-sm'));?>
						
<!-- 						<a class="btn btn-default btn-sm" data-toggle="modal" -->
						<!-- 							href="#modalReport"> รายงาน </a> -->
					</div>
				</div>
				<div class="portlet-body">
					<table class="table table-striped table-hover table-bordered"
						id="gvResult">
						<thead>
							<tr>
								<th class="no-sort">ดำเนินการ</th>
								<th>ลำดับ</th>
								<?php if(UserLoginUtils::getUserRoleName () == UserLoginUtils::ADMIN){?>
								<th style="text-align: center">คณะ/ส่วนงาน</th>
								<?php }?>
								<th style="text-align: center">Status.</th>

								<th>ชื่อ-นามสกุล/รายละเอียดไฟล์</th>
								<th>Hp(10)</th>
								<th>Hp(3)</th>
								<th>Hp(0.07)</th>

								<th>ผลการประเมิณ</th>
								<th>วันที่รายงาน</th>
								<th>ไฟล์แนบ</th>
								<th style="text-align: center">ครั้งที่แก้ไข</th>
								
							</tr>


						</thead>
						<tbody>
	<?php
	$counter = 1;
	$dataProvider = $data->search ();
	
	foreach ( $dataProvider->data as $data ) {
		?>
				<tr class="line-<?php echo $counter%2 == 0 ? '1' : '2'?>">
<td class="center">
<?php 
switch (UserLoginUtils::getUserRoleName ()) {
	case UserLoginUtils::ADMIN :
			if(UserLoginUtils::canDelete( $_SERVER['REQUEST_URI'])){?>
				<a title="ยกเลิกใช้งาน" onclick="return confirm('ยกเลิกการใช้งาน?')" class="fa fa-close"
				href="<?php echo Yii::app()->CreateUrl('Form4/Delete/id/'.$data->id)?>"></a>
			<?php }
		break;
}
if($data->status == "F"){?>
<a title="View" class="fa fa-search-minus" 
href="<?php echo Yii::app()->CreateUrl('Form4/View/id/'.$data->id)?>"></a>
<?php }else{
switch (UserLoginUtils::getUserRoleName ()) {
	case UserLoginUtils::ADMIN :
	case UserLoginUtils::USER :
		if($data->approve_status == UserLoginUtils::INIT_APPROVE){
			if(UserLoginUtils::canUpdate( $_SERVER['REQUEST_URI']) && !$data->description == 'ATTACH_FILE'){?>
				<a title="แก้ไข" class="fa fa-edit"
				href="<?php echo Yii::app()->CreateUrl('Form4/Update/id/'.$data->id)?>"></a>
			<?php }
			if(UserLoginUtils::canDelete( $_SERVER['REQUEST_URI'])){?>
				<a title="Delete" onclick="return confirm('ต้องการลบข้อมูลใช่หรือไม่?')" class="fa fa-trash"
				href="<?php echo Yii::app()->CreateUrl('Form4/Delete/id/'.$data->id)?>"></a>
			<?php }
			?>
			<a title="Commit" onclick="return confirm('ต้องการส่งข้อมูลให้ Staff ตรวจสอบ?')" class="fa fa-paper-plane"
				href="<?php echo Yii::app()->CreateUrl('Form4/ApproveStatus/id/'.$data->id.'/approve_status/'.UserLoginUtils::USER_APPROVE)?>"></a>
			<?php
		}
		if($data->revision >1){?>
			<a title="ประวัติการแก้ไข" class="fa fa-clock-o"
			href="<?php echo Yii::app()->CreateUrl('Form4/Revision/id/'.$data->update_from_id)?>"></a>
		<?php }
		
		break;
		case UserLoginUtils::STAFF :
			if($data->approve_status == UserLoginUtils::USER_APPROVE){
				if(UserLoginUtils::canUpdate( $_SERVER['REQUEST_URI'])){?>
							<a title="แก้ไข" class="fa fa-edit"
							href="<?php echo Yii::app()->CreateUrl('Form4/Update/id/'.$data->id)?>"></a>
						<?php }
						if(UserLoginUtils::canDelete( $_SERVER['REQUEST_URI'])){?>
							<a title="Delete" onclick="return confirm('ต้องการลบข้อมูลใช่หรือไม่?')" class="fa fa-trash"
							href="<?php echo Yii::app()->CreateUrl('Form4/Delete/id/'.$data->id)?>"></a>
						<?php }
			?>
			<a title="Commit" onclick="return confirm('ต้องการส่งข้อมูลให้  Executive ตรวจสอบ?')" class="fa fa-paper-plane"
				href="<?php echo Yii::app()->CreateUrl('Form4/ApproveStatus/id/'.$data->id.'/approve_status/'.UserLoginUtils::STAFF_APPROVE)?>"></a>
			<a title="Reject" onclick="return confirm('ส่งข้อมูลให้  User ตรวจสอบใหม่?')" class="fa fa-reply"
				href="<?php echo Yii::app()->CreateUrl('Form4/ApproveStatus/id/'.$data->id.'/approve_status/'.UserLoginUtils::INIT_APPROVE)?>"></a>
			
			<?php
			}
			if($data->revision >1){?>
				<a title="ประวัติการแก้ไข" class="fa fa-clock-o"
				href="<?php echo Yii::app()->CreateUrl('Form4/Revision/id/'.$data->update_from_id)?>"></a>
			<?php }
		break;
	case UserLoginUtils::EXECUTIVE :
		?>
		<a title="View" class="fa fa-search-minus"
		href="<?php echo Yii::app()->CreateUrl('Form4/View/id/'.$data->id)?>"></a>
		<?php
		if($data->approve_status == UserLoginUtils::STAFF_APPROVE && MApprover::isMyTask(UserLoginUtils::getUserInfo(),$data->approve_index)){?>		
			<a title="Commit" onclick="return confirm('ยืนยันการบันทึกข้อมูล?')" class="fa fa-paper-plane"
				href="<?php echo Yii::app()->CreateUrl('Form4/ApproveStatus/id/'.$data->id.'/approve_status/'.UserLoginUtils::EXECUTIVE_APPROVE)?>"></a>
			<a title="Reject" onclick="return confirm('ส่งข้อมูลให้  Staff ตรวจสอบใหม่?')" class="fa fa-reply"
				href="<?php echo Yii::app()->CreateUrl('Form4/ApproveStatus/id/'.$data->id.'/approve_status/'.UserLoginUtils::USER_APPROVE)?>"></a>
			
		<?php }
		if($data->approve_status == UserLoginUtils::EXECUTIVE_APPROVE){?>
			<a title="Reject" onclick="return confirm('ส่งข้อมูลให้  Staff ตรวจสอบใหม่?')" class="fa fa-reply"
			href="<?php echo Yii::app()->CreateUrl('Form4/ApproveStatus/id/'.$data->id.'/approve_status/'.UserLoginUtils::USER_APPROVE)?>"></a>
					
		<?php }
		break;


}}?>

								</td>

								<td class="center"><?php echo  $counter;?></td>
																				<?php if(UserLoginUtils::getUserRoleName () == UserLoginUtils::ADMIN){?>
								<td class="center"><?php echo $data->owner_department->faculty->name.' '.$data->owner_department->name.' '.$data->owner_department->branch_id?></td>
								<?php }?>
								<td class="center"><?php echo CommonUtil::getApproveStatus($data->approve_status);?></td>
								
								<td class="center"><?php echo $data->name?></td>
								<td class="center"><?php echo $data->hp_10_volume?></td>
								<td class="center"><?php echo $data->hp_3_volume?></td>
								<td class="center"><?php echo $data->hp_007_volume?></td>

								<td class="center"><?php
		switch ($data->result) {
			case "1" :
				echo "อยู่ในเกณฑ์มาตรฐานความปลอดภัย";
				break;
			case "2" :
				echo "เกินมาตรฐานความปลอดภัย";
				break;
		}
		?>
		</td>
								<td class="center"><?php echo $data->report_month.'/'.$data->report_year?></td>
								<td class="center">
									<?php if(isset($data->file_path)){?>
									<a title="Download" class="fa fa-download"
									href="<?php echo  ConfigUtil::getAppName().''. $data->file_path?>">&nbsp;ดาวโหลด</a>	<?php }?></td>


								<td class="center"><?php echo $data->revision?></td>

								
								
							</tr>
			<?php
		$counter ++;
	}
	?>						

						</tbody>
					</table>

					<div class="note note-info">
						<h3>หมายเหตุ</h3>
						<table>
							<tr>
								<td>1.</td>
								<td>&nbsp;&nbsp;Hp(10) เป็นปริมาณรังสีที่ความลึก 10 มิลลิเมตร
									จากผิวแทนปริมาณรังสีทั่วลำตัว</td>
								<td></td>
							</tr>
							<tr>
								<td></td>
								<td>&nbsp;&nbsp;Hp(3) เป็นปริมาณรังสีที่ความลึก 3 มิลลิเมตร
									จากผิว แทนปริมาณรังสีที่เลนส์ตา</td>
								<td></td>
							</tr>
							<tr>
								<td></td>
								<td>&nbsp;&nbsp;Hp(0.07) เป็นปริมาณรังสีที่ความลึก 0.07
									มิลลิเมตร จากผิว แทนปริมาณรังสีที่ผิวหนัง</td>
								<td></td>
							</tr>
							<tr>
								<td>2.</td>
								<td>&nbsp;&nbsp;ค่าปริมาณรังสีระดับที่สำนักรังสีและเครื่องมือแพทย์ต้องแทรกแซง
									สำหรับ</td>
								<td></td>
							</tr>
							<tr>
								<td></td>
								<td>&nbsp;&nbsp;Hp(10) 4,000 ไมโครซีเวิร์ต/เดือน</td>
								<td></td>
							</tr>
							<tr>
								<td></td>
								<td>&nbsp;&nbsp;Hp(0.07) 40,000 ไมโครซีเวิร์ต/เดือน</td>
								<td></td>
							</tr>
							<tr>
								<td></td>
								<td>&nbsp;&nbsp;Hp(3) 12,000 ไมโครซีเวิร์ต/เดือน</td>
								<td></td>
							</tr>
							<tr>
								<td></td>
								<td>&nbsp;&nbsp;ค่าเฉลี่ยปริมาณรังสี 5 ปี ติดต่อกันต้องไม่เกิน
									20 มิลลิซีเวิร์ต
									และในปีใดปีหนึ่งค่าปริมาณรังสีเฉลี่ยต้องไม่มากกว่า 50
									มิลลิซีเวิร์ต</td>
								<td></td>
							</tr>
							<tr>
								<td>3.</td>
								<td>&nbsp;&nbsp;ค่าปริมาณรังสีในรายงานผล คือ
									ค่าปริมาณรังสีเฉลี่ยรายเดือนในแต่ละรอบการใช้งาน</td>
								<td></td>
							</tr>
							<tr>
								<td>4.</td>
								<td>&nbsp;&nbsp; ค่าปริมาณรังสี =1 หมายถึง
									ปริมาณรังสีที่ต่ำกว่าระดับการบันทึก 100 ไมโครซีเวิร์ต/ 3 เดือน</td>
								<td></td>
							</tr>
						</table>






					</div>

				</div>



			</div>
		</div>
	</div>

	<script
		src="<?php echo ConfigUtil::getAppName();?>/assets/global/plugins/jquery.min.js"
		type="text/javascript"></script>
	<script>
jQuery(document).ready(function () {
	var table = $('#gvResult');

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
    $('#report_id').on('change', function() {
        if(this.value>0){
    		$("#btnDownload").attr("href", "Form4/Report"+this.value);
        }else{
    		$("#btnDownload").attr("href", "#");
            
        }
    });
});

</script>

</form>