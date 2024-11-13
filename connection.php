<?php
$newConnection = new Connection();
session_start();
class Connection
{
    private $server = "mysql:host=localhost;dbname=midtermdb";
    private $username = "root";
    private $password = "";
    private $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ];
    protected $conn;

    public function openConnection(): PDO
    {
        try {
            $this->conn = new PDO($this->server, $this->username, $this->password, $this->options);
            return $this->conn;
        } catch (PDOException $e) {
            echo "There is a problem is the connection: " . $e->getMessage();
        }
    }
}

class ProductCRUD extends Connection
{
    public function createProduct($product_name, $description, $price, $category, $stock_quantity, $image_url)
    {
        $sql = "INSERT INTO products_table (product_name, description, price, category, stock_quantity, image_url) 
                VALUES (:product_name, :description, :price, :category, :stock_quantity, :image_url)";
        $stmt = $this->openConnection()->prepare($sql);
        $stmt->execute([
            'product_name' => $product_name,
            'description' => $description,
            'price' => $price,
            'category' => $category,
            'stock_quantity' => $stock_quantity,
            'image_url' => $image_url
        ]);
    }

    public function readProducts()
    {
        $sql = "SELECT * FROM products_table";
        $stmt = $this->openConnection()->query($sql);
        return $stmt->fetchAll();
    }

    public function updateProduct($product_id, $product_name, $description, $price, $category, $stock_quantity, $image_url)
    {
        $sql = "UPDATE products_table SET product_name = :product_name, description = :description, price = :price, 
                category = :category, stock_quantity = :stock_quantity, image_url = :image_url 
                WHERE product_id = :product_id";
        $stmt = $this->openConnection()->prepare($sql);
        $stmt->execute([
            'product_id' => $product_id,
            'product_name' => $product_name,
            'description' => $description,
            'price' => $price,
            'category' => $category,
            'stock_quantity' => $stock_quantity,
            'image_url' => $image_url
        ]);
    }

    public function deleteProduct($product_id)
    {
        $sql = "DELETE FROM products_table WHERE product_id = :product_id";
        $stmt = $this->openConnection()->prepare($sql);
        $stmt->execute(['product_id' => $product_id]);
    }
}

