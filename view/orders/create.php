<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0"><?php echo htmlspecialchars($title); ?></h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="index.php?page=dashboard">Home</a></li>
                        <li class="breadcrumb-item"><a href="index.php?page=orders&action=list">Orders</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="app-content">
        <div class="container-fluid">
            <form action="index.php?page=orders&action=store" method="POST" id="order-form">
                <!-- Order Information Card -->
                <div class="card card-primary card-outline mb-4">
                    <div class="card-header"><h3 class="card-title">Order Information</h3></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="CustomerID" class="form-label">Customer</label>
                                <select name="CustomerID" id="CustomerID" class="form-select" required>
                                    <option value="" disabled selected>Select Customer...</option>
                                    <?php foreach($customers as $c) echo "<option value='{$c['CustomerID']}'>".htmlspecialchars($c['CompanyName'])."</option>"; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="EmployeeID" class="form-label">Employee</label>
                                <select name="EmployeeID" id="EmployeeID" class="form-select" required>
                                    <option value="" disabled selected>Select Employee...</option>
                                    <?php foreach($employees as $e) echo "<option value='{$e['EmployeeID']}'>".htmlspecialchars($e['FirstName'].' '.$e['LastName'])."</option>"; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                             <div class="col-md-4 mb-3">
                                <label for="OrderDate" class="form-label">Order Date</label>
                                <input type="date" class="form-control" name="OrderDate" id="OrderDate" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="RequiredDate" class="form-label">Required Date</label>
                                <input type="date" class="form-control" name="RequiredDate" id="RequiredDate" required>
                            </div>
                             <div class="col-md-4 mb-3">
                                <label for="ShipVia" class="form-label">Ship Via</label>
                                <select name="ShipVia" id="ShipVia" class="form-select" required>
                                    <option value="" disabled selected>Select Shipper...</option>
                                    <?php foreach($shippers as $s) echo "<option value='{$s['ShipperID']}'>".htmlspecialchars($s['CompanyName'])."</option>"; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Details Card -->
                <div class="card card-secondary card-outline mb-4">
                    <div class="card-header"><h3 class="card-title">Shipping Details</h3></div>
                    <div class="card-body">
                         <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="ShipName" class="form-label">Ship Name</label>
                                <input type="text" class="form-control" name="ShipName" id="ShipName" required placeholder="Recipient's full name" maxlength="40">
                                <small class="form-text text-muted">Max. 40 characters.</small>
                            </div>
                             <div class="col-md-6 mb-3">
                                <label for="Freight" class="form-label">Freight Cost</label>
                                <input type="number" step="0.01" class="form-control" name="Freight" id="Freight" required value="0">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="ShipAddress" class="form-label">Ship Address</label>
                            <input type="text" class="form-control" name="ShipAddress" id="ShipAddress" required placeholder="Street address, building, etc." maxlength="60">
                            <small class="form-text text-muted">Max. 60 characters.</small>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="ShipCity" class="form-label">Ship City</label>
                                <input type="text" class="form-control" name="ShipCity" id="ShipCity" required placeholder="e.g. London" maxlength="15">
                                <small class="form-text text-muted">Max. 15 characters.</small>
                            </div>
                             <div class="col-md-4 mb-3">
                                <label for="ShipRegion" class="form-label">Ship Region</label>
                                <input type="text" class="form-control" name="ShipRegion" id="ShipRegion" placeholder="e.g. Greater London" maxlength="15">
                                <small class="form-text text-muted">Max. 15 characters.</small>
                            </div>
                             <div class="col-md-4 mb-3">
                                <label for="ShipPostalCode" class="form-label">Ship Postal Code</label>
                                <input type="text" class="form-control" name="ShipPostalCode" id="ShipPostalCode" placeholder="e.g. SW1A 0AA" maxlength="10">
                                <small class="form-text text-muted">Max. 10 characters.</small>
                            </div>
                        </div>
                         <div class="mb-3">
                            <label for="ShipCountry" class="form-label">Ship Country</label>
                            <input type="text" class="form-control" name="ShipCountry" id="ShipCountry" required placeholder="e.g. UK" maxlength="15">
                            <small class="form-text text-muted">Max. 15 characters.</small>
                        </div>
                    </div>
                </div>

                <!-- Order Products Card -->
                <div class="card card-info card-outline mb-4">
                    <div class="card-header"><h3 class="card-title">Order Products</h3></div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-5">
                                <label for="product-selector" class="form-label">Select Product</label>
                                <select id="product-selector" class="form-select">
                                    <option value="" disabled selected>Add a product to the order...</option>
                                    <?php foreach($products as $p) echo "<option value='{$p['ProductID']}' data-price='{$p['UnitPrice']}' data-name='".htmlspecialchars($p['ProductName'], ENT_QUOTES)."'>".htmlspecialchars($p['ProductName'])."</option>"; ?>
                                </select>
                            </div>
                            <div class="col-md-2 align-self-end">
                                <button type="button" id="add-product-btn" class="btn btn-primary w-100">Add Product</button>
                            </div>
                        </div>
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th style="width: 120px;" class="text-end">Unit Price</th>
                                    <th style="width: 100px;" class="text-center">Quantity</th>
                                    <th style="width: 120px;" class="text-center">Discount</th>
                                    <th style="width: 150px;" class="text-end">Subtotal</th>
                                    <th style="width: 50px;" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="order-items-table">
                                <!-- JS will populate this area -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-end">Products Total:</th>
                                    <th id="products-total" class="text-end">0.00</th>
                                    <th></th>
                                </tr>
                                 <tr>
                                    <th colspan="4" class="text-end">Freight:</th>
                                    <th id="freight-total" class="text-end">0.00</th>
                                    <th></th>
                                </tr>
                                <tr class="fs-5 table-group-divider">
                                    <th colspan="4" class="text-end">Grand Total:</th>
                                    <th id="grand-total" class="text-end">0.00</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- This field is not editable on creation -->
                <input type="hidden" name="ShippedDate" value="">

                <div class="card-footer text-end">
                    <a href="index.php?page=orders&action=list" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save me-2"></i>Create Order</button>
                </div>
            </form>
        </div>
    </div>
