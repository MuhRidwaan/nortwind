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
                        <li class="breadcrumb-item"><a href="index.php?page=customers&action=list">Customers</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="app-content">
        <div class="container-fluid">
            <div class="card card-primary card-outline mb-4">
                <div class="card-header">
                    <div class="card-title">Form Tambah Customer Baru</div>
                </div>
                <form action="index.php?page=customers&action=store" method="POST">
                    <div class="card-body">
                        <div class="row mb-3">
                            <label for="CustomerID" class="col-sm-2 col-form-label">Customer ID</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="CustomerID" name="CustomerID" maxlength="5" required>
                                <small class="form-text text-muted">Harus unik, maksimal 5 karakter.</small>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="CompanyName" class="col-sm-2 col-form-label">Company Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="CompanyName" name="CompanyName" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="ContactName" class="col-sm-2 col-form-label">Contact Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="ContactName" name="ContactName">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="ContactTitle" class="col-sm-2 col-form-label">Contact Title</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="ContactTitle" name="ContactTitle">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="Address" class="col-sm-2 col-form-label">Address</label>
                            <div class="col-sm-10">
                                <textarea name="Address" id="Address" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="City" class="col-sm-2 col-form-label">City</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="City" name="City">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="Region" class="col-sm-2 col-form-label">Region</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="Region" name="Region">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="PostalCode" class="col-sm-2 col-form-label">Postal Code</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="PostalCode" name="PostalCode">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="Country" class="col-sm-2 col-form-label">Country</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="Country" name="Country">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="Phone" class="col-sm-2 col-form-label">Phone</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="Phone" name="Phone">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="Fax" class="col-sm-2 col-form-label">Fax</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="Fax" name="Fax">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Save Customer</button>
                        <a href="index.php?page=customers&action=list" class="btn btn-secondary float-end">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>