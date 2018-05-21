<?php
return array(
	'app_begin' => array('Common\Behavior\CheckLangBehavior','Common\Behavior\load_langBehavior'),
	'view_filter' => array('Behavior\TokenBuildBehavior'),
);