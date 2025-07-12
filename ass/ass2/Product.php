<?php
class Product {
    private $productID;
    private $productName;
    private $category;
    private $description;
    private $price;
    private $rating;
    private $imageName;

    // constructor
    public function __construct($productID, $productName, $category, $description, $price, $rating, $imageName) {
        $this->productID = $productID;
        $this->productName = $productName;
        $this->category = $category;
        $this->description = $description;
        $this->price = $price;
        $this->rating = $rating;
        $this->imageName = $imageName;
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

  //  HTML Table Row 
  public function displayInTable() {
    $imagePath = "images/" . $this->imageName;

    return "
    <tr>
        <td><img src='$imagePath' alt='Product Image' width='85'></td>
        <td><a href='view.php?id=" . $this->productID . "'>" . $this->productID . "</a></td>
        <td>" . $this->productName . "</td>
        <td>" . $this->category . "</td>
        <td>" . $this->description . "</td>
        <td>" . $this->price . "</td>
        <td class='actions'>
            <form method='GET' action='edit.php' style='display:inline;'>
                <input type='hidden' name='id' value='" . $this->productID . "'>
                <button type='submit' style='border:none; background:none;'>
                    <img src='images/pencel.jpeg' alt='Edit' title='Edit' width='25' height='25'>
                </button>
            </form>
            <form method='GET' action='delete.php' style='display:inline; margin-left:5px;'>
                <input type='hidden' name='id' value='" . $this->productID . "'>
                <button type='submit' style='border:none; background:none;'>
                    <img src='images/delete.jpeg' alt='Delete' title='Delete' width='25' height='25'>
                </button>
            </form>
        </td>
    </tr>";
}




   
    
    // Get Product by ID
    public static function getProductById($id) {
        include('dbconfig.inc.php'); 

        $sql = "SELECT id, product_name, category, description, price, rating, image_name FROM products WHERE id = ?";
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
                $row['image_name']
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
        </ul>
    
        <!-- display  description -->
        <h2><strong>Description :</strong></h2>
        <p>" . nl2br($product->getDescription()) . "</p>
    
        <!-- Link to go back to the products table -->
        <p><a href='products.php'>Back to Products Table</a></p>
    
        </body>
        </html>";
    }
    
    
    
}

?>
