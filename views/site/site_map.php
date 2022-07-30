<?php

$this->title = 'Sitemap';
?>
<ul>
<?php
echo '<li>', yii\helpers\Html::a('Login', ['site/login']) , '</li>';
echo '<li>', yii\helpers\Html::a('Validate Certificate', ['site/validate']), '</li>';

?>
</ul>