<?php
include('dbconfig.inc.php');  // conaction with dadta base 
include('Product.php');  //  Product class file
include("header.php"); 


$productNameFilter = isset($_GET['name']) ? $_GET['name'] : ''; // name filter
$productPriceFilter = isset($_GET['price']) && is_numeric($_GET['price']) ? (float)$_GET['price'] : '';  //  price filter
$productCategoryFilter = isset($_GET['category']) ? $_GET['category'] : '';  // category filter
//...........................................................................................................................
$whereClauses = [];  //Array store the WHERE 
$queryParams = [];  // Array  store the values 

// Check  name filter 
if ($productNameFilter !== '') {
    $whereClauses[] = "product_name LIKE ?";  // Add condition for  name
    $queryParams[] = "%{$productNameFilter}%";  // Add value for  name filter
}
// Check  price filter 
if ($productPriceFilter !== '') {
    $whereClauses[] = "price <= ?";  //  condition  price
    $queryParams[] = $productPriceFilter;  // Add value for price filter
}
// Check category filter 
if ($productCategoryFilter !== '' && $productCategoryFilter !== 'all') {
    $whereClauses[] = "category = ?";  // condition  category
    $queryParams[] = $productCategoryFilter;  // value  category 
}
//........................................................................................................................
//  query  for  database
$sqlQuery = "SELECT id, product_name, category, description, price, rating, image_name, quantity FROM products";

if (count($whereClauses) > 0) {
    $sqlQuery .= ' WHERE ';//when adding a new condition  we will add WHERE for the query
    $conditions = '';
    foreach ($whereClauses as $index => $clause) {
        $conditions .= $clause;
        if ($index < count($whereClauses) - 1) {
            $conditions .= ' AND ';
        }
    }
    $sqlQuery .= $conditions;
}

// Prepare and execute the query with the filter parameters
$stmt = $pdo->prepare($sqlQuery);
$stmt->execute($queryParams);
$resultSet = $stmt;

//execute  query using filter 
$categoryStmt = $pdo->query("SELECT DISTINCT category FROM products");
$productCategories = $categoryStmt->fetchAll(PDO::FETCH_COLUMN);

// html code

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products Table</title>
    <link rel="stylesheet" href="StyleCSS.css">
</head>

<body>
  
<fieldset>
    <legend>Advanced Product Search</legend>

    <div class="action-info">
        <img src="images/AddButtton.jpg" alt="Add Product" width="30" height="30">
        To Add a new Product click on the following link 
        <a href="add.php">Add Product</a>.
    </div>

    <div class="grid-container">
    <aside class="search-form">
        <form method="get" action="">
            <label>
                Name
                <input type="text" name="name" placeholder="Product Name" value="<?php echo htmlspecialchars($productNameFilter); ?>" />
            </label>
            <label>
                Price
                <input type="text" name="price" placeholder="Product Price" value="<?php echo htmlspecialchars($productPriceFilter); ?>" />
            </label>
            <label>
                Category
                <select name="category">
                    <option value="all">All Categories</option>
                    <?php foreach ($productCategories as $category): ?>
                        <option value="<?php echo htmlspecialchars($category); ?>" <?php if ($category === $productCategoryFilter) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($category); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <button type="submit">Filter</button>
        </form>
    </aside>
                   

    <main class="product-grid">
    <?php
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($rows) > 0) {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $productsPerPage = 10;
        $startIndex = ($page - 1) * $productsPerPage;
        $pagedRows = array_slice($rows, $startIndex, $productsPerPage);

 $pagedRows = array_slice($rows, $startIndex, $productsPerPage);

foreach ($pagedRows as $row) {
    $product = new Product(
        $row['id'],
        $row['product_name'],
        $row['category'],
        $row['description'],
        $row['price'],
        isset($row['rating']) ? $row['rating'] : 0,
        $row['image_name'],
        isset($row['quantity']) ? $row['quantity'] : 0 
    );

    echo $product->displayAsCard(); 
}


        // Row 3: Pagination
$totalPages = ceil(count($rows) / $productsPerPage);
echo '<div class="pagination-controls">';

if ($page > 1) {
    echo '<a class="pagination-button" href="?page=' . ($page - 1) . '">Previous</a>';
} else {
    echo '<span class="pagination-button disabled">Previous</span>';
}

if ($page < $totalPages) {
    echo '<a class="pagination-button" href="?page=' . ($page + 1) . '">Next</a>';
} else {
    echo '<span class="pagination-button disabled">Next</span>';
}

echo '</div>';

    }
    ?>
</main>

</div>
</fieldset>

<?php
$pdo = null;
?>

<footer>
    <table>
        <tr>
            <td>
                <p>
                    <b>Customer Support:</b><br>
                    <img src="images/phone.jpg" alt="Phone" width="30" height="30">
                    +972-599-314087<br>
                    <a href="mailto:support@luckyyouare.com">
                        <img src="images/email.jpg" alt="Email" width="30" height="30">
                        support@luckyyouare.com
                    </a>
                </p>
                <p>
                    <a href="ContactUs.html">
                        <img src="images/contatus.jpg" alt="Contact Us" width="30" height="30">
                        Contact Us
                    </a>
                </p>
            </td>
        </tr>
    </table>
    <p>
        &copy; 2025 LuckyYouAre. All rights reserved.
    </p>
</footer>

</body>
</html>

