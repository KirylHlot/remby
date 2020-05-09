<?php function arrayToDataAttr($array){
	return htmlentities(json_encode($array), ENT_QUOTES, 'UTF-8');
}