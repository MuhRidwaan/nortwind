<?php
// file: classes/Order.php

class Order {
    private $db;

    public function __construct(PDO $pdo) {
        $this->db = $pdo;
    }

    /**
     * Validates shipping data against defined length limits.
     * @param array $data The order data containing shipping fields.
     * @return bool|string True if valid, an error message string if not.
     */
    private function validateShippingData(array $data): bool|string {
        $limits = [
            'ShipName'       => 40,
            'ShipAddress'    => 60,
            'ShipCity'       => 15,
            'ShipRegion'     => 15,
            'ShipPostalCode' => 10,
            'ShipCountry'    => 15
        ];

        foreach ($limits as $field => $limit) {
            if (isset($data[$field]) && mb_strlen($data[$field]) > $limit) {
                // Returns a user-friendly error message.
                return "The '$field' field cannot exceed $limit characters.";
            }
        }
        return true;
    }

    /**
     * Creates a new order with its details using a transaction.
     * @param array $orderData Data for the 'orders' table.
     * @param array $detailsData Array of detail items for 'orderdetails'.
     * @return int|string The OrderID on success, or an error message on failure.
     */
    public function create(array $orderData, array $detailsData): int|string {
        if (empty($detailsData)) {
            return "No products have been added to the order.";
        }

        // Validate data before proceeding
        $validationResult = $this->validateShippingData($orderData);
        if ($validationResult !== true) {
            return $validationResult; // Return the specific error message
        }

        try {
            $this->db->beginTransaction();

            // 1. Insert data into the 'orders' table
            $queryOrder = "INSERT INTO orders (CustomerID, EmployeeID, OrderDate, RequiredDate, ShippedDate, ShipVia, Freight, ShipName, ShipAddress, ShipCity, ShipRegion, ShipPostalCode, ShipCountry) 
                           VALUES (:CustomerID, :EmployeeID, :OrderDate, :RequiredDate, :ShippedDate, :ShipVia, :Freight, :ShipName, :ShipAddress, :ShipCity, :ShipRegion, :ShipPostalCode, :ShipCountry)";
            $stmtOrder = $this->db->prepare($queryOrder);
            
            if (empty($orderData['ShippedDate'])) {
                $orderData['ShippedDate'] = null;
            }

            $stmtOrder->execute($orderData);
            $orderID = $this->db->lastInsertId();

            // 2. Insert data into the 'orderdetails' table
            $queryDetail = "INSERT INTO orderdetails (OrderID, ProductID, UnitPrice, Quantity, Discount) 
                            VALUES (:OrderID, :ProductID, :UnitPrice, :Quantity, :Discount)";
            $stmtDetail = $this->db->prepare($queryDetail);

            foreach ($detailsData as $item) {
                $stmtDetail->execute([
                    'OrderID' => $orderID,
                    'ProductID' => $item['ProductID'],
                    'UnitPrice' => $item['UnitPrice'],
                    'Quantity' => $item['Quantity'],
                    'Discount' => $item['Discount']
                ]);
            }

            $this->db->commit();
            return (int)$orderID;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Order creation failed: " . $e->getMessage());
            return "Failed to create the order due to a database error.";
        }
    }

    /**
     * Updates an existing order and its details using a transaction.
     * @param int $orderID The ID of the order to update.
     * @param array $orderData New data for the 'orders' table.
     * @param array $detailsData Array of new detail items for 'orderdetails'.
     * @return bool|string True on success, or an error message on failure.
     */
    public function update(int $orderID, array $orderData, array $detailsData): bool|string {
        if (empty($detailsData)) {
            return "An order must have at least one product.";
        }

        // Validate data before proceeding
        $validationResult = $this->validateShippingData($orderData);
        if ($validationResult !== true) {
            return $validationResult; // Return the specific error message
        }

        try {
            $this->db->beginTransaction();

            // 1. Update master data in the 'orders' table
            $orderData['OrderID'] = $orderID;
            if (empty($orderData['ShippedDate'])) {
                $orderData['ShippedDate'] = null;
            }

            $queryOrder = "UPDATE orders SET 
                                CustomerID = :CustomerID, EmployeeID = :EmployeeID, OrderDate = :OrderDate, RequiredDate = :RequiredDate, 
                                ShippedDate = :ShippedDate, ShipVia = :ShipVia, Freight = :Freight, ShipName = :ShipName, ShipAddress = :ShipAddress, 
                                ShipCity = :ShipCity, ShipRegion = :ShipRegion, ShipPostalCode = :ShipPostalCode, ShipCountry = :ShipCountry
                           WHERE OrderID = :OrderID";
            $stmtOrder = $this->db->prepare($queryOrder);
            $stmtOrder->execute($orderData);

            // 2. Delete all old order details
            $stmtDelete = $this->db->prepare("DELETE FROM orderdetails WHERE OrderID = :OrderID");
            $stmtDelete->execute(['OrderID' => $orderID]);

            // 3. Insert the new order details
            $queryDetail = "INSERT INTO orderdetails (OrderID, ProductID, UnitPrice, Quantity, Discount) 
                            VALUES (:OrderID, :ProductID, :UnitPrice, :Quantity, :Discount)";
            $stmtDetail = $this->db->prepare($queryDetail);

            foreach ($detailsData as $item) {
                $stmtDetail->execute([
                    'OrderID' => $orderID,
                    'ProductID' => $item['ProductID'],
                    'UnitPrice' => $item['UnitPrice'],
                    'Quantity' => $item['Quantity'],
                    'Discount' => $item['Discount']
                ]);
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Order update failed for OrderID {$orderID}: " . $e->getMessage());
            return "Failed to update the order due to a database error.";
        }
    }

    /**
     * Retrieves a single order with its product details and total calculation.
     * @param int $OrderID
     * @return array|false The order data or false if not found.
     */
    public function getById(int $OrderID): array|false {
        $query = "SELECT o.*, c.CompanyName as CustomerName, 
                         e.FirstName, e.LastName, s.CompanyName as ShipperName
                  FROM orders o
                  LEFT JOIN customers c ON o.CustomerID = c.CustomerID
                  LEFT JOIN employees e ON o.EmployeeID = e.EmployeeID
                  LEFT JOIN shippers s ON o.ShipVia = s.ShipperID
                  WHERE o.OrderID = :OrderID";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['OrderID' => $OrderID]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($order) {
            require_once 'OrderDetail.php';
            $orderDetailManager = new OrderDetail($this->db);
            $order['Details'] = $orderDetailManager->getByOrderId($OrderID);

            $total = 0;
            foreach ($order['Details'] as $detail) {
                $total += $detail['UnitPrice'] * $detail['Quantity'] * (1 - $detail['Discount']);
            }
            $order['TotalAmount'] = $total;
        }

        return $order;
    }
    
