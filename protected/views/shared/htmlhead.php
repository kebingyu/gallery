<!doctype html>
<html>
<head>
<title><?php echo $this->pageTitle . ' | Zhou Family Gallery'; ?></title>
<?php
if($metaDescription = str_replace('"','\"',$this->metaDescription)){
	echo "<meta name = \"description\" content = \"" . $metaDescription . "\" />\n";
}
if($metaKeyword = str_replace('"','\"',$this->metaKeyword)){
	echo "<meta name = \"keywords\" content = \"" . $metaKeyword . "\" />\n";
}
?>
<meta charset="UTF-8">
<meta name="author" content="kyu" />
<meta name="robots" content="noindex, nofollow" />
</head>
