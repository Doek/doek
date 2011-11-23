<?php $count = sizeof($rows);
if ($count > 0) : ?>
<div id="slideshow">
<?php foreach ($rows as $id => $row): ?>
    <?php print $row; ?>
<?php endforeach; ?>
</div>
<div id="slider-controls-wrapper">
    <div id="slider-controls">
	<ul id="slider-navigation">
<?php for($i = 0; $i < $count; $i++): ?>
	    <li><a href="#"></a></li>
<?php endfor; ?>
	</ul>
    </div>
</div>
<? endif; ?>

