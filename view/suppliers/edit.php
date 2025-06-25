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
                        <li class="breadcrumb-item"><a href="index.php?page=suppliers&action=list">Suppliers</a></li>
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
                    <div class="card-title">Form Edit Data Supplier</div>
                </div>
                <form action="index.php?page=suppliers&action=update" method="POST">
                    <input type="hidden" name="SupplierID" value="<?php echo htmlspecialchars($data_supplier['SupplierID']); ?>">
                    <div class="card-body">
                         <div class="mb-3">
                            <label for="CompanyName" class="form-label">Company Name</label>
                            <input type="text" class="form-control" id="CompanyName" name="CompanyName" value="<?php echo htmlspecialchars($data_supplier['CompanyName']); ?>" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="ContactName" class="form-label">Contact Name</label>
                                <input type="text" class="form-control" id="ContactName" name="ContactName" value="<?php echo htmlspecialchars($data_supplier['ContactName']); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="ContactTitle" class="form-label">Contact Title</label>
                                <input type="text" class="form-control" id="ContactTitle" name="ContactTitle" value="<?php echo htmlspecialchars($data_supplier['ContactTitle']); ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="Address" class="form-label">Address</label>
                            <textarea name="Address" id="Address" class="form-control" rows="2"><?php echo htmlspecialchars($data_supplier['Address']); ?></textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="City" class="form-label">City</label>
                                <input type="text" class="form-control" id="City" name="City" value="<?php echo htmlspecialchars($data_supplier['City']); ?>">
                            </div>
                             <div class="col-md-4">
                                <label for="Region" class="form-label">Region</label>
                                <input type="text" class="form-control" id="Region" name="Region" value="<?php echo htmlspecialchars($data_supplier['Region']); ?>">
                            </div>
                            <div class="col-md-4">
                                <label for="PostalCode" class="form-label">Postal Code</label>
                                <input type="text" class="form-control" id="PostalCode" name="PostalCode" value="<?php echo htmlspecialchars($data_supplier['PostalCode']); ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="Country" class="form-label">Country</label>
                            <input type="text" class="form-control" id="Country" name="Country" value="<?php echo htmlspecialchars($data_supplier['Country']); ?>">
                        </div>
                        <div class="row mb-3">
                           <div class="col-md-6">
                                <label for="Phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="Phone" name="Phone" value="<?php echo htmlspecialchars($data_supplier['Phone']); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="Fax" class="form-label">Fax</label>
                                <input type="text" class="form-control" id="Fax" name="Fax" value="<?php echo htmlspecialchars($data_supplier['Fax']); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <a href="index.php?page=suppliers&action=list" class="btn btn-secondary float-end">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
