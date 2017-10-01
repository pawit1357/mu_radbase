<?php
class GridUtil {
	public static function RenderPageButton($ctrl, $dataProvider){
		$pages = new CPagination();
		$pages->pageVar = $dataProvider->pagination->pageVar;
		$ctrl->widget('CLinkPager', array(
				'pages' => $pages,
				'itemCount'=> $dataProvider->totalItemCount,
				'pageSize'=> $dataProvider->pagination->pageSize,
				'maxButtonCount'=> 15,
				'nextPageLabel'=>'Next >>',
				'header' => 'Go to page::',
		));
	}
	
	public static function getDataIndex($dataProvider, $index){
		$pageSize = $dataProvider->pagination->pageSize;
		$currentPage = $_GET[$dataProvider->pagination->pageVar];
		if($currentPage * 1 <= 0) {
			$currentPage = 1;
		}
		
		if(($currentPage - 1) * $pageSize > $dataProvider->totalItemCount) {
			$currentPage = floor($dataProvider->totalItemCount / $pageSize);
			if(($currentPage * $pageSize) < $dataProvider->totalItemCount) {
				$currentPage = $currentPage + 1;
			}
		}
		
		return (($currentPage - 1) * $pageSize) + $index;
		
	}
}
?>