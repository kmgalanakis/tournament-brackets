<?php // @codingStandardsIgnoreLine
/**
 * Tennis Brackets plugin's main bootstrap file.
 *
 * @package  tournament-brackets
 * @author   Konstantinos Galanakis
 */
require_once TOURNAMENT_BRACKETS_PATH . '/inc/autoload.php';
$tb_main = new \Tournament_Brackets\Controllers\Tournament_Brackets_Main();
$tb_main->initialize();
