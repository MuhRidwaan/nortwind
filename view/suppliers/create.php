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
                    <div class="card-title">Form Tambah Supplier Baru</div>
                </div>
                <form action="index.php?page=suppliers&action=store" method="POST">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="CompanyName" class="form-label">Company Name</label>
                            <input type="text" class="form-control" id="CompanyName" name="CompanyName" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="ContactName" class="form-label">Contact Name</label>
                                <input type="text" class="form-control" id="ContactName" name="ContactName">
                            </div>
                            <div class="col-md-6">
                                <label for="ContactTitle" class="form-label">Contact Title</label>
                                <input type="text" class="form-control" id="ContactTitle" name="ContactTitle">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="Address" class="form-label">Address</label>
                            <textarea name="Address" id="Address" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="City" class="form-label">City</label>
                                <input type="text" class="form-control" id="City" name="City">
                            </div>
                             <div class="col-md-4">
                                <label for="Region" class="form-label">Region</label>
                                <input type="text" class="form-control" id="Region" name="Region">
                            </div>
                            <div class="col-md-4">
                                <label for="PostalCode" class="form-label">Postal Code</label>
                                <input type="text" class="form-control" id="PostalCode" name="PostalCode">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="Country" class="form-label">Country</label>
                            <input type="text" class="form-control" id="Country" name="Country">
                        </div>
                        <div class="row mb-3">
                           <div class="col-md-6">
                                <label for="Phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="Phone" name="Phone">
                            </div>
                            <div class="col-md-6">
                                <label for="Fax" class="form-label">Fax</label>
                                <input type="text" class="form-control" id="Fax" name="Fax">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Save Supplier</button>
                        <a href="index.php?page=suppliers&action=list" class="btn btn-secondary float-end">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
