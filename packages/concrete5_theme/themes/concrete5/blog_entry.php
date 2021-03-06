<?php
defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('elements/header.php'); ?>

<main>
    <?php
    $a = new Area('Page Header');
    $a->enableGridContainer();
    $a->display($c);
    ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-sm-4 col-sidebar">
                <?php
                $a = new Area('Sidebar');
                $a->display($c);
                ?>
            </div>
            <div class="col-lg-8 col-lg-offset-1 col-sm-8 col-content">
                <?php
                $a = new Area('Blog Post Header');
                $a->setAreaGridMaximumColumns(12);
                $a->display($c);
                ?>
                <?php
                $a = new Area('Main');
                $a->setAreaGridMaximumColumns(12);
                $a->display($c);
                ?>
                <?php
                $a = new Area('Blog Post More');
                $a->setAreaGridMaximumColumns(12);
                $a->display($c);
                ?>

            </div>
        </div>
    </div>

    <?php
    $a = new Area('Page Footer');
    $a->enableGridContainer();
    $a->display($c);
    ?>

</main>

<?php  $this->inc('elements/footer.php'); ?>
