<?php // @codingStandardsIgnoreLine
/**
 * Tennis Brackets plugin's main bootstrap file.
 *
 * @package  tennis-brackets
 * @author   Konstantinos Galanakis
 */
require_once TENNIS_BRACKETS_PATH . '/inc/autoload.php';
$tb_main = new \Tennis_Brackets\Controllers\Tennis_Brackets_Main();
$tb_main->initialize();
