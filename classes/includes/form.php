<tr>
	<td colspan="3"><hr/></td>
</tr>


<tr class="form-field">
	<th scope="row" valign="top">
		<label for="extra_html"><?php _ex('Advertise Name', 'name block'); ?></label>
	</th>
	<td>
		<?php
			echo $term_meta->name;
		?>
	</td>
</tr>

<tr class="form-field">
	<th scope="row" valign="top">
		<label for="extra_html"><?php _ex('Advertise Content', 'Html block'); ?></label>
	</th>
	<td>
		<textarea name="extra_html" id="extra_html" rows="10" cols="50" style="width: 97%;"><?php echo stripslashes($term_meta->content); ?></textarea>
		<br />
		<span class="description">
		<?php _e('This code is for advertisement'); ?>
		</span>
	</td>
</tr>


		
