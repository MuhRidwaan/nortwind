<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0"><?php echo $title; ?></h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="index.php?page=dashboard">Home</a></li>
                        <li class="breadcrumb-item"><a href="index.php?page=suppliers&action=list">Suppliers</a></li>
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
                    <h3 class="card-title">Supplier Detail</h3>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Supplier ID</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($data_supplier['SupplierID']); ?></dd>

                        <dt class="col-sm-3">Company Name</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($data_supplier['CompanyName']); ?></dd>
                        
                        <dt class="col-sm-3">Contact Name</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($data_supplier['ContactName']); ?></dd>

                        <dt class="col-sm-3">Contact Title</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($data_supplier['ContactTitle']); ?></dd>
                        
                        <dt class="col-sm-3">Address</dt>
                        <dd class="col-sm-9"><?php echo nl2br(htmlspecialchars($data_supplier['Address'])); ?></dd>
                        
                        <dt class="col-sm-3">City</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($data_supplier['City']); ?></dd>

                        <dt class="col-sm-3">Region</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($data_supplier['Region']); ?></dd>
                        
                        <dt class="col-sm-3">Postal Code</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($data_supplier['PostalCode']); ?></dd>
                        
                        <dt class="col-sm-3">Country</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($data_supplier['Country']); ?></dd>
                        
                        <dt class="col-sm-3">Phone</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($data_supplier['Phone']); ?></dd>

                        <dt class="col-sm-3">Fax</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($data_supplier['Fax']); ?></dd>
                    </dl>
                </div>
                <div class="card-footer">
                    <a href="index.php?page=suppliers&action=list" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                    <a href="index.php?page=suppliers&action=edit&id=<?php echo $data_supplier['SupplierID']; ?>" class="btn btn-warning float-end">
                        <i class="fas fa-edit"></i> Edit Supplier
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>
