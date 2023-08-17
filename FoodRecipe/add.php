<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("connection.php");


$categoryOptions = [];
$categoryQuery = "SELECT category, category_image FROM category";
$categoryResult = $conn->query($categoryQuery);
if ($categoryResult->num_rows > 0) {
    while ($row = $categoryResult->fetch_assoc()) {
        $categoryOptions[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newCategory = $_POST["category"];

    if (!empty($newCategory)) {
        $checkQuery = "SELECT * FROM category WHERE category = '$newCategory'";
        $result = $conn->query($checkQuery);

        if ($result->num_rows === 0) {
            $targetDir = "img/";
            $targetFile = $targetDir . basename($_FILES["category_image"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            if (isset($_POST["submit"])) {
                $check = getimagesize($_FILES["category_image"]["tmp_name"]);
                if ($check !== false) {
                    $uploadOk = 1;
                } else {
                    echo "File is not an image.";
                    $uploadOk = 0;
                }
            }

            if (file_exists($targetFile)) {
                echo "Sorry, file already exists.";
                $uploadOk = 0;
            }

            if ($_FILES["category_image"]["size"] > 500000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }

            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
            } else {
                if (move_uploaded_file($_FILES["category_image"]["tmp_name"], $targetFile)) {
                    $insertQuery = "INSERT INTO category (category, category_image) VALUES ('$newCategory', '$targetFile')";
                    if ($conn->query($insertQuery) === TRUE) {
                        echo "Category added successfully.";
                    } else {
                        echo "Error adding category: " . $conn->error;
                    }
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        } else {
            echo "Category already exists.";
        }
    } else {
        echo "Category cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Jade's Food Recipe</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <?php include("partials/nav.header.php"); ?>
    <div class="container">
        <h2>Add Category</h2>
        <div class="col-md-6 mt-2">
            <img id="imagePreview" src="#" alt="Image Preview" style="max-width: 200px; height: 200px; display: none; border: solid 1px;">
        </div>
        <form class="form" method="post" enctype="multipart/form-data">
            <div class="input-group col-md-6">
                <input type="text" class="form-control" name="category" placeholder="Enter Category">
                <input type="file" class="form-control" name="category_image" accept="image/*" onchange="previewImage(event)">
            </div>
            <div class="input-group col-md-6 mt-2 d-flex justify-content-end">
                <button class="btn btn-success" type="submit" name="submit">Add Category</button>
            </div>
        </form>
        <script>
            function previewImage(event) {
                var image = document.getElementById('imagePreview');
                image.style.display = 'block';
                image.src = URL.createObjectURL(event.target.files[0]);
            }
        </script>
    </div>
    <h2>Category</h2>

    <!-- Display categories and images -->
    <div class="row mt-3 justify-content-center">
        <?php foreach ($categoryOptions as $category) { ?>
            <div class="card m-1 col-md-3 center">
                <img src="<?php echo $category['category_image']; ?>" class="card-img-top mx-auto mt-2" alt="<?php echo $category['category']; ?>" 
                style="max-width: 200px; object-fit:contain; ">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $category['category']; ?></h5>
                </div>
            </div>
        <?php } ?>
    </div>
</body>
</html>
