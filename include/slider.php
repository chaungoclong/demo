<!-- slide -->
<div id="slide" class="carousel slide" data-ride="carousel" data-interval="3000">
  <!-- indicators -->
  <?php 
  $catID = $_GET['cat'] ?? null;
  if($catID) {
   $getSlideSQL = "  
   SELECT * FROM db_slider
   WHERE cat_id = '{$catID}'
   ORDER BY sld_pos ASC
   LIMIT 10
   ";
 } else {
  $getSlideSQL = "  
  SELECT * FROM db_slider
  ORDER BY sld_pos ASC
  LIMIT 10
  ";
}

$listSlide = get_list($getSlideSQL, 2);
$numSlide = 0;
if($listSlide) {
  $numSlide = $listSlide->num_rows;
}

?>
<div class="carousel-indicators">
  <?php for ($i=0; $i < $numSlide ; $i++): ?> 
    <li data-target="#slide" data-slide-to="<?= $i; ?>" class="<?php if($i == 0) echo 'active'; ?>"></li>
  <?php endfor ?>
  
</div>
<!-- slide content -->
<div class="carousel-inner">
  <?php $index = 0; ?>
  <?php foreach ($listSlide as $key => $slide): ?>
    <div class="<?php echo ($index == 0) ? 'carousel-item active' : 'carousel-item'; ?>">
      <a href='<?= create_link(base_url("product.php"), ["cat"=>$slide["cat_id"]]); ?>'>
        <img src="<?= $slide['sld_image']; ?>" alt="">
      </a>
    </div>
    <?php $index++; ?>
  <?php endforeach ?>
</div>
<!-- slide control -->
<?php if ($numSlide > 1): ?>
  <a href="#slide" class="carousel-control-prev" data-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </a>
<?php endif ?>
<a href="#slide" class="carousel-control-next" data-slide="next">
  <span class="carousel-control-next-icon"></span>
</a>
</div>
<!-- /slide -->
