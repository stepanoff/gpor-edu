<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
      <button type="button" class="btn btn-navbar collapsed" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="brand" href="./index.html"><?php echo Yii::app()->params['siteName']; ?></a>
      <div class="nav-collapse collapse" style="height: 0px; ">
        <ul class="nav">
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
</div>
