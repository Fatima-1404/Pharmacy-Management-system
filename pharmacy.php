<?php
session_start();

class Product {
    protected $name;
    protected $price;

    public function __construct($name, $price) {
        $this->name = $name;
        $this->price = $price;
    }

    public function getName() { return $this->name; }
    public function getPrice() { return number_format($this->price, 2); }
}

class Medicine extends Product {
    private $type; // e.g., Tablet, Syrup
    private $stock;

    public function __construct($name, $price, $type, $stock) {
        // Calling parent constructor
        parent::__construct($name, $price);
        $this->type = $type;
        $this->stock = $stock;
    }

    public function getType() { return $this->type; }
    public function getStock() { return $this->stock; }
}


if (!isset($_SESSION['inventory'])) {
    $_SESSION['inventory'] = [];
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_medicine'])) {
    $newName = $_POST['m_name'];
    $newPrice = $_POST['m_price'];
    $newType = $_POST['m_type'];
    $newStock = $_POST['m_stock'];

    // Create new Object
    $medicineObj = new Medicine($newName, $newPrice, $newType, $newStock);

    // Store in Session Array
    $_SESSION['inventory'][] = $medicineObj;
}

// Clear Session (Optional helper)
if (isset($_POST['clear'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Pharmacy Management</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f7f6;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background: #2980b9;
        }

        .clear-btn {
            background: #e74c3c;
            margin-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        th,
        td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #f8f9fa;
            color: #555;
        }

        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Pharmacy Inventory System</h2>

        <form method="POST">
            <div class="form-group">
                <label>Medicine Name</label>
                <input type="text" name="m_name" required placeholder="e.g. Paracetamol">
            </div>
            <div class="form-group">
                <label>Type</label>
                <select name="m_type">
                    <option value="Tablet">Tablet</option>
                    <option value="Syrup">Syrup</option>
                    <option value="Capsule">Capsule</option>
                    <option value="Injection">Injection</option>
                </select>
            </div>
            <div class="form-group">
                <label>Price ($)</label>
                <input type="number" step="0.01" name="m_price" required>
            </div>
            <div class="form-group">
                <label>Stock Quantity</label>
                <input type="number" name="m_stock" required>
            </div>
            <button type="submit" name="add_medicine">Add to Inventory</button>
        </form>

        <hr>

        <h3>Current Stock</h3>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($_SESSION['inventory'])): ?>
                <tr>
                    <td colspan="4" style="text-align:center;">No records found.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($_SESSION['inventory'] as $item): ?>
                <tr>
                    <td><strong>
                            <?php echo $item->getName(); ?>
                        </strong></td>
                    <td>
                        <?php echo $item->getType(); ?>
                    </td>
                    <td>$
                        <?php echo $item->getPrice(); ?>
                    </td>
                    <td>
                        <?php echo $item->getStock(); ?> units
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <form method="POST">
            <button type="submit" name="clear" class="clear-btn">Reset All Records</button>
        </form>
    </div>

</body>

</html>
