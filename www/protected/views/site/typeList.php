<style type="text/css">
.edu-full-list {background: #f4f4f4; padding: 20px 20px 10px 20px;}
.edu-full-list__item {margin-bottom: 10px;}
</style>
<div class="g-48">
    <div class="g-col-1 g-span-37">

        <h1 class="b-header b-header_type_h1"><?php echo $title; ?></h1>

        <!-- Весь список -->
        <div class="g-48">
            <?php
                $current = 0;
                foreach ($rows as $i=>$_keys) {
            ?>
            <div class="g-col-<?php echo 15*$current+1 ?> g-span-15">
                <?php
                foreach ($_keys as $k=>$_row) {
                ?>
                <h2><?=$k?></h2>
                <ul class="edu-full-list__list">
                    <?php
                    foreach ($_row['rows'] as $item ) {
                    ?>
                    <li class="edu-full-list__item">
                        <?php echo CHtml::link($item['title'], array('/site/showCard', 'id'=>$item['id']), array('class'=>'')); ?>
                    </li>
                    <?php
                    }
                    ?>
                </ul>
                <?php
                }
                ?>
                </div>
                <?php
                    $current++;
                }
                ?>
        </div>
        <!-- Весь спиок -->


    </div>
    <div class="g-col-39 g-span-10 g-main-col-2">
        <?php
            $this->renderPartial('application.views.blocks.bannerRight', array(
            ));
        ?>
    </div>

</div>