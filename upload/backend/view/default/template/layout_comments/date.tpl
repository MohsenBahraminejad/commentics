<?php echo $header; ?>

<div class="layout_comments_date_page">

	<div class='page_help_block'><?php echo $page_help_link; ?></div>

	<h1><?php echo $lang_heading; ?></h1>

	<hr>

	<?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>

	<?php if ($info) { ?>
		<div class="info"><?php echo $info; ?></div>
	<?php } ?>

	<?php if ($error) { ?>
		<div class="error"><?php echo $error; ?></div>
	<?php } ?>

	<?php if ($warning) { ?>
		<div class="warning"><?php echo $warning; ?></div>
	<?php } ?>

	<div class="description"><?php echo $lang_description; ?></div>

	<form action="index.php?route=layout_comments/date" class="controls" method="post">
		<div class="fieldset">
			<label><?php echo $lang_entry_enabled; ?></label>
			<input type="checkbox" name="show_date" value="1" <?php if ($show_date) { echo 'checked'; } ?>>
			<a class="hint" onmouseover="showhint('<?php echo $lang_hint_enabled; ?>', this, event, '')">[?]</a>
		</div>

		<div class="fieldset">
			<label><?php echo $lang_entry_auto; ?></label>
			<input type="checkbox" name="date_auto" value="1" <?php if ($date_auto) { echo 'checked'; } ?>>
			<a class="hint" onmouseover="showhint('<?php echo $lang_hint_auto; ?>', this, event, '')">[?]</a>
		</div>

		<input type="hidden" name="csrf_key" value="<?php echo $csrf_key; ?>">

		<div class="buttons"><input type="submit" class="button" value="<?php echo $lang_button_update; ?>" title="<?php echo $lang_button_update; ?>"></div>

		<div class="links"><a href="<?php echo $link_back; ?>"><?php echo $lang_link_back; ?></a></div>
	</form>

</div>

<?php echo $footer; ?>