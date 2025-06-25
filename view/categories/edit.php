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
                        <li class="breadcrumb-item"><a href="index.php?page=categories&action=list">Categories</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="app-content">
        <div class="container-fluid">
            <div class="card card-warning card-outline mb-4">
                <div class="card-header">
                    <div class="card-title">Form Edit Category</div>
                </div>
                <form action="index.php?page=categories&action=update" method="POST">
                    <div class="card-body">
                        
                        <input type="hidden" name="CategoryID" value="<?php echo htmlspecialchars($data_category['CategoryID']); ?>">
                        <div class="row mb-3">
                            <label for="CategoryName" class="col-sm-2 col-form-label">Category Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="CategoryName" name="CategoryName" value="<?php echo htmlspecialchars($data_category['CategoryName']); ?>" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="Description" class="col-sm-2 col-form-label">Description</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="Description" name="Description" value="<?php echo htmlspecialchars($data_category['Description']); ?>">
                            </div>
                        </div>
                        
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update Categories</button>
                        <a href="index.php?page=categories&action=list" class="btn btn-secondary float-end">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>