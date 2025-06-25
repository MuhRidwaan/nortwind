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
                        <li class="breadcrumb-item"><a href="index.php?page=categories&action=list">categories</a></li>
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
                    <div class="card-title">Form Categories</div>
                </div>
                <form action="index.php?page=categories&action=store" method="POST">
                    <div class="card-body">
                        
                        <div class="row mb-3">
                            <label for="CategoryName" class="col-sm-2 col-form-label">Category Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="CategoryName" name="CategoryName" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="Description" class="col-sm-2 col-form-label">Dedscription</label>
                            <div class="col-sm-10">
                                <textarea type="text" class="form-control" id="Description" name="Description"> </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="index.php?page=categories&action=list" class="btn btn-secondary float-end">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>