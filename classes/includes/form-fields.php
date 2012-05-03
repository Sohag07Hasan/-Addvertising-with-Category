<div class="wrap">
	<h2>Add New</h2>
	
	<?php
	
		if(isset($_REQUEST['term_id']) && !empty($_REQUEST['term_id'])){
			echo "<div class='updated'><p>saved</p></div>";
		}
	?>
	
	<form name="addpromo" id="promoadd" method="post" action="">				
			<input type="hidden" name="advertisement_added" value="Y" />
			<input type="hidden" name="advertisement_id" value="<?php echo $meta->id; ?>" />				
			<div id="poststuff" class="metabox-holder has-right-sidebar">
			
				<div id="post-body">
					<div id="post-body-content">
					
						<div id="namediv" class="stuffbox">
							<h3> <level for="link_name" >Add new Advertisement</level> </h3>
							<div class="inside">
								<table class="form-table">
									<tbody>
										<tr class="site_id_row">
											<th valign="top" scope="row">
												Advertisement Name : 
											</th>
											<td valign="top">
												<input type="text" name="addvertise_name" value="<?php echo $meta->name ; ?>" />
											</td>
										</tr>
										
										<tr class="site_id_row">
											<th valign="top" scope="row">
												Html or Js : 
											</th>
											<td valign="top">
												<textarea rows="10" cols="62" name="addvertise_content"><?php echo stripslashes($meta->content); ?></textarea>
											</td>
										</tr>
										
										<tr class="site_id_row">
											<th valign="top" scope="row">
												Bind A Category : 
											</th>
											<td valign="top">
												<select name="advertise_category">																									
													<?php echo self::get_categories($meta->term_id); ?>												
												</select>
											</td>
										</tr>
										
										<tr class="site_id_row">
											<th valign="top" scope="row">
												Select a Position : 
											</th>
											<td valign="top">
												<select class="postform" name="advertise_position">
													<option <?php selected('1', $meta->position); ?> value="1">Top</option>
													<option <?php selected('2', $meta->position); ?> value="2">Bottom</option>
													<option <?php selected('3', $meta->position); ?> value="3">After First Paragraph</option>
												</select>
											</td>
										</tr>
										
										
									</tbody>
								</table>																			
							</div>
						</div> <!-- stuffbox -->																				
						
					</div> <!-- post-body-content -->
				</div> <!-- post-body -->
			
				<div class="inner-sidebar">
						<div id="linkgoaldiv" class="postbox ">
							<div class="handlediv" title="Click to toggle"><br/></div>
							<h3 class="hndle"><span> Set it Global </span></h3>
							<div class="inside">
								<input type="checkbox" name="global_status" value='1' <?php checked('1', $gt); ?> /> Set this for all Category
							</div>
						</div>
				</div> <!-- innder sidebar -->
				
				<div id="side-info-column" class="inner-sidebar">
						<div id="linkgoaldiv" class="postbox ">
							<div class="handlediv" title="Click to toggle"><br/></div>
							<h3 class="hndle"><span> Generate/Update </span></h3>
							<div class="inside">
								<input type="submit" value="save" class="button-primary" />
							</div>
						</div>
				</div> <!-- innder sidebar -->					
			</div> <!-- poststuff -->
	</form>
	
</div>
