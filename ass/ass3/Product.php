<?php
class Product {
    public $productID;
    public $productName;
    public $category;
    public $description;
    public $price;
    public $rating;
    public $imageName;
    public $quantity; 


    // constructor
    public function __construct($id, $name, $category, $description, $price, $rating, $imageName, $quantity) {
        $this->productID = $id;
        $this->productName = $name;
        $this->category = $category;
        $this->description = $description;
        $this->price = $price;
        $this->rating = $rating;
        $this->imageName = $imageName;
        $this->quantity = $quantity; 
    }


    // setters and getters
    public function getProductID() {
        return $this->productID;
    }

    public function setProductID($productID) {
        $this->productID = $productID;
    }

    public function getProductName() {
        return $this->productName;
    }

    public function setProductName($productName) {
        $this->productName = $productName;
    }

    public function getCategory() {
        return $this->category;
    }

    public function setCategory($category) {
        $this->category = $category;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function getRating() {
        return $this->rating;
    }

    public function setRating($rating) {
        $this->rating = $rating;
    }

    public function getImageName() {
        return $this->imageName;
    }

    public function setImageName($imageName) {
        $this->imageName = $imageName;
    }

    public function getQuantity() {
    return $this->quantity;
}

  //  HTML Table Row 
  public function displayInTable() {
    $imagePath = "images/" . $this->imageName;

    return "
    <tr>
        <td><img src='$imagePath' alt='Product Image' width='85'></td>
       <td>
          <a href='view.php?id=<?php echo $this->productID; ?>' class='product-id'>
              <?php echo $this->productID; ?>
          </a>
        </td>
        <td>" . $this->productName . "</td>
        <td>" . $this->category . "</td>
        <td>" . $this->description . "</td>
        <td>" . $this->price . "</td>
        <td class='actions'>
            <form method='GET' action='edit.php'>
                <input type='hidden' name='id' value='" . $this->productID . "'>
                <button type='submit'>
                    <img src='images/pencel.jpeg' alt='Edit' title='Edit' width='25' height='25'>
                </button>
            </form>
            <form method='GET' action='delete.php'>
                <input type='hidden' name='id' value='" . $this->productID . "'>
                <button type='submit'>
                    <img src='images/delete.jpeg' alt='Delete' title='Delete' width='25' height='25'>
                </button>
            </form>
        </td>
    </tr>";
}

    // Get Product by ID
    public static function getProductById($id) {
        include('dbconfig.inc.php'); 

        $sql = "SELECT id, product_name, category, description, price, rating, image_name, quantity FROM products WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return new Product(
    $row['id'],
    $row['product_name'],
    $row['category'],
    $row['description'],
    $row['price'],
    isset($row['rating']) ? $row['rating'] : 0,
    $row['image_name'],
    isset($row['quantity']) ? $row['quantity'] : 0
);

        } else {
            return null;
        }
    }


    public static function displayProductPage($id) {
        // get  product using its ID
        $product = self::getProductById($id);
    
        //  show an error message and exit  If product is not found
        if ($product === null) {
            echo "<p>Product not found.</p>";
            return;
        }
    
        //  displaying product details
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <title>Product Details: " . $product->getProductName() . "</title>
        </head>
        <body>
    
        <h1>Product Details</h1>
    
        <!-- display  image -->
        <div>
            <img src='images/" . $product->getImageName() . "' 
                 alt='" . $product->getProductName() . "' width='300' />
        </div>
    
        <!-- display ID and Name -->
        <h2>
            Product ID: " . $product->getProductID() . " - 
            Product Name: " . $product->getProductName() . "
        </h2>
    
        <!-- display  details -->
       <ul>
           <li><strong>Category:</strong> " . $product->getCategory() . "</li>
           <li><strong>Rating:</strong> " . $product->getRating() . "</li>
           <li><strong>Price:</strong> $" . number_format($product->getPrice(), 2) . "</li>
           <li><strong>Quantity:</strong> " . $product->getQuantity() . "</li>
       </ul>

    
        <!-- display  description -->
        <h2><strong>Description :</strong></h2>
        <p>" . nl2br($product->getDescription()) . "</p>
    
        <!-- Link to go back to the products table -->
        <p><a href='products.php'>Back to Products Table</a></p>
    
        </body>
        </html>";
    }
    
    
    

public function displayAsCard() {
    $stockClass = ($this->quantity <= 5) ? 'low-stock' : 'normal-stock';
    return '
        <div class="product-card">
            <h3 class="product-id">Product ID: ' . $this->productID . '</h3>
            
            <img src="images/' . $this->imageName . '" alt="' . $this->productName . '" class="product-image">
            
            <h4 class="product-name" tabindex="0">
                ' . $this->productName . '
                <section class="tooltip-box">
                    <h2 class="' . $stockClass . '">Quantity: ' . $this->quantity . '</h2>
                    <p>' . $this->description . '</p>
                </section>
            </h4>
            
            <!-- Display "Category:" label next to category -->
            <p class="category-badge ' . $this->category . '">Category: ' . $this->category . '</p>
            <p class="price">Price: $' . number_format($this->price, 2) . '</p>
            
            <nav class="product-actions">
                <form action="view.php" method="get">
                    <input type="hidden" name="id" value="' . $this->productID . '">
                    <button class="view-btn" type="submit">View</button>
                </form>
                <form method="post" action="addToCart.php">
                    <input type="hidden" name="productID" value="' . $this->productID . '">
                    <button type="submit" name="addToCart" class="addTOcart-btn">Add to Cart</button>
                </form>
            </nav>
        </div>
    ';
}




}
?>
