<main class="app-main">
    <div class="app-content-header">
        <!-- Breadcrumb Header -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0"><?php echo htmlspecialchars($title); ?></h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="index.php?page=dashboard">Home</a></li>
                        <li class="breadcrumb-item"><a href="index.php?page=orders&action=list">Orders</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="app-content">
        <div class="container-fluid">
            <form action="index.php?page=orders&action=update" method="POST" id="order-form">
                <input type="hidden" name="OrderID" value="<?php echo htmlspecialchars($data_order['OrderID']); ?>">
                <!-- Order Master -->
                <div class="card card-primary card-outline mb-4">
                    <div class="card-header"><h3 class="card-title">Order Information</h3></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="CustomerID" class="form-label">Customer</label>
                                <select name="CustomerID" id="CustomerID" class="form-select" required>
                                    <option value="">Select Customer...</option>
                                    <?php foreach($customers as $c): ?>
                                        <option value="<?php echo $c['CustomerID']; ?>" <?php if($data_order['CustomerID'] == $c['CustomerID']) echo 'selected'; ?>>
                                            <?php echo htmlspecialchars($c['CompanyName']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="EmployeeID" class="form-label">Employee</label>
                                <select name="EmployeeID" id="EmployeeID" class="form-select" required>
                                    <option value="">Select Employee...</option>
                                    <?php foreach($employees as $e): ?>
                                         <option value="<?php echo $e['EmployeeID']; ?>" <?php if($data_order['EmployeeID'] == $e['EmployeeID']) echo 'selected'; ?>>
                                            <?php echo htmlspecialchars($e['FirstName'].' '.$e['LastName']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="OrderDate" class="form-label">Order Date</label>
                                <input type="date" class="form-control" name="OrderDate" id="OrderDate" value="<?php echo substr($data_order['OrderDate'], 0, 10); ?>" required>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="ShipVia" class="form-label">Shipper</label>
                                <select name="ShipVia" id="ShipVia" class="form-select" required>
                                     <option value="">Select Shipper...</option>
                                     <?php foreach($shippers as $s): ?>
                                         <option value="<?php echo $s['ShipperID']; ?>" <?php if($data_order['ShipVia'] == $s['ShipperID']) echo 'selected'; ?>>
                                            <?php echo htmlspecialchars($s['CompanyName']); ?>
                                        </option>
                                     <?php endforeach; ?>
                                </select>
                            </div>
                             <div class="col-md-4 mb-3">
                                <label for="ShipName" class="form-label">Ship To Name</label>
                                <input type="text" class="form-control" name="ShipName" id="ShipName" value="<?php echo htmlspecialchars($data_order['ShipName']); ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="Freight" class="form-label">Freight</label>
                                <input type="number" step="0.01" class="form-control" name="Freight" id="Freight" value="<?php echo htmlspecialchars($data_order['Freight']); ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="ShipAddress" class="form-label">Ship Address</label>
                            <input type="text" class="form-control" name="ShipAddress" id="ShipAddress" value="<?php echo htmlspecialchars($data_order['ShipAddress']); ?>">
                        </div>
                         <!-- Other fields like RequiredDate, ShippedDate, ShipCity, etc. can be added here -->
                         <input type="hidden" name="RequiredDate" value="<?php echo $data_order['RequiredDate']; ?>">
                         <input type="hidden" name="ShippedDate" value="<?php echo $data_order['ShippedDate']; ?>">
                         <input type="hidden" name="ShipCity" value="<?php echo $data_order['ShipCity']; ?>">
                         <input type="hidden" name="ShipRegion" value="<?php echo $data_order['ShipRegion']; ?>">
                         <input type="hidden" name="ShipPostalCode" value="<?php echo $data_order['ShipPostalCode']; ?>">
                         <input type="hidden" name="ShipCountry" value="<?php echo $data_order['ShipCountry']; ?>">
                    </div>
                </div>

                <!-- Order Details -->
                <div class="card card-info card-outline mb-4">
                    <div class="card-header"><h3 class="card-title">Order Products</h3></div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-5">
                                <label>Select Product</label>
                                <select id="product-selector" class="form-select">
                                    <option value="">Add a product...</option>
                                    <?php foreach($products as $p) echo "<option value='{$p['ProductID']}' data-price='{$p['UnitPrice']}'>".htmlspecialchars($p['ProductName'])."</option>"; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <button type="button" id="add-product-btn" class="btn btn-primary w-100">Add</button>
                            </div>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th style="width:120px">Price</th>
                                    <th style="width:100px">Qty</th>
                                    <th style="width:120px">Discount</th>
                                    <th style="width:150px">Subtotal</th>
                                    <th style="width:50px"></th>
                                </tr>
                            </thead>
                            <tbody id="order-items-table">
                                <!-- Existing items will be rendered here by PHP/JS -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-end">Total:</th>
                                    <th id="grand-total" class="text-end">0.00</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-success">Update Order</button>
                    <a href="index.php?page=orders&action=list" class="btn btn-secondary float-end">Cancel</a>
                </div>
            </form>
        </div>
    </div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const addBtn = document.getElementById('add-product-btn');
    const productSelector = document.getElementById('product-selector');
    const itemsTable = document.getElementById('order-items-table');
    let itemIndex = 0;

    // Data detail order yang ada (dilewatkan dari PHP)
    const existingItems = <?php echo json_encode($data_order['Details']); ?>;

    // Fungsi untuk menambah baris produk
    function addProductRow(productId, productName, price, qty, discount) {
        if (document.querySelector(`input[name='products[${productId}][id]']`)) {
            // Sebaiknya, di halaman edit, kita izinkan duplikasi untuk ditangani saat render awal
            // Namun untuk penambahan manual, kita cegah.
            if (!existingItems.find(item => item.ProductID == productId)) {
                alert('Product already added.');
                return;
            }
        }

        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>
                ${productName}
                <input type="hidden" name="products[${itemIndex}][id]" value="${productId}">
                <input type="hidden" class="item-price" name="products[${itemIndex}][price]" value="${price}">
            </td>
            <td class="text-end">${parseFloat(price).toFixed(2)}</td>
            <td><input type="number" name="products[${itemIndex}][qty]" class="form-control form-control-sm item-qty" value="${qty}" min="1"></td>
            <td><input type="number" name="products[${itemIndex}][discount]" class="form-control form-control-sm item-discount" value="${parseFloat(discount)}" min="0" max="1" step="0.01"></td>
            <td class="text-end item-subtotal">0.00</td>
            <td><button type="button" class="btn btn-danger btn-sm remove-item-btn">&times;</button></td>
        `;
        itemsTable.appendChild(newRow);
        updateRowSubtotal(newRow);
        itemIndex++;
    }
    
    // Render item yang sudah ada saat halaman dimuat
    existingItems.forEach(item => {
        addProductRow(item.ProductID, item.ProductName, item.UnitPrice, item.Quantity, item.Discount);
    });
    updateGrandTotal();

    // Event listener untuk tombol "Add"
    addBtn.addEventListener('click', function() {
        const selectedOption = productSelector.options[productSelector.selectedIndex];
        if (!selectedOption.value) return;

        addProductRow(
            selectedOption.value,
            selectedOption.text,
            selectedOption.dataset.price,
            1, // qty default
            0 // discount default
        );
    });

    // Event listener untuk hapus item dan update subtotal
    itemsTable.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item-btn')) {
            e.target.closest('tr').remove();
            updateGrandTotal();
        }
    });
    itemsTable.addEventListener('input', function(e) {
        if (e.target.classList.contains('item-qty') || e.target.classList.contains('item-discount')) {
            const row = e.target.closest('tr');
            updateRowSubtotal(row);
        }
    });

    function updateRowSubtotal(row) {
        const price = parseFloat(row.querySelector('.item-price').value) || 0;
        const qty = parseInt(row.querySelector('.item-qty').value) || 0;
        const discount = parseFloat(row.querySelector('.item-discount').value) || 0;
        const subtotal = price * qty * (1 - discount);
        row.querySelector('.item-subtotal').textContent = subtotal.toFixed(2);
        updateGrandTotal();
    }

    function updateGrandTotal() {
        let total = 0;
        document.querySelectorAll('#order-items-table tr').forEach(row => {
            const subtotalText = row.querySelector('.item-subtotal').textContent;
            total += parseFloat(subtotalText) || 0;
        });
        document.getElementById('grand-total').textContent = total.toFixed(2);
    }
});
</script>
</main>