class UserCRUD extends Connection
{
    public function login($email, $password)
    {
        try {
            $stmt = $this->openConnection()->prepare("SELECT * FROM users_table WHERE email = :email AND password = :password");
            $stmt->execute(['email' => $email, 'password' => $password]);
            $user = $stmt->fetch(PDO::FETCH_OBJ);

            if ($user) {
                $_SESSION['user_id'] = $user->user_id;
                $_SESSION['username'] = $user->username;
                return $user;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function createUser($email, $username, $password, $user_type, $first_name, $last_name, $phone_number, $address_line1, $address_line2, $city)
    {
        $sql = "INSERT INTO users_table (email, username, password, user_type, first_name, last_name, phone_number, address_line1, address_line2, city)
                VALUES (:email, :username, :password, :user_type, :first_name, :last_name, :phone_number, :address_line1, :address_line2, :city)";
        $stmt = $this->openConnection()->prepare($sql);
        $stmt->execute([
            'email' => $email,
            'username' => $username,
            'password' => $password,
            'user_type' => $user_type,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'phone_number' => $phone_number,
            'address_line1' => $address_line1,
            'address_line2' => $address_line2,
            'city' => $city
        ]);
    }

    public function readUsers()
    {
        $sql = "SELECT * FROM users_table";
        $stmt = $this->openConnection()->query($sql);
        return $stmt->fetchAll();
    }

    public function updateUser($user_id, $email, $username, $password, $user_type, $first_name, $last_name, $phone_number, $address_line1, $address_line2, $city)
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $sql = "UPDATE users_table SET email = :email, username = :username, password = :password, 
                user_type = :user_type, first_name = :first_name, last_name = :last_name, 
                phone_number = :phone_number, address_line1 = :address_line1, address_line2 = :address_line2, 
                city = :city WHERE user_id = :user_id";
        $stmt = $this->openConnection()->prepare($sql);
        $stmt->execute([
            'user_id' => $user_id,
            'email' => $email,
            'username' => $username,
            'password' => $hashedPassword,
            'user_type' => $user_type,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'phone_number' => $phone_number,
            'address_line1' => $address_line1,
            'address_line2' => $address_line2,
            'city' => $city
        ]);
    }

    public function deleteUser($user_id)
    {
        $sql = "DELETE FROM users_table WHERE user_id = :user_id";
        $stmt = $this->openConnection()->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
    }
}

class CategoryCRUD extends Connection
{
    public function createCategory($category_name)
    {
        $sql = "INSERT INTO category_table (category_name) VALUES (:category_name)";
        $stmt = $this->openConnection()->prepare($sql);
        $stmt->execute(['category_name' => $category_name]);
    }

    public function readCategories()
    {
        $sql = "SELECT * FROM category_table";
        $stmt = $this->openConnection()->query($sql);
        return $stmt->fetchAll();
    }

    public function updateCategory($category_id, $category_name)
    {
        $sql = "UPDATE category_table SET category_name = :category_name WHERE category_id = :category_id";
        $stmt = $this->openConnection()->prepare($sql);
        $stmt->execute(['category_id' => $category_id, 'category_name' => $category_name]);
    }

    public function deleteCategory($category_id)
    {
        $sql = "DELETE FROM category_table WHERE category_id = :category_id";
        $stmt = $this->openConnection()->prepare($sql);
        $stmt->execute(['category_id' => $category_id]);
    }
}

class CartCRUD extends Connection
{
    public function buyNow($user_id, $product_id)
    {
        // Update stock quantity for the product
        $stmt = $this->openConnection()->prepare("UPDATE products_table SET stock_quantity = stock_quantity - 1 WHERE product_id = :product_id");
        $stmt->execute(['product_id' => $product_id]);
    }
    public function checkoutItem($user_id, $product_id)
    {
        $stmt = $this->openConnection()->prepare("DELETE FROM cart_table WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
    }

    public function checkoutAllItems($user_id)
    {
        $cartItems = $this->getCartItems($user_id);

        foreach ($cartItems as $item) {
            $stmt = $this->openConnection()->prepare("UPDATE products_table SET stock_quantity = stock_quantity - :quantity WHERE product_id = :product_id");
            $stmt->execute(['quantity' => $item->quantity, 'product_id' => $item->product_id]);
        }

        $this->clearCart($user_id);
    }

    public function getCartItems($user_id)
    {
        $stmt = $this->openConnection()->prepare("SELECT c.*, p.product_name, p.price, p.image_url
                                      FROM cart_table c
                                      JOIN products_table p ON c.product_id = p.product_id
                                      WHERE c.user_id = :user_id");
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll();
    }

    public function addToCart($user_id, $product_id)
    {
        $stmt = $this->openConnection()->prepare("INSERT INTO cart_table (user_id, product_id, quantity) 
                                                  VALUES (:user_id, :product_id, 1)
                                                  ON DUPLICATE KEY UPDATE quantity = quantity + 1");
        $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
    }

    public function getUserCart($user_id)
    {
        $sql = "SELECT * FROM cart_table WHERE user_id = :user_id";
        $stmt = $this->openConnection()->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll();
    }

    public function updateCartQuantity($cart_id, $quantity)
    {
        $sql = "UPDATE cart_table SET quantity = :quantity WHERE cart_id = :cart_id";
        $stmt = $this->openConnection()->prepare($sql);
        $stmt->execute(['cart_id' => $cart_id, 'quantity' => $quantity]);
    }

    public function removeFromCart($cart_id)
    {
        $sql = "DELETE FROM cart_table WHERE cart_id = :cart_id";
        $stmt = $this->openConnection()->prepare($sql);
        $stmt->execute(['cart_id' => $cart_id]);
    }

    public function clearCart($user_id)
    {
        $stmt = $this->openConnection()->prepare("DELETE FROM cart_table WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $user_id]);
    }
}