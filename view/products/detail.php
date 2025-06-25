<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0"><?php echo $title; // Judul dinamis dari controller ?></h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="index.php?page=dashboard">Home</a></li>
                        <li class="breadcrumb-item"><a href="index.php?page=products&action=list">Products</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    
    <div class="app-content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Product Detail</h3>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Product ID</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($data_product['ProductID']); ?></dd>

                        <dt class="col-sm-3">Product Name</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($data_product['ProductName']); ?></dd>
                        
                        <dt class="col-sm-3">Supplier</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($data_product['SupplierName'] ?? 'N/A'); ?></dd>
                        
                        <dt class="col-sm-3">Category</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($data_product['CategoryName'] ?? 'N/A'); ?></dd>
                        
                        <dt class="col-sm-3">Quantity Per Unit</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($data_product['QuantityPerUnit']); ?></dd>
                        
                        <dt class="col-sm-3">Unit Price</dt>
                        <dd class="col-sm-9"><?php echo "Rp " . number_format($data_product['UnitPrice'], 2, ',', '.'); ?></dd>
                        
                        <dt class="col-sm-3">Units In Stock</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($data_product['UnitsInStock']); ?></dd>
                        
                        <dt class="col-sm-3">Units On Order</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($data_product['UnitsOnOrder']); ?></dd>
                        
                        <dt class="col-sm-3">Reorder Level</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($data_product['ReorderLevel']); ?></dd>
                        
                        <dt class="col-sm-3">Discontinued</dt>
                        <dd class="col-sm-9">
                            <?php if ($data_product['Discontinued']): ?>
                                <span class="badge bg-danger">Yes</span>
                            <?php else: ?>
                                <span class="badge bg-success">No</span>
                            <?php endif; ?>
                        </dd>
                    </dl>
                </div>
                <div class="card-footer">
                    <a href="index.php?page=products&action=list" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                    <a href="index.php?page=products&action=edit&id=<?php echo $data_product['ProductID']; ?>" class="btn btn-warning float-end">
                        <i class="fas fa-edit"></i> Edit Product
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>