<?php
        if ($items) {

            ?>
            <h1 class="b-header b-header_type_h3"><?php echo $title; ?></h1>
            <?php
            $i = 0;
            $c = 0;
            foreach ($items as $item) {
                if (!$i || !$i%3) {
                    ?>
                    <div class="g-48">
                    <?php
                }
                ?>
            <div class="g-col-<?php echo (1+$c*16); ?> g-span-16">
                <div style="background-image: url(<?php echo Yii::app()->fileManager->getImageThumbUrlByUid ($item->image, Institution::IMAGE_WIDTH_MAIN, false); ?>);" class="b-news-item b-news-item_layout_annonce">
                    <a href="<?php echo CHtml::normalizeUrl(array('/site/showCard', 'id'=>$item->id)); ?>" class="b-news-item__pic__shadow"></a>
                    <span class="b-news-item__title">
                        <a class="b-news-item__title__link" href="<?php echo CHtml::normalizeUrl(array('/site/showCard', 'id'=>$item->id)); ?>"><?php echo $item->getFullTitle(); ?></a>
                    </span>
                </div>
            </div>
                <?php
                $c++;
                $i++;
                if (!$i%3) {
                    $c = 0;
                    ?>
                    </div>
                    <?php
                }
            }

            if ($i%3) {
                ?>
                </div>
                <?php
            }
            ?>
            <?php
        }
        ?>