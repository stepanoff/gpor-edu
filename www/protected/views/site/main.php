<style type="text/css">
.edu-full-list {background: #f4f4f4; padding: 20px 20px 10px 20px;}
.edu-full-list__item {margin-bottom: 10px;}
</style>
<div class="g-48">
    <div class="g-col-1 g-span-27 g-main-col-1">

        <?php
        foreach ($imgBlocks as $key => $value) {
            $this->renderPartial('application.views.blocks.objectsOnMain', array(
                'items' => $value,
                'title' => $types[$key]['title'],
            ));
        }
        ?>

        <h3 class="b-header b-header_type_h3">Последние новости</h3>

        <!-- Новости -->
        <div class="g-48">
            <div class="g-col-1 g-span-48">
                <?php 
                if ($news) {
                    foreach ($news as $singleNews) {
                        ?>
                <div class="b-news-item">
                    <?php echo $singleNews['titlelink']; ?>
                </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
        <!-- Новости -->


    </div>

    <div class="g-col-29 g-span-20 g-main-col-2">
        <div class="g-48">
            <div class="g-col-1 g-span-24">
                <?php
                if ($list) {
                    foreach ($list as $key => $value) {
                        if ($itemsTotal[$key] > count($value)) {
                            ?>
                <h3 class="b-header b-header_type_h3"><?php echo CHtml::link($types[$key]['listTitle'], array('/site/type', 'type'=>$types[$key]['alias']), array('class'=>'')); ?></h3>
                            <?php
                        }
                        else {
                            ?>
                <h3 class="b-header b-header_type_h3"><?php echo $types[$key]['listTitle']; ?></h3>
                <?php
                        }
                    ?>
                <div class="edu-full-list">
                    <ul class="edu-full-list__list">
                    <?php
                    foreach ($value as $item) {
                        ?>
                        <li class="edu-full-list__item"><a href="<?php echo CHtml::normalizeUrl(array('/site/showCard', 'id'=>$item['id'])); ?>"><?php echo $item['title']; ?></a></li>
                        <?php
                    }
                    ?>
                        <li class="edu-full-list__item"><?php echo CHtml::link('Весь список &rarr;', array('/site/type', 'type'=>$types[$key]['alias']), array('class'=>'')); ?></li>
                    </ul>
                </div>
                    <?php
                    }
                }
                ?>
            </div>
            <div class="g-col-26 g-span-24">
                <?php
                $this->renderPartial('application.views.blocks.bannerRight', array(
                ));
                ?>
            </div>
        </div>
    </div>
</div>