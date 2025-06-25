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
                        <li class="breadcrumb-item"><a href="index.php?page=shippers&action=list">Shippers</a></li>
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
                    <div class="card-title">Form Edit Data Shipper</div>
                </div>
                <form action="index.php?page=shippers&action=update" method="POST">
                    <input type="hidden" name="ShipperID" value="<?php echo htmlspecialchars($data_shipper['ShipperID']); ?>">
                    
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="CompanyName" class="form-label">Company Name</label>
                            <input type="text" class="form-control" id="CompanyName" name="CompanyName" value="<?php echo htmlspecialchars($data_shipper['CompanyName']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="Phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="Phone" name="Phone" value="<?php echo htmlspecialchars($data_shipper['Phone']); ?>">
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <a href="index.php?page=shippers&action=list" class="btn btn-secondary float-end">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
