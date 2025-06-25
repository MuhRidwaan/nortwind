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
                        <li class="breadcrumb-item"><a href="index.php?page=employees&action=list">Employees</a></li>
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
                    <div class="card-title">Form Edit Data Karyawan</div>
                </div>
                <form action="index.php?page=employees&action=update" method="POST">
                    <input type="hidden" name="EmployeeID" value="<?php echo htmlspecialchars($data_employee['EmployeeID']); ?>">
                    
                    <div class="card-body">
                        <div class="mb-3">
                             <label for="EmployeeID_display" class="form-label">Employee ID</label>
                             <input type="text" class="form-control" id="EmployeeID_display" value="<?php echo htmlspecialchars($data_employee['EmployeeID']); ?>" disabled>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="FirstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="FirstName" name="FirstName" value="<?php echo htmlspecialchars($data_employee['FirstName']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="LastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="LastName" name="LastName" value="<?php echo htmlspecialchars($data_employee['LastName']); ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="Title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="Title" name="Title" value="<?php echo htmlspecialchars($data_employee['Title']); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="TitleOfCourtesy" class="form-label">Title Of Courtesy</label>
                                <input type="text" class="form-control" id="TitleOfCourtesy" name="TitleOfCourtesy" placeholder="e.g., Mr., Mrs., Dr." value="<?php echo htmlspecialchars($data_employee['TitleOfCourtesy']); ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="BirthDate" class="form-label">Birth Date</label>
                                <input type="date" class="form-control" id="BirthDate" name="BirthDate" value="<?php echo substr($data_employee['BirthDate'], 0, 10); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="HireDate" class="form-label">Hire Date</label>
                                <input type="date" class="form-control" id="HireDate" name="HireDate" value="<?php echo substr($data_employee['HireDate'], 0, 10); ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="Address" class="form-label">Address</label>
                            <textarea name="Address" id="Address" class="form-control" rows="2"><?php echo htmlspecialchars($data_employee['Address']); ?></textarea>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="City" class="form-label">City</label>
                                <input type="text" class="form-control" id="City" name="City" value="<?php echo htmlspecialchars($data_employee['City']); ?>">
                            </div>
                             <div class="col-md-4">
                                <label for="Region" class="form-label">Region</label>
                                <input type="text" class="form-control" id="Region" name="Region" value="<?php echo htmlspecialchars($data_employee['Region']); ?>">
                            </div>
                            <div class="col-md-4">
                                <label for="PostalCode" class="form-label">Postal Code</label>
                                <input type="text" class="form-control" id="PostalCode" name="PostalCode" value="<?php echo htmlspecialchars($data_employee['PostalCode']); ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="Country" class="form-label">Country</label>
                                <input type="text" class="form-control" id="Country" name="Country" value="<?php echo htmlspecialchars($data_employee['Country']); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="HomePhone" class="form-label">Home Phone</label>
                                <input type="text" class="form-control" id="HomePhone" name="HomePhone" value="<?php echo htmlspecialchars($data_employee['HomePhone']); ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="Extension" class="form-label">Extension</label>
                                <input type="text" class="form-control" id="Extension" name="Extension" value="<?php echo htmlspecialchars($data_employee['Extension']); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="ReportsTo" class="form-label">Reports To</label>
                                <input type="number" class="form-control" id="ReportsTo" name="ReportsTo" placeholder="EmployeeID of manager" value="<?php echo htmlspecialchars($data_employee['ReportsTo']); ?>">
                                <small class="form-text text-muted">Isi dengan ID Karyawan atasan.</small>
                            </div>
                        </div>

                         <div class="mb-3">
                            <label for="Notes" class="form-label">Notes</label>
                            <textarea name="Notes" id="Notes" class="form-control" rows="3"><?php echo htmlspecialchars($data_employee['Notes']); ?></textarea>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <a href="index.php?page=employees&action=list" class="btn btn-secondary float-end">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>