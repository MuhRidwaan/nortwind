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
                        <li class="breadcrumb-item"><a href="index.php?page=products&action=list">Products</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="app-content">
        <div class="container-fluid">
            <div class="card card-primary card-outline mb-4">
                <div class="card-header">
                    <div class="card-title">Form Edit Produk</div>
                </div>
                <form action="index.php?page=products&action=update" method="POST">
                    <input type="hidden" name="ProductID" value="<?php echo htmlspecialchars($data_product['ProductID']); ?>">
                    
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="ProductName" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="ProductName" name="ProductName" value="<?php echo htmlspecialchars($data_product['ProductName']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="QuantityPerUnit" class="form-label">Quantity Per Unit</label>
                                <input type="text" class="form-control" id="QuantityPerUnit" name="QuantityPerUnit" value="<?php echo htmlspecialchars($data_product['QuantityPerUnit']); ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="CategoryID" class="form-label">Category</label>
                                <select name="CategoryID" id="CategoryID" class="form-select">
                                    <option value="">Pilih Kategori...</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['CategoryID']; ?>" <?php if($data_product['CategoryID'] == $category['CategoryID']) echo 'selected'; ?>>
                                            <?php echo htmlspecialchars($category['CategoryName']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                 <label for="SupplierID" class="form-label">Supplier</label>
                                <select name="SupplierID" id="SupplierID" class="form-select">
                                    <option value="">Pilih Supplier...</option>
                                     <?php foreach ($suppliers as $supplier): ?>
                                        <option value="<?php echo $supplier['SupplierID']; ?>" <?php if($data_product['SupplierID'] == $supplier['SupplierID']) echo 'selected'; ?>>
                                            <?php echo htmlspecialchars($supplier['CompanyName']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="UnitPrice" class="form-label">Unit Price</label>
                                <input type="number" class="form-control" id="UnitPrice" name="UnitPrice" step="0.01" value="<?php echo htmlspecialchars($data_product['UnitPrice']); ?>">
                            </div>
                            <div class="col-md-4">
                                <label for="UnitsInStock" class="form-label">Units In Stock</label>
                                <input type="number" class="form-control" id="UnitsInStock" name="UnitsInStock" value="<?php echo htmlspecialchars($data_product['UnitsInStock']); ?>">
                            </div>
                            <div class="col-md-4">
                                <label for="ReorderLevel" class="form-label">Reorder Level</label>
                                <input type="number" class="form-control" id="ReorderLevel" name="ReorderLevel" value="<?php echo htmlspecialchars($data_product['ReorderLevel']); ?>">
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label for="UnitsOnOrder" class="form-label">Units On Order</label>
                                <input type="number" class="form-control" id="UnitsOnOrder" name="UnitsOnOrder" value="<?php echo htmlspecialchars($data_product['UnitsOnOrder']); ?>">
                            </div>
                            <div class="col-md-8 pt-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="Discontinued" name="Discontinued" <?php if($data_product['Discontinued']) echo 'checked'; ?>>
                                    <label class="form-check-label" for="Discontinued">
                                        Discontinued (Produk ini sudah tidak dilanjutkan)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <a href="index.php?page=products&action=list" class="btn btn-secondary float-end">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>