    /**
     * Deletes an order and its details using a transaction.
     * @param int $OrderID
     * @return bool
     */
    public function delete(int $OrderID): bool {
        try {
            $this->db->beginTransaction();
            $stmtDetails = $this->db->prepare("DELETE FROM orderdetails WHERE OrderID = :OrderID");
            $stmtDetails->execute(['OrderID' => $OrderID]);
            $stmtOrder = $this->db->prepare("DELETE FROM orders WHERE OrderID = :OrderID");
            $stmtOrder->execute(['OrderID' => $OrderID]);
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Order deletion failed for OrderID {$OrderID}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Marks an order as shipped by setting the ShippedDate to the current timestamp.
     * @param int $orderID
     * @return bool
     */
    public function shipOrder(int $orderID): bool {
        $query = "UPDATE orders SET ShippedDate = NOW() WHERE OrderID = :OrderID AND ShippedDate IS NULL ";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['OrderID' => $orderID]);
    }

    /**
     * Gets the total count of unshipped orders.
     * @return int
     */
    public function getUnshippedOrdersCount(): int {
        $sql = "SELECT COUNT(OrderID) FROM orders WHERE ShippedDate IS NULL";
        $stmt = $this->db->query($sql);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Gets a paginated list of unshipped orders.
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getUnshippedOrdersPaginated(int $limit, int $offset): array {
        $sql = "SELECT o.OrderID, o.OrderDate, o.RequiredDate, o.ShipName, c.CompanyName as CustomerName
                FROM orders o
                LEFT JOIN customers c ON o.CustomerID = c.CustomerID
                WHERE o.ShippedDate IS NULL
                ORDER BY o.OrderDate DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Gets the total count of orders, with an optional search term.
     * @param string|null $searchTerm
     * @return int
     */
    public function getTotalCount(string $searchTerm = null): int {
        $sql = "SELECT COUNT(o.OrderID) 
                FROM orders o
                LEFT JOIN customers c ON o.CustomerID = c.CustomerID";
        $params = [];

        if ($searchTerm) {
            $sql .= " WHERE CONCAT_WS(' ', o.OrderID, c.CompanyName, o.ShipName) LIKE :searchTerm";
            $params[':searchTerm'] = '%' . $searchTerm . '%';
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }
   
    /**
     * Gets a paginated list of orders, with an optional search term.
     * @param int $limit
     * @param int $offset
     * @param string|null $searchTerm
     * @return array
     */
    public function getPaginated(int $limit, int $offset, string $searchTerm = null): array {
        $sql = "SELECT o.OrderID, o.OrderDate, o.ShippedDate, o.ShipName, c.CompanyName as CustomerName,
                       (SELECT SUM(od.UnitPrice * od.Quantity * (1 - od.Discount)) FROM orderdetails od WHERE od.OrderID = o.OrderID) + o.Freight as TotalOrderAmount
                FROM orders o
                LEFT JOIN customers c ON o.CustomerID = c.CustomerID";
        $params = [];

        if ($searchTerm) {
            $sql .= " WHERE CONCAT_WS(' ', o.OrderID, c.CompanyName, o.ShipName) LIKE :searchTerm";
            $params[':searchTerm'] = '%' . $searchTerm . '%';
        }

        $sql .= " ORDER BY o.OrderDate DESC LIMIT :limit OFFSET :offset";
        $params[':limit'] = $limit;
        $params[':offset'] = $offset;

        $stmt = $this->db->prepare($sql);
        
        foreach ($params as $key => &$val) {
            $type = ($key == ':limit' || $key == ':offset') ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindParam($key, $val, $type);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
