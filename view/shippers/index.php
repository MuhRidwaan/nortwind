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

         <?php display_flash_message(); ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Shippers List</h3>
                    <div class="card-tools">

                     <form action="index.php" method="GET" class="d-inline-flex align-items-center">
                        <input type="hidden" name="page" value="shippers">
                        <input type="hidden" name="action" value="list">
                        <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari shipper..." value="<?php echo htmlspecialchars($search_term ?? ''); ?>">
                        <button type="submit" class="btn btn-primary btn-sm ms-2">
                           <i class="bi bi-search"></i>
                        </button>
                        <a href="index.php?page=shippers&action=list" class="btn btn-secondary btn-sm ms-1">Reset</a>
                    </form>
                        <a href="index.php?page=shippers&action=create" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add New
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Company Name</th>
                                <th>Phone</th>
                                <th style="width: 200px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($list_shipper)): ?>
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada data untuk ditampilkan.</td>
                                </tr>
                            <?php else: ?>
                                <?php $nomor = 1; ?>
                                <?php foreach ($list_shipper as $shipper): ?>
                                    <tr>
                                        <td><?php echo $nomor++; ?>.</td>
                                        <td><?php echo htmlspecialchars($shipper['CompanyName']); ?></td>
                                        <td><?php echo htmlspecialchars($shipper['Phone']); ?></td>
                                        <td>
                                            <a href="index.php?page=shippers&action=detail&id=<?php echo $shipper['ShipperID']; ?>" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                            <a href="index.php?page=shippers&action=edit&id=<?php echo $shipper['ShipperID']; ?>" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="index.php?page=shippers&action=delete&id=<?php echo $shipper['ShipperID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this shipper?');">
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
                        if (isset($pagination)) {
                            echo $pagination->render();
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</main>
