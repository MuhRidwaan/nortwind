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
                        <li class="breadcrumb-item active" aria-current="page">Orders</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="app-content">
        <div class="container-fluid">
            <?php display_flash_message(); ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Orders List</h3>
                    <div class="card-tools">
                        <form action="index.php" method="GET" class="d-inline-flex align-items-center">
                            <input type="hidden" name="page" value="orders">
                            <input type="hidden" name="action" value="list">
                            <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari ID/Customer..." value="<?php echo htmlspecialchars($search_term ?? ''); ?>">
                            <button type="submit" class="btn btn-primary btn-sm ms-2"><i class="bi bi-search"></i></button>
                            <a href="index.php?page=orders&action=list" class="btn btn-secondary btn-sm ms-1">Reset</a>
                        </form>
                        <a href="index.php?page=orders&action=create" class="btn btn-success btn-sm">
                            <i class="bi bi-plus-lg"></i> Create New Order
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10px">#ID</th>
                                <th>Customer</th>
                                <th>Order Date</th>
                                <th>Shipped Date</th>
                                <th>Ship Name</th>
                                <th style="width: 120px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($list_order)): ?>
                                <tr><td colspan="6" class="text-center">No orders found.</td></tr>
                            <?php else: ?>
                                <?php foreach ($list_order as $order): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($order['OrderID']); ?></td>
                                        <td><?php echo htmlspecialchars($order['CustomerName']); ?></td>
                                        <td><?php echo date('d M Y', strtotime($order['OrderDate'])); ?></td>
                                        <td><?php echo $order['ShippedDate'] ? date('d M Y', strtotime($order['ShippedDate'])) : 'N/A'; ?></td>
                                        <td><?php echo htmlspecialchars($order['ShipName']); ?></td>
                                        <td>
                                            <a href="index.php?page=orders&action=detail&id=<?php echo $order['OrderID']; ?>" class="btn btn-info btn-sm"><i class="bi bi-eye-fill"></i></a>
                                            <a href="index.php?page=orders&action=edit&id=<?php echo $order['OrderID']; ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil-fill"></i></a>
                                            <a href="index.php?page=orders&action=delete&id=<?php echo $order['OrderID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this order and all its details?');"><i class="bi bi-trash-fill"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <?php if (isset($pagination)) { echo $pagination->render(); } ?>
                </div>
            </div>
        </div>
    </div>
</main>
