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
                        <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($title); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="app-content">
        <div class="container-fluid">

         <?php display_flash_message(); // Menampilkan notifikasi ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Products List</h3>
                    <div class="card-tools">

                     <form action="index.php" method="GET" class="d-inline-flex align-items-center">
                        <input type="hidden" name="page" value="products">
                        <input type="hidden" name="action" value="list">
                        
                        <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari produk/kategori..." value="<?php echo htmlspecialchars($search_term ?? ''); ?>">
                        <button type="submit" class="btn btn-primary btn-sm ms-2">
                           <i class="bi bi-search"></i>
                        </button>
                        <a href="index.php?page=products&action=list" class="btn btn-secondary btn-sm ms-1">Reset</a>
                    </form>
                        <a href="index.php?page=products&action=create" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add New
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Unit Price</th>
                                <th>Units In Stock</th>
                                <th>Discontinued</th>
                                <th style="width: 200px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($list_product)): ?>
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data untuk ditampilkan.</td>
                                </tr>
                            <?php else: ?>
                                <?php $nomor = 1; ?>
                                <?php foreach ($list_product as $product): ?>
                                    <tr>
                                        <td><?php echo $nomor++; ?>.</td>
                                        <td>
                                        <?php echo htmlspecialchars($product['ProductName']); // <-- PERUBAHAN DI SINI: Tag <a> dihapus ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($product['CategoryName']); ?></td>
                                        <td class="text-end"><?php echo number_format($product['UnitPrice'], 2, ',', '.'); ?></td>
                                        <td class="text-center"><?php echo htmlspecialchars($product['UnitsInStock']); ?></td>
                                        <td class="text-center">
                                            <?php if ($product['Discontinued']): ?>
                                                <span class="badge bg-danger">Yes</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">No</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="index.php?page=products&action=detail&id=<?php echo $product['ProductID']; ?>" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                            <a href="index.php?page=products&action=edit&id=<?php echo $product['ProductID']; ?>" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="index.php?page=products&action=delete&id=<?php echo $product['ProductID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?');">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <?php 
                        // Merender link pagination
                        if (isset($pagination)) {
                            echo $pagination->render();
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</main>