<div class="navbar navbar-inverse">
  <div class="container">

    <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-9">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo CHtml::normalizeUrl(array($baseLink)); ?>"><?php echo Yii::app()->params['siteName']; ?></a>
    </div>

      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-9">
        <ul class="nav navbar-nav">
            <?php
            foreach ($items as $item) {
                ?>
                <li class="<?php echo isset($item['active']) && $item['active'] ? 'active' : ''; ?>">
                  <a href="<?php echo isset($item['link']) ? $item['link'] : ''; ?>"><?php echo $item['title']; ?></a>
                </li>
                <?php
            }
            ?>
        </ul>
      </div>

  </div>
</div>

