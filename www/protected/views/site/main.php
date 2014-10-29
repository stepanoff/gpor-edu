<style type="text/css">
.edu-full-list {background: #f4f4f4; padding: 20px 20px 10px 20px;}
.edu-full-list__item {margin-bottom: 10px;}
</style>
<div class="g-48">
    <div class="g-col-1 g-span-27 g-main-col-1">

        <?php
        $this->renderPartial('application.views.blocks.objectsOnMain', array(
            'items' => $itemsBlock1,
            'title' => 'Лучшие вузы Екатеринбурга',
        ));
        ?>

        <?php
        $this->renderPartial('application.views.blocks.objectsOnMain', array(
            'items' => $itemsBlock2,
            'title' => 'Лучшие колледжы Екатеринбурга',
        ));
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
                    ?>
                <h3 class="b-header b-header_type_h3">Все ВУЗы Екатеринбурга</h3>
                <div class="edu-full-list">
                    <ul class="edu-full-list__list">
                    <?php
                    foreach ($list as $item) {
                        ?>
                        <li class="edu-full-list__item"><a href="<?php echo CHtml::normalizeUrl(array('/site/showCard', 'id'=>$item['id'])); ?>"><?php echo $item['title']; ?></a></li>
                        <?php
                    }
                    ?>
                    </ul>
                </div>
                    <?php
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