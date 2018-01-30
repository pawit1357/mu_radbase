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
					<?php if(MenuUtil::getCurrentPageStatus($_SERVER['REQUEST_URI'],"Revision")){?>
						<?php echo CHtml::link('ย้อนกลับ',array('Form5/'),array('class'=>'btn btn-default btn-sm'));?>
					<?php }else{?>
						<?php echo (UserLoginUtils::canCreate($_SERVER['REQUEST_URI']) == false)? "":  CHtml::link(ConfigUtil::getBtnAddName(),array('Form5/Create'),array('class'=>'btn btn-default btn-sm'));?>
					<?php }?>
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

								<th style="text-align: center">Rev.</th>
								<th>วัน/เดือน/ปี ที่ส่งกำจัด</th>
								<th>ประเภทวัสดุกัมมันตรังสี</th>
								<th>ชื่อวัสดุกัมมันตรังสี</th>
								<th>สมบัติทางกายภาพ</th>
								<th>กัมมันตภาพสูงสุดหรือน้ำหนัก</th>
								<!-- 								<th>หน่วยงาน/บริษัท ที่ส่งกำจัด</th> -->

								
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
				href="<?php echo Yii::app()->CreateUrl('Form5/Delete/id/'.$data->id)?>"></a>
			<?php }
		break;
}
if($data->status == "F"){?>
<a title="View" class="fa fa-search-minus" 
href="<?php echo Yii::app()->CreateUrl('Form5/View/id/'.$data->id)?>"></a>
<?php }else{
switch (UserLoginUtils::getUserRoleName ()) {
	case UserLoginUtils::ADMIN :
	case UserLoginUtils::USER :
		if($data->approve_status == UserLoginUtils::INIT_APPROVE){
			if(UserLoginUtils::canUpdate( $_SERVER['REQUEST_URI'])){?>
				<a title="แก้ไข" class="fa fa-edit"
				href="<?php echo Yii::app()->CreateUrl('Form5/Update/id/'.$data->id)?>"></a>
			<?php }
			if(UserLoginUtils::canDelete( $_SERVER['REQUEST_URI'])){?>
				<a title="Delete" onclick="return confirm('ต้องการลบข้อมูลใช่หรือไม่?')" class="fa fa-trash"
				href="<?php echo Yii::app()->CreateUrl('Form5/Delete/id/'.$data->id)?>"></a>
			<?php }
			?>
			<a title="Commit" onclick="return confirm('ต้องการส่งข้อมูลให้ Staff ตรวจสอบ?')" class="fa fa-paper-plane"
				href="<?php echo Yii::app()->CreateUrl('Form5/ApproveStatus/id/'.$data->id.'/approve_status/'.UserLoginUtils::USER_APPROVE)?>"></a>
			<?php
		}
		if($data->revision >1){?>
			<a title="ประวัติการแก้ไข" class="fa fa-clock-o"
			href="<?php echo Yii::app()->CreateUrl('Form5/Revision/id/'.$data->update_from_id)?>"></a>
		<?php }
		
		break;
		case UserLoginUtils::STAFF :
			if($data->approve_status == UserLoginUtils::USER_APPROVE){
				if(UserLoginUtils::canUpdate( $_SERVER['REQUEST_URI'])){?>
							<a title="แก้ไข" class="fa fa-edit"
							href="<?php echo Yii::app()->CreateUrl('Form5/Update/id/'.$data->id)?>"></a>
						<?php }
						if(UserLoginUtils::canDelete( $_SERVER['REQUEST_URI'])){?>
							<a title="Delete" onclick="return confirm('ต้องการลบข้อมูลใช่หรือไม่?')" class="fa fa-trash"
							href="<?php echo Yii::app()->CreateUrl('Form5/Delete/id/'.$data->id)?>"></a>
						<?php }
			?>
			<a title="Commit" onclick="return confirm('ต้องการส่งข้อมูลให้  Executive ตรวจสอบ?')" class="fa fa-paper-plane"
				href="<?php echo Yii::app()->CreateUrl('Form5/ApproveStatus/id/'.$data->id.'/approve_status/'.UserLoginUtils::STAFF_APPROVE)?>"></a>
			<a title="Reject" onclick="return confirm('ส่งข้อมูลให้  User ตรวจสอบใหม่?')" class="fa fa-reply"
				href="<?php echo Yii::app()->CreateUrl('Form5/ApproveStatus/id/'.$data->id.'/approve_status/'.UserLoginUtils::INIT_APPROVE)?>"></a>
			
			<?php
			}
			if($data->revision >1){?>
				<a title="ประวัติการแก้ไข" class="fa fa-clock-o"
				href="<?php echo Yii::app()->CreateUrl('Form5/Revision/id/'.$data->update_from_id)?>"></a>
			<?php }
		break;
	case UserLoginUtils::EXECUTIVE :
		?>
		<a title="View" class="fa fa-search-minus"
		href="<?php echo Yii::app()->CreateUrl('Form5/View/id/'.$data->id)?>"></a>
		<?php
		if($data->approve_status == UserLoginUtils::STAFF_APPROVE && MApprover::isMyTask(UserLoginUtils::getUserInfo(),$data->approve_index)){?>		
			<a title="Commit" onclick="return confirm('ยืนยันการบันทึกข้อมูล?')" class="fa fa-paper-plane"
				href="<?php echo Yii::app()->CreateUrl('Form5/ApproveStatus/id/'.$data->id.'/approve_status/'.UserLoginUtils::EXECUTIVE_APPROVE)?>"></a>
			<a title="Reject" onclick="return confirm('ส่งข้อมูลให้  Staff ตรวจสอบใหม่?')" class="fa fa-reply"
				href="<?php echo Yii::app()->CreateUrl('Form5/ApproveStatus/id/'.$data->id.'/approve_status/'.UserLoginUtils::USER_APPROVE)?>"></a>
			
		<?php }
		if($data->approve_status == UserLoginUtils::EXECUTIVE_APPROVE){?>
			<a title="Reject" onclick="return confirm('ส่งข้อมูลให้  Staff ตรวจสอบใหม่?')" class="fa fa-reply"
			href="<?php echo Yii::app()->CreateUrl('Form5/ApproveStatus/id/'.$data->id.'/approve_status/'.UserLoginUtils::USER_APPROVE)?>"></a>
					
		<?php }
		break;


}}?>

								</td>
								<td class="center"><?php echo  $counter;?></td>
																<?php if(UserLoginUtils::getUserRoleName () == UserLoginUtils::ADMIN){?>
								<td class="center"><?php echo $data->owner_department->faculty->name.' '.$data->owner_department->name.' '.$data->owner_department->branch_id?></td>
								<?php }?>
								<td class="center"><?php echo CommonUtil::getApproveStatus($data->approve_status);?></td>

								<td class="center"><?php echo $data->revision?></td>

								<td class="center"><?php echo (isset($data->clear_date)? CommonUtil::getDateThai( $data->clear_date):'')?></td>
								<td class="center"><?php echo (isset($data->types_of_radioactive_waste->name)? $data->types_of_radioactive_waste->name:'')?></td>
								<td class="center"><?php echo (isset($data->rad->bpm_radioactive_elements->name)? $data->rad->bpm_radioactive_elements->name : '')?></td>
								<td class="center"><?php echo (isset($data->phisical_status->name)? $data->phisical_status->name:'')?></td>
								<td class="center"><?php echo $data->rad_or_maximum_weight.'  '.$data->unit->name;?></td>


								
							</tr>
			<?php
		$counter ++;
	}
	?>		
						

						</tbody>
					</table>

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
    		$("#btnDownload").attr("href", "Form5/Report"+this.value);
        }else{
    		$("#btnDownload").attr("href", "#");
            
        }
    });
});

</script>

</form>