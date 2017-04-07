<?php
/**
 * Created by Netbeans.
 * User: Arun
 * Date: 27/3/17
 * Time: 5:51 AM
 */
?>

<h4 style="text-transform: none;">
    <?= $tweet->text ?>
</h4>

<?php
if (!empty($tweet->media_url_https)) {
    echo '<img style="max-width: 100%;" src="'. $tweet->media_url_https .'" >';
}
?>