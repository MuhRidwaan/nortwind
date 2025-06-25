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
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    
    <div class="app-content">
        <div class="container-fluid" id="invoice-container">
            <?php display_flash_message(); ?>

            <div class="row">
                <!-- Order & Customer Info -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header"><h3 class="card-title">Order Information</h3></div>
                        <div class="card-body">
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Order ID</dt>
                                <dd class="col-sm-8">#<?php echo htmlspecialchars($data_order['OrderID']); ?></dd>

                                <dt class="col-sm-4">Customer</dt>
                                <dd class="col-sm-8"><?php echo htmlspecialchars($data_order['CustomerName']); ?></dd>
                                
                                <dt class="col-sm-4">Sales Employee</dt>
                                <dd class="col-sm-8"><?php echo htmlspecialchars($data_order['FirstName'].' '.$data_order['LastName']); ?></dd>

                                <dt class="col-sm-4">Order Date</dt>
                                <dd class="col-sm-8"><?php echo date('d F Y', strtotime($data_order['OrderDate'])); ?></dd>
                                
                                <dt class="col-sm-4">Required Date</dt>
                                <dd class="col-sm-8"><?php echo date('d F Y', strtotime($data_order['RequiredDate'])); ?></dd>
                                
                                <dt class="col-sm-4">Shipped Date</dt>
                                <dd class="col-sm-8">
                                    <?php if ($data_order['ShippedDate']): ?>
                                        <span class="badge bg-success"><?php echo date('d F Y', strtotime($data_order['ShippedDate'])); ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Pending Shipment</span>
                                    <?php endif; ?>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Shipping Info -->
                <div class="col-md-6">
                     <div class="card h-100">
                        <div class="card-header"><h3 class="card-title">Shipping & Freight</h3></div>
                        <div class="card-body">
                             <dl class="row mb-0">
                                <dt class="col-sm-4">Shipped Via</dt>
                                <dd class="col-sm-8"><?php echo htmlspecialchars($data_order['ShipperName']); ?></dd>

                                <dt class="col-sm-4">Freight Cost</dt>
                                <dd class="col-sm-8">$<?php echo number_format($data_order['Freight'], 2); ?></dd>
                                
                                <dt class="col-sm-4">Ship To</dt>
                                <dd class="col-sm-8">
                                    <strong><?php echo htmlspecialchars($data_order['ShipName']); ?></strong><br>
                                    <?php echo htmlspecialchars($data_order['ShipAddress']); ?><br>
                                    <?php echo htmlspecialchars($data_order['ShipCity']); ?>
                                    <?php echo $data_order['ShipRegion'] ? ', ' . htmlspecialchars($data_order['ShipRegion']) : ''; ?>
                                    <?php echo htmlspecialchars($data_order['ShipPostalCode']); ?><br>
                                    <?php echo htmlspecialchars($data_order['ShipCountry']); ?>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Details Table -->
            <div class="card mt-4">
                <div class="card-header"><h3 class="card-title">Order Details</h3></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th class="text-end">Unit Price</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-center">Discount</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data_order['Details'] as $detail): 
                                    $subtotal = $detail['UnitPrice'] * $detail['Quantity'] * (1 - $detail['Discount']);
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($detail['ProductName']); ?></td>
                                    <td class="text-end">$<?php echo number_format($detail['UnitPrice'], 2); ?></td>
                                    <td class="text-center"><?php echo $detail['Quantity']; ?></td>
                                    <td class="text-center"><?php echo ($detail['Discount'] * 100); ?>%</td>
                                    <td class="text-end">$<?php echo number_format($subtotal, 2); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-group-divider">
                                <tr>
                                    <td colspan="3" rowspan="3"></td>
                                    <td class="text-end fw-bold">Products Total:</td>
                                    <td class="text-end fw-bold">$<?php echo number_format($data_order['TotalAmount'], 2); ?></td>
                                </tr>
                                 <tr>
                                    <td class="text-end fw-bold">Freight:</td>
                                    <td class="text-end fw-bold">$<?php echo number_format($data_order['Freight'], 2); ?></td>
                                </tr>
                                 <tr class="fs-5 bg-light">
                                    <td class="text-end fw-bold">Grand Total:</td>
                                    <td class="text-end fw-bold">$<?php echo number_format($data_order['TotalAmount'] + $data_order['Freight'], 2); ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-4 text-end d-print-none">
                <a href="index.php?page=orders&action=list" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Back to List</a>
                <button onclick="window.print()" class="btn btn-info"><i class="fas fa-print me-2"></i>Print</button>
                <a href="index.php?page=orders&action=edit&id=<?php echo $data_order['OrderID']; ?>" class="btn btn-primary"><i class="fas fa-edit me-2"></i>Edit</a>
                <button id="delete-btn" class="btn btn-danger" data-id="<?php echo $data_order['OrderID']; ?>"><i class="fas fa-trash me-2"></i>Delete</button>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButton = document.getElementById('delete-btn');
    if(deleteButton) {
        deleteButton.addEventListener('click', function() {
            const orderId = this.dataset.id;
            // A simple, clean confirmation dialog
            if (confirm(`Are you sure you want to delete Order #${orderId}? This action cannot be undone.`)) {
                window.location.href = `index.php?page=orders&action=delete&id=${orderId}`;
            }
        });
    }
});
</script>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #invoice-container, #invoice-container * {
            visibility: visible;
        }
        #invoice-container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
    }
</style>
