<?php
session_start();
// include Yii bootstrap file
require_once(dirname(__FILE__).'/framework/yii.php');
$config=dirname(__FILE__).'/protected/config/main.php';




// include custom class
require_once dirname(__FILE__).'/protected/utilities/UserLoginUtils.php';
require_once dirname(__FILE__).'/protected/utilities/ConfigUtil.php';
require_once dirname(__FILE__).'/protected/utilities/MenuUtil.php';
require_once dirname(__FILE__).'/protected/utilities/GridUtil.php';
require_once dirname(__FILE__).'/protected/utilities/CommonUtil.php';
require_once dirname(__FILE__).'/protected/utilities/MailUtil.php';
require_once dirname(__FILE__).'/protected/utilities/QuestionUtil.php';
require_once dirname(__FILE__).'/protected/utilities/PHPExcel/Classes/PHPExcel/IOFactory.php';
require_once dirname(__FILE__).'/protected/utilities/fpdi/fpdf.php';
require_once dirname(__FILE__).'/protected/utilities/fpdi/fpdfhtml.php';


require_once dirname(__FILE__).'/protected/utilities/fpdi/fpdi.php';
require_once dirname(__FILE__).'/protected/utilities/fpdi/fpdf_tpl.php';
require_once dirname(__FILE__).'/protected/utilities/fpdi/fpdi_bridge.php';
require_once dirname(__FILE__).'/protected/utilities/fpdi/fpdi_pdf_parser.php';
require_once dirname(__FILE__).'/protected/utilities/fpdi/pdf_context.php';
require_once dirname(__FILE__).'/protected/utilities/fpdi/pdf_parser.php';


require_once dirname(__FILE__).'/protected/utilities/tcpdf/tcpdf.php';
// tcpdf_autoconfig.php
// tcpdf_import.php
// tcpdf_include.php
// tcpdf_parser.php



// create a Web application instance and run
$app = Yii::createWebApplication($config);
Yii::app()->setTimeZone('UTC');
$app->run();
