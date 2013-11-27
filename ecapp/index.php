<?php
error_reporting(-1); //change this in production
session_save_path('tmp');
include_once 'includes.php';
include_once 'SPINE/Spine_Core/SpineFrontController.php';

/***added this text for test commit.***/
try
{
	$spine	=	new Spine_FrontController();
	$spine->init();
}
catch (Exception $e)
{
	echo $e->getMessage();
}

