<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>


<h1>home</h1>

<a href="/add_product">add_product</a>

<h1>Products</h1>
<?php foreach ($products as $product) {?>

      <div>

    <p>category:  <?php echo $product->getCategory()->getTitle(); ?></p>
    <p>prod title: <?php echo $product->getTitle(); ?></p>
    <p>img:</p>
<?php foreach ($product->getImages() as $img) {?>
        <img src="public/uploads/<?php echo $img; ?>" alt="">
<?php }?>
      </div>
<?php }?>
</body>
</html>