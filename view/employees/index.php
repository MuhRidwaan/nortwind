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
                        <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($title); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="app-content">
        <div class="container-fluid">

         <?php display_flash_message(); // Menampilkan notifikasi ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Employees List</h3>
                    <div class="card-tools">

                     <form action="index.php" method="GET" class="d-inline-flex align-items-center">
                        <input type="hidden" name="page" value="employees">
                        <input type="hidden" name="action" value="list">
                        
                        <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari..." value="<?php echo htmlspecialchars($search_term ?? ''); ?>">
                        <button type="submit" class="btn btn-primary btn-sm ms-2">
                           <i class="bi bi-search"></i>
                        </button>
                        <a href="index.php?page=employees&action=list" class="btn btn-secondary btn-sm ms-1">Reset</a>
                    </form>
                        <a href="index.php?page=employees&action=create" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add New
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>EmployeeID</th>
                                <th>Full Name</th>
                                <th>Title</th>
                                <th>City</th>
                                <th>Country</th>
                                <th>Phone</th>
                                <th style="width: 180px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($list_employee)): ?>
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data untuk ditampilkan.</td>
                                </tr>
                            <?php else: ?>
                                <?php 
                    
                                ?>
                                <?php $nomor = 1; ?>
                                <?php foreach ($list_employee as $employee): ?>
                                    <tr>
                                        <td><?php echo $nomor++; ?>.</td>
                                        <td><?php echo htmlspecialchars($employee['EmployeeID']); ?></td>
                                        <td>
                                            <a href="index.php?page=employees&action=detail&id=<?php echo $employee['EmployeeID']; ?>">
                                                <?php echo htmlspecialchars($employee['FirstName'] . ' ' . $employee['LastName']); ?>
                                            </a>
                                        </td>
                                        <td><?php echo htmlspecialchars($employee['Title']); ?></td>
                                        <td><?php echo htmlspecialchars($employee['City']); ?></td>
                                        <td><?php echo htmlspecialchars($employee['Country']); ?></td>
                                        <td><?php echo htmlspecialchars($employee['HomePhone']); ?></td>
                                        <td>
                                            <a href="index.php?page=employees&action=edit&id=<?php echo $employee['EmployeeID']; ?>" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="index.php?page=employees&action=delete&id=<?php echo $employee['EmployeeID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this employee?');">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <?php 
                        // Merender link pagination
                        if (isset($pagination)) {
                            echo $pagination->render();
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</main>