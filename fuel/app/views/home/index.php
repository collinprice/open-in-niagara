<div class="row">
	<div class="large-12 header">
		<h2>Open in Niagara</h2>
		<p>Find out whats going on this week in the Niagara Region.</p>
		<hr />
	</div>
</div>
<div class="row">
	<div class="large-5 columns">
		<h4>Today</h4>
		<ul class="no-bullet">
		<?php foreach ($today as $event) : ?>
			<li><?= date('g:ia', strtotime($event['open_time'])) ?> - <?= date('g:ia', strtotime($event['close_time'])) ?> <?= Html::anchor($event['website'], $event['name'], array('target' => '_blank')) ?></li>
		<?php endforeach; ?>
		</ul>
		<h4>Upcoming</h4>
		<?php foreach($upcoming as $day) : ?>
		<h5><?= $day['day'] ?></h5>
		<ul class="no-bullet">
		<?php if (count($day['events']) > 0) : ?>
		<?php foreach($day['events'] as $event) : ?>
			<li><?= date('g:ia', strtotime($event['open_time'])) ?> - <?= date('g:ia', strtotime($event['close_time'])) ?> <?= Html::anchor($event['website'], $event['name'], array('target' => '_blank')) ?></li>
		<?php endforeach; ?>
		<?php else : ?>
			<li>Nothing.</li>
		<?php endif; ?>
		</ul>
		<?php endforeach; ?>
	</div>
	<div class="large-7 columns">
		<div id="map-canvas"></div>
	</div>
</div>
<script type="text/javascript">
	
	$(function() {
		var mapOptions = {
			zoom: 10,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
		var LatLngList = new Array();

		<?php foreach ($waypoints as $waypoint) : ?>
		new google.maps.Marker({
			title: "<?= $waypoint['title'] ?>",
			position: new google.maps.LatLng(<?= $waypoint['latitude'] ?>, <?= $waypoint['longitude'] ?>),
			map: map
		});
		LatLngList.push(new google.maps.LatLng(<?= $waypoint['latitude'] ?>, <?= $waypoint['longitude'] ?>));
		<?php endforeach; ?>

		var bounds = new google.maps.LatLngBounds();
		for (var i = 0; i < LatLngList.length; i++) {
			bounds.extend(LatLngList[i]);
		}

		map.fitBounds(bounds);
	});

</script>