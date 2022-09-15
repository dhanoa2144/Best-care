<?php
$row_labels = get_sub_field('row_labels');
$columns = get_sub_field('columns');
?>

<section class="module__framework-table">
	<div class="framework-table__arrows"></div>
	<div class="container">
		<div class="row">
			<?php
			for($i = 0; $i < count($columns); $i++) {
				$header = $columns[$i]['header']; // group
				$rows =  $columns[$i]['rows']; // repeater
				?>
				<div class="framework-table__column framework-table__column--<?php echo $i; ?> col-12 col-md-6 col-lg-3">

					<?php
					$image_id = $header['image'];
					$bg = $header['background_colour'];
					$audience = $header['audience'];
					$audience_link = $header['audience_link_'];
					$audience_name = $audience->name;
					$audience_url = get_term_link($audience->term_id, 'audience');
					$subheading = $header['subheading'];
					$description = $header['description'];
					$page_link = $header['page_link_'];
					?>

					<a class="framework-table__column__header bg-<?php echo $bg; ?>" href="<?php echo $audience_link; ?>">
						<div class="framework-table__column__header__image">
						
							<?php echo wp_get_attachment_image($image_id, 'medium_taller'); ?>
						</div>
						<div class="framework-table__column__header__copy">
							<h2 class="h3"><?php echo $audience_name; ?></h2>
							<p class="h5"><?php echo $subheading; ?></p>
							<p><?php echo $description; ?></p>
						</div>
					</a>

					<div class="framework-table__column__rows">
						<ul class="framework-table__column__rows__list framework-table__column__rows__list--count-<?php echo count($rows); ?>">
							<?php
							for($j = 0; $j < count($rows); $j++) {
								$row_count = $j;
								$row_label = $row_labels[$j]['label'];
								$row_page_link = $row_labels[$j]['page_link_page'];
								$row_slug = lp_slugify($row_label);
								$row_classes = 'hover-bg-' . $row_labels[$j]['colour'];
								if(count($rows) == 1) {
									$row_slug = 'best-care-governance'; // hack to just link it down to the tab the client wants in this context
									$row_classes = 'full-height';
									$row_page_link = '/framework/best-care-governance/';
								} ?>
								<li>
									<?php
									if($i == 0) { ?>
										<span class="row-label first-column d-none d-xxl-flex <?php echo $row_classes; ?>" data-linkgroup="<?php echo $row_slug;
										?>">
											<?php echo $row_label; ?>
										</span>
									<?php } else { ?>
										<span class="row-label d-none"><?php echo $row_label; ?></span>
									<?php } ?>
									<a href="<?php echo $row_page_link;?>"
									   class="<?php echo $row_classes; ?>"
									   data-linkgroup="<?php echo $row_slug; ?>"
									  
									>

									
										<?php echo $rows[$j]['row_copy']; ?>
									</a>
								</li>
							<?php } ?>
						</ul>
					</div>

				</div>
			<?php } ?>
		</div>
	</div>
</section>