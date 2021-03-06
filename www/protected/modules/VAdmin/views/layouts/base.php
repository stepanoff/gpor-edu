<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Template &middot; Bootstrap</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

  </head>

  <body>

    <?php
        $this->widget('VAdminMenuWidget', array('baseLink' => '/admin/adminInstitution/', 'items' => array(
            array ('title' => 'Учебные заведения', 'link' => '/admin/adminInstitution/'),
        )));
    ?>

    <div class="container">

      <?php echo $content; ?>

      <div class="footer">
        <p>&copy; Company 2013</p>
      </div>

    </div> <!-- /container -->

    <?php if (Yii::app()->getComponent('informer')) { $this->widget('VMessagesWidget', array()); } ?>

  </body>
</html>
