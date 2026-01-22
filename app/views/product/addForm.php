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

<h1>add form</h1>

<form action="/product_store" method="post" enctype="multipart/form-data">
    <div>
        <label>Title</label>
        <input type="text" name="title" required>
    </div>

    <div>
        <label>Category</label>
        <input type="number" name="category_id" required>
    </div>

    <div>
        <label>Images</label>
        <input type="file" name="images[]" multiple accept="image/jpeg,image/png">
    </div>

    <button type="submit">Send</button>
</form>


</body>
</html>