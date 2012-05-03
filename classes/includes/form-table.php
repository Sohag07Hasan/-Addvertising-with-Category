<div class="wrap">
	<?php screen_icon('link-manager'); ?>
	<h2>Add Management</h2>
	
	<?php
		if($_POST['b_action'] == 'delete' || $_GET['action'] == 'delete'){
			echo "<div class='updated'><p>Successfully deleted</p></div>";
		 }	
	?>
	
	<form action="" method="post">
		<!-- the bulk action button -->
		<div class="tablenav top">				
			<div class="alignleft actions">					
				<select name="b_action">
					<option selected="selected" value="-1">Bulk Actions</option>
					<option value="delete">Delete</option>
				</select>
				<input id="doaction" class="button-secondary action" type="submit" value="Apply" name="promo_delete_button" />
			</div>
			<div class="alignleft actions">						
			</div>
			<br class="clear">
		</div>
		
		<!-- table view of all the batches -->
		<table class="wp-list-table widefat fixed bookmarks" cellspacing="0">
			<thead>
				<tr>
					<th id="cb" class="manage-column column-cb check-column" style="" scope="col">
						<input type="checkbox">
					</th>
					<th id="name" class="manage-column column-name sortable desc" style="" scope="col">
						<a href="#">
							<span>Name</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>
					
					<th class="manage-column column-visible sortable desc" style="" scope="col">
						<a href="#">
							<span>Category</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>
										
					<th class="manage-column column-visible sortable desc" style="" scope="col">
						<a href="#">
							<span>Global Status</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>							
				</tr>
			</thead>
			<tfoot>
				<tr>				
					
					
					<th id="cb" class="manage-column column-cb check-column" style="" scope="col">
						<input type="checkbox">
					</th>
					<th id="name" class="manage-column column-name sortable desc" style="" scope="col">
						<a href="#">
							<span>Name</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>
					
					<th class="manage-column column-visible sortable desc" style="" scope="col">
						<a href="#">
							<span>Category</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>
										
					<th class="manage-column column-visible sortable desc" style="" scope="col">
						<a href="#">
							<span>Global Status</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>							
			</tr>
				
			</tfoot>
			
			<tbody>
				<?php
					$home = get_option('siteurl');
					foreach($data as $d) :
						
						$edit_link = $home . '/wp-admin/admin.php?page=advertisent_addition&term_id=';
						$del_link = $home . '/wp-admin/admin.php?page=add_management&action=delete&term_id=';
					
						?>
						
						<tr>
							<th class='check-column' scope='row'>
								<input type='checkbox' value="<?php echo $d->term_id; ?>" name='check[]'>
							</th>
							<td> 
								<?php echo $d->name; ?>
								<div class='row-actions'>
									<a href="<?php echo $edit_link . $d->term_id; ?>">Edit</a>&nbsp| 
									<a style='color:red' href="<?php echo $del_link . $d->term_id; ?>">Delete</a>
								</div>
							</td>
														
							<td><?php echo get_cat_name($d->term_id); ?></td>
							<td><?php echo($d->term_id == self::get_global_term())? 'global' : 'local' ?></td>
						</tr>
						
						<?php
					endforeach;
				?>
			</tbody>

		</table>
	</form>
	
</div>
