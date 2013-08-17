<!DOCTYPE html>
<!--[if IE 8]><html class="no-js lt-ie9" lang="en" ><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js" lang="en" ><!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
	
	<title><?= $title ?></title>

<?= Asset::css('foundation.css') ?>
<?= Asset::css('style.css') ?>

<?= Asset::js('vendor/custom.modernizr.js') ?>
<?= Asset::js('vendor/jquery.js') ?>
<?= Asset::js('foundation.min.js') ?>
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCRuPDt2BPdoVJpCMqRgGXA8sRb3lSaAVw&sensor=true"></script>
	<script>
		$(function() {
			$(document).foundation();
		});
	</script>
</head>
<body>
<?= $content; ?>
</body>
</html>