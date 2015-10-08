<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 10/1/2015 9:09 AM
 *
 * Template Du an noi bat 1 col 2 rows
 * @var $news from NewsWidget parameter
 */
?>

<?php
if (!empty($news)) {
    foreach ($news as $k => $n) { ?>
        <div class="blocksibar">
            <img src="store/news/show/<?= $n->banner ?>" alt="<?= $n->title ?>">

            <div class="showtext">
                <h4> <?= $n->title ?> </h4>
            </div>
        </div>
    <?php }
}
?>


