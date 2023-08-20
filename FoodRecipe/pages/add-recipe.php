<?php

$categoryOptions = [];
$categoryQuery = "SELECT category FROM category";
$categoryResult = $conn->query($categoryQuery);

if ($categoryResult->num_rows > 0) {
    while ($row = $categoryResult->fetch_assoc()) {
        $categoryOptions[] = $row['category'];
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


<div class="container">
    <h2>Add Recipe</h2>
    <form class="form" method="post" enctype="multipart/form-data">
        <div class="row g-2">
            <div class="col-md">
                <div class="form-floating">
                    <input type="text" class="form-control" id="floatingInputGrid" placeholder="Enter Recipe's Name">
                    <label for="floatingInputGrid">Recipe's Name</label>
                </div>
            </div>
            <div class="col-md">
                <div class="form-floating">
                    <select class="form-select" id="floatingSelectGrid" name="selected_category">
                        <option selected>Select Category</option>
                        <?php foreach ($categoryOptions as $category) { ?>
                            <option value="<?php echo $category; ?>"><?php echo $category; ?></option>
                        <?php } ?>
                    </select>
                    <label for="floatingSelectGrid">Category</label>
                </div>
            </div>
            <div class="col-md">
                <div class="form-floating">
                    <textarea class="form-control" id="floatingInputGrid" placeholder="short description"></textarea>
                    <label for="floatingInputGrid">Short Description</label>
                </div>
            </div>
        </div>
        <div class="row g-2 mt-2">
            <div class="col-md">
                <div class="form-floating bg-none">
                    <input type="file" class="form-control" id="floatingInputGrid" accept="image/*">
                    <label for="floatingInputGrid">Upload Image</label>
                </div>
            </div>
            <div class="col-md">
                <div class="accordion accordion-flush" id="accordionExample">
                    <div class="accordion-item">
                        <button class="accordion-button add-accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne" style="border: solid 1px lightgrey; border-radius:5px;">
                            Add Ingredients
                        </button>

                    </div>
                </div>

                <script>
                    const accordion = document.getElementById("accordionExample");
                    let isAccordionsHidden = false; // To track the state of accordion items

                    function toggleAccordions() {
                        const accordionItems = accordion.querySelectorAll('.accordion-item');
                        isAccordionsHidden = !isAccordionsHidden;

                        accordionItems.forEach(item => {
                            const collapseElement = item.querySelector('.accordion-collapse');

                            if (isAccordionsHidden) {
                                collapseElement.classList.remove('show');
                            } else {
                                collapseElement.classList.add('show');
                            }
                        });
                    }

                    function addAccordionItem() {
                        const accordionItemCount = accordion.querySelectorAll('.accordion-item').length;

                        const newAccordionItem = `
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${accordionItemCount}" aria-expanded="true" aria-controls="collapse${accordionItemCount}">
            Ingredients #${accordionItemCount}
          </button>
        </h2>
        <div id="collapse${accordionItemCount}" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
          <div class="accordion-body">
            <input type="text" class="form-control" placeholder="Enter text">
            <button class="btn btn-primary mt-3 add-accordion-button">Add Another Accordion</button>
          </div>
        </div>
      </div>
    `;

                        accordion.innerHTML += newAccordionItem;
                    }

                    accordion.addEventListener("click", function(event) {
                        if (event.target.classList.contains("add-accordion-button")) {
                            addAccordionItem();
                        } else if (event.target.classList.contains("accordion-button")) {
                            toggleAccordions();
                        }
                    });
                </script>


            </div>
            <div class="col-md">
                <div class="form-floating">
                    <textarea class="form-control" id="floatingInputGrid" placeholder="short description"></textarea>
                    <label for="floatingInputGrid">Short Description</label>
                </div>
            </div>
        </div>
    </form>
</div>