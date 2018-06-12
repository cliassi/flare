<?php
require 'framework/core/vendor/faker/src/autoload.php';
$faker = Faker\Factory::create();
?>
<br>
<style type="text/css">
.sidebar-nav{
  /*display: none;*/
}

#page-wrapper{
  margin: 0px !important;
}
</style>

<h1>
	<?php $faker->name; ?>
</h1>