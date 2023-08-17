<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include ("connection.php");

// Fetch categories from the database
$categoryOptions = [];
$categoryQuery = "SELECT category, category_image FROM category";
$categoryResult = $conn->query($categoryQuery);
if ($categoryResult->num_rows > 0) {
    while ($row = $categoryResult->fetch_assoc()) {
        $categoryOptions[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the selected category from the form
    $selectedCategory = $_POST["selected_category"];

    // Check if the category is not empty
    if (!empty($selectedCategory)) {
        // Handle image upload ...

        // Rest of the code for image upload and insertion
    } else {
        echo "Please select a category.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Jade's Food Recipe</title>
    <link rel="stylesheet" href="styles.css"> <!-- Include your CSS file -->
    <!-- Add these lines to your <head> section -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<?php include ("partials/nav.header.php"); ?>

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
