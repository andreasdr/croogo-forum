<?php if (($crumbs = $this->Breadcrumb->get()) && count($crumbs) > 2) { ?>
    <nav class="breadcrumb">
        <ul>
<?php
	foreach ($crumbs as $i => $crumb) {
		// skip homepage
		if ($i < 2) continue;
	?>
                <li>
                    <a href="<?php echo $this->Html->url($crumb['url']); ?>">
                        <?php echo h($crumb['title']); ?>
                        <span class="caret">/</span>
                    </a>
                </li>
	<?php } ?>
        </ul>
    </nav>
<?php }