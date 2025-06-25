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
                        <li class="breadcrumb-item"><a href="index.php?page=shippers&action=list">Shippers</a></li>
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
                    <h3 class="card-title">Shipper Detail</h3>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Shipper ID</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($data_shipper['ShipperID']); ?></dd>

                        <dt class="col-sm-3">Company Name</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($data_shipper['CompanyName']); ?></dd>
                        
                        <dt class="col-sm-3">Phone</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($data_shipper['Phone']); ?></dd>
                    </dl>
                </div>
                <div class="card-footer">
                    <a href="index.php?page=shippers&action=list" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                    <a href="index.php?page=shippers&action=edit&id=<?php echo $data_shipper['ShipperID']; ?>" class="btn btn-warning float-end">
                        <i class="fas fa-edit"></i> Edit Shipper
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>
