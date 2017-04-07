<?php
/**
 * Created by Netbeans.
 * User: Arun
 * Date: 20/3/17
 * Time: 7:35 AM
 */
?>
<a href="<?= $post->fb_link ?>" target="_blank">
<h4 style="text-transform: none;">
    <?= $post->story ?>
</h4>
<p <?php if($post->post_type != 'status') { echo ' style="font-size: 12px;" '; } ?>>
    <?= $post->description ?>
</p>

<?php
if (!empty($post->picture)) {
    echo '<img style="max-width: 100%;" src="'. $post->full_picture .'" >';
}
?>

</a>