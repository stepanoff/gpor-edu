<div class="submenu_items context">
  <?php 
  $items = array (
    array(
        'label' => 'Главная',
        'route' => array('/site/index'),
        'regexp' => array('/'),
      ),
    array(
        'label' => 'ВУЗы',
        'route' => array('/site/type', 'type' => 'vuz'),
        'regexp' => array('/vuz/'),
      ),
    array(
        'label' => 'Колледжи',
        'route' => array('/site/type', 'type' => 'college'),
        'regexp' => array('.*/vuz/.*'),
      ),
    array(
        'label' => 'Языковые центры',
        'route' => array('/site/type', 'type' => 'linvo'),
        'regexp' => array('.*/lingvo/.*'),
      ),
    /*
    array(
        'label' => 'Бизнес-образование',
        'route' => array('/site/type', 'type' => 'bo'),
        'regexp' => array('/bo/'),
      ),
    */
    array(
        'label' => 'Дополнительное образование',
        'route' => array('/site/type', 'type' => 'do'),
        'regexp' => array('.*/do/.*'),
      ),
  );

/*
  <div class="submenu_items__item submenu_items__item-current">
    <div class="submenu_items__item-current-pad">
      <div class="submenu_items__item-current__content">
        <i class="submenu_items__item-current-l"></i>
        <i class="submenu_items__item-current-r"></i>
        <i class="submenu_items__item-current-bottom"></i>
        <a class="submenu_items__item-link" href="/realty/">Недвижимость Екатеринбурга</a>
      </div></div><div class="b-submenu__separator">
    </div>
  </div>
*/
    $i = 0;
    foreach ($items as $item) {
        $i++;
        echo '<div class="submenu_items__item">';
        echo CHtml::link($item['label'], $item['route'], array('class'=>'submenu_items__item-link'));
        if ($i < count($items))
          echo '<div class="b-submenu__separator"></div>';
        echo '</div>';
    }
  ?>
</div>