<script>
// Script remains the same as it doesn't need to handle maxlength directly
document.addEventListener('DOMContentLoaded', function() {
    const addBtn = document.getElementById('add-product-btn');
    const productSelector = document.getElementById('product-selector');
    const itemsTable = document.getElementById('order-items-table');
    const grandTotalEl = document.getElementById('grand-total');
    const productsTotalEl = document.getElementById('products-total');
    const freightTotalEl = document.getElementById('freight-total');
    const freightInput = document.getElementById('Freight');
    let itemIndex = 0;

    const formatCurrency = (num) => parseFloat(num).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    addBtn.addEventListener('click', function() {
        const selectedOption = productSelector.options[productSelector.selectedIndex];
        if (!selectedOption.value) {
            alert('Please select a product to add.');
            return;
        }

        const productId = selectedOption.value;
        const productName = selectedOption.dataset.name;
        const productPrice = parseFloat(selectedOption.dataset.price);

        if (document.querySelector(`tr[data-product-id="${productId}"]`)) {
            alert('This product is already in the order. You can change the quantity instead.');
            return;
        }
        
        const newRow = document.createElement('tr');
        newRow.dataset.productId = productId;
        
        newRow.innerHTML = `
            <td>
                ${productName}
                <input type="hidden" name="products[${itemIndex}][id]" value="${productId}">
            </td>
            <td class="text-end">
                <input type="hidden" class="item-price" name="products[${itemIndex}][price]" value="${productPrice}">
                ${formatCurrency(productPrice)}
            </td>
            <td><input type="number" name="products[${itemIndex}][qty]" class="form-control form-control-sm item-qty text-center" value="1" min="1" required></td>
            <td><input type="number" name="products[${itemIndex}][discount]" class="form-control form-control-sm item-discount text-center" value="0" min="0" max="1" step="0.01"></td>
            <td class="text-end item-subtotal">${formatCurrency(productPrice)}</td>
            <td class="text-center"><button type="button" class="btn btn-danger btn-sm remove-item-btn">&times;</button></td>
        `;
        itemsTable.appendChild(newRow);
        itemIndex++;
        updateTotals();
    });

    itemsTable.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item-btn')) {
            e.target.closest('tr').remove();
            updateTotals();
        }
    });

    itemsTable.addEventListener('input', function(e) {
        if (e.target.classList.contains('item-qty') || e.target.classList.contains('item-discount')) {
            updateTotals();
        }
    });

    freightInput.addEventListener('input', updateTotals);

    function updateTotals() {
        let productsTotal = 0;
        document.querySelectorAll('#order-items-table tr').forEach(row => {
            const price = parseFloat(row.querySelector('.item-price').value) || 0;
            const qty = parseInt(row.querySelector('.item-qty').value) || 0;
            const discount = parseFloat(row.querySelector('.item-discount').value) || 0;
            
            if (discount < 0) row.querySelector('.item-discount').value = 0;
            if (discount > 1) row.querySelector('.item-discount').value = 1;

            const subtotal = price * qty * (1 - discount);
            row.querySelector('.item-subtotal').textContent = formatCurrency(subtotal);
            productsTotal += subtotal;
        });

        const freight = parseFloat(freightInput.value) || 0;
        const grandTotal = productsTotal + freight;

        productsTotalEl.textContent = formatCurrency(productsTotal);
        freightTotalEl.textContent = formatCurrency(freight);
        grandTotalEl.textContent = formatCurrency(grandTotal);
    }
});
</script>
</main>
