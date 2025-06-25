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
                        <li class="breadcrumb-item"><a href="index.php?page=employees&action=list">Employees</a></li>
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
                    <h3 class="card-title">Employee Detail</h3>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Employee ID</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($data_employee['EmployeeID']); ?></dd>

                        <dt class="col-sm-3">Full Name</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($data_employee['FirstName'] . ' ' . $data_employee['LastName']); ?></dd>
                        
                        <dt class="col-sm-3">Title</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($data_employee['Title']); ?></dd>
                        
                        <dt class="col-sm-3">Title of Courtesy</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($data_employee['TitleOfCourtesy']); ?></dd>
                        
                        <dt class="col-sm-3">Birth Date</dt>
                        <dd class="col-sm-9"><?php echo date('d F Y', strtotime($data_employee['BirthDate'])); // Format tanggal agar mudah dibaca ?></dd>
                        
                        <dt class="col-sm-3">Hire Date</dt>
                        <dd class="col-sm-9"><?php echo date('d F Y', strtotime($data_employee['HireDate'])); // Format tanggal agar mudah dibaca ?></dd>
                        
                        <dt class="col-sm-3">Address</dt>
                        <dd class="col-sm-9"><?php echo nl2br(htmlspecialchars($data_employee['Address'])); ?></dd>
                        
                        <dt class="col-sm-3">City</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($data_employee['City']); ?></dd>
                        
                        <dt class="col-sm-3">Region</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($data_employee['Region']); ?></dd>
                        
                        <dt class="col-sm-3">Postal Code</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($data_employee['PostalCode']); ?></dd>
                        
                        <dt class="col-sm-3">Country</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($data_employee['Country']); ?></dd>
                        
                        <dt class="col-sm-3">Home Phone</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($data_employee['HomePhone']); ?></dd>
                        
                        <dt class="col-sm-3">Extension</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($data_employee['Extension']); ?></dd>
                        
                        <dt class="col-sm-3">Reports To</dt>
                        <dd class="col-sm-9">
                            <?php 
                                // Jika ada atasan, tampilkan ID-nya. Bisa dikembangkan menjadi link ke detail atasan.
                                echo $data_employee['ReportsTo'] ? htmlspecialchars($data_employee['ReportsTo']) : 'N/A'; 
                            ?>
                        </dd>
                        
                        <dt class="col-sm-3">Notes</dt>
                        <dd class="col-sm-9"><?php echo nl2br(htmlspecialchars($data_employee['Notes'])); ?></dd>
                    </dl>
                </div>
                <div class="card-footer">
                    <a href="index.php?page=employees&action=list" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                    <a href="index.php?page=employees&action=edit&id=<?php echo $data_employee['EmployeeID']; ?>" class="btn btn-warning float-end">
                        <i class="fas fa-edit"></i> Edit Employee
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>