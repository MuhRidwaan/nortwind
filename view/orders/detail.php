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
        <div class="container-fluid">
            <?php display_flash_message(); ?>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Order Information</h3>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-2">Order ID:</dt>
                        <dd class="col-sm-4">#<?php echo htmlspecialchars($data_order['OrderID']); ?></dd>

                        <dt class="col-sm-2">Order Date:</dt>
                        <dd class="col-sm-4"><?php echo date('d F Y', strtotime($data_order['OrderDate'])); ?></dd>

                        <dt class="col-sm-2">Customer:</dt>
                        <dd class="col-sm-4"><?php echo htmlspecialchars($data_order['CustomerName']); ?></dd>
                        
                        <dt class="col-sm-2">Employee:</dt>
                        <dd class="col-sm-4"><?php echo htmlspecialchars($data_order['FirstName'].' '.$data_order['LastName']); ?></dd>

                        <dt class="col-sm-2">Shipped Via:</dt>
                        <dd class="col-sm-4"><?php echo htmlspecialchars($data_order['ShipperName']); ?></dd>

                        <dt class="col-sm-2">Freight Cost:</dt>
                        <dd class="col-sm-4"><?php echo number_format($data_order['Freight'], 2, ',', '.'); ?></dd>

                        <dt class="col-sm-2">Ship To:</dt>
                        <dd class="col-sm-10"><?php echo htmlspecialchars($data_order['ShipName']); ?><br>
                            <?php echo htmlspecialchars($data_order['ShipAddress']); ?><br>
                            <?php echo htmlspecialchars($data_order['ShipCity'] . ', ' . $data_order['ShipPostalCode']); ?><br>
                            <?php echo htmlspecialchars($data_order['ShipCountry']); ?>
                        </dd>
                    </dl>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header"><h3 class="card-title">Order Details</h3></div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th class="text-end">Unit Price</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Discount</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data_order['Details'] as $detail): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($detail['ProductName']); ?></td>
                                <td class="text-end"><?php echo number_format($detail['UnitPrice'], 2, ',', '.'); ?></td>
                                <td class="text-center"><?php echo $detail['Quantity']; ?></td>
                                <td class="text-center"><?php echo ($detail['Discount'] * 100); ?>%</td>
                                <td class="text-end"><?php echo number_format($detail['UnitPrice'] * $detail['Quantity'] * (1 - $detail['Discount']), 2, ',', '.'); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="fw-bold">
                                <td colspan="3"></td>
                                <td class="text-end">Freight:</td>
                                <td class="text-end"><?php echo number_format($data_order['Freight'], 2, ',', '.'); ?></td>
                            </tr>
                             <tr class="fw-bold fs-5">
                                <td colspan="3"></td>
                                <td class="text-end bg-light">Grand Total:</td>
                                <td class="text-end bg-light"><?php echo number_format($data_order['TotalAmount'] + $data_order['Freight'], 2, ',', '.'); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="mt-3">
                <a href="index.php?page=orders&action=list" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to List</a>
            </div>
        </div>
    </div>
</main>
