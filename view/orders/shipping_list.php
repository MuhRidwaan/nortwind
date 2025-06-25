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
                        <li class="breadcrumb-item active" aria-current="page">Pengiriman</li>
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
                    <h3 class="card-title">Pesanan Siap Dikirim</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10px">#ID</th>
                                <th>Customer</th>
                                <th>Order Date</th>
                                <th>Required Date</th>
                                <th>Ship To</th>
                                <th style="width: 150px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($list_order)): ?>
                                <tr><td colspan="6" class="text-center">Luar biasa! Semua pesanan telah dikirim.</td></tr>
                            <?php else: ?>
                                <?php foreach ($list_order as $order): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($order['OrderID']); ?></td>
                                        <td><?php echo htmlspecialchars($order['CustomerName']); ?></td>
                                        <td><?php echo $order['OrderDate'] ? date('d M Y', strtotime($order['OrderDate'])) : 'N/A'; ?></td>
                                        <td><?php echo $order['RequiredDate'] ? date('d M Y', strtotime($order['RequiredDate'])) : 'N/A'; ?></td>          
                                        <td><?php echo htmlspecialchars($order['ShipName']); ?></td>
                                        <td>
                                            <a href="index.php?page=orders&action=ship_now&id=<?php echo $order['OrderID']; ?>" class="btn btn-success btn-sm" onclick="return confirm('Anda yakin ingin menandai pesanan ini sebagai \'Terkirim\'?');">
                                                <i class="bi bi-truck"></i> Kirim Pesanan
                                            </a>
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
