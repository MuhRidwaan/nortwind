<main class="app-main">
    <!-- Header Konten -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6"><h3 class="mb-0">Dashboard</h3></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Konten Utama -->
    <div class="app-content">
        <div class="container-fluid">
            <!-- Baris Info Box -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-primary">
                        <div class="inner">
                            <h3><?php echo htmlspecialchars($stats['new_orders_this_month'] ?? 0); ?></h3>
                            <p>Pesanan Baru (Bulan Ini)</p>
                        </div>
                        <div class="small-box-icon"><i class="bi bi-bag-check-fill"></i></div>
                        <a href="index.php?page=orders" class="small-box-footer link-light">Lihat Detail <i class="bi bi-arrow-right-circle-fill"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                     <div class="small-box text-bg-success">
                        <div class="inner">
                            <h3><?php echo "Rp " . number_format($stats['total_revenue'] ?? 0, 0, ',', '.'); ?></h3>
                            <p>Total Pendapatan</p>
                        </div>
                        <div class="small-box-icon"><i class="bi bi-cash-coin"></i></div>
                        <a href="#" class="small-box-footer link-light">&nbsp;</a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-warning">
                        <div class="inner">
                            <h3><?php echo htmlspecialchars($stats['total_customers'] ?? 0); ?></h3>
                            <p>Total Pelanggan</p>
                        </div>
                        <div class="small-box-icon"><i class="bi bi-people-fill"></i></div>
                        <a href="index.php?page=customers" class="small-box-footer link-dark">Lihat Detail <i class="bi bi-arrow-right-circle-fill"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-danger">
                        <div class="inner">
                             <h3><?php echo htmlspecialchars($stats['total_products'] ?? 0); ?></h3>
                            <p>Produk Aktif</p>
                        </div>
                        <div class="small-box-icon"><i class="bi bi-box-seam-fill"></i></div>
                        <a href="index.php?page=products" class="small-box-footer link-light">Lihat Detail <i class="bi bi-arrow-right-circle-fill"></i></a>
                    </div>
                </div>
            </div>

            <!-- Baris Grafik Garis -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="card-header"><h3 class="card-title">Grafik Penjualan (12 Bulan Terakhir)</h3></div>
                        <div class="card-body">
                            <div id="sales-line-chart" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Baris Grafik Lainnya -->
            <div class="row">
                <div class="col-lg-7">
                    <div class="card mb-4">
                        <div class="card-header"><h3 class="card-title">Produk Terlaris (Berdasarkan Unit)</h3></div>
                        <div class="card-body">
                            <div id="top-products-bar-chart" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card mb-4">
                        <div class="card-header"><h3 class="card-title">Penjualan per Kategori</h3></div>
                        <div class="card-body">
                            <div id="category-donut-chart" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Skrip untuk menginisialisasi ApexCharts dengan data dinamis -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- GRAFIK PENJUALAN BULANAN (LINE CHART) ---
    const salesLineChartOptions = {
        series: [{
            name: 'Pendapatan',
            data: <?php echo json_encode($sales_chart_data['data']); ?>
        }],
        chart: { type: 'area', height: 300, toolbar: { show: false } },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth' },
        xaxis: { type: 'category', categories: <?php echo json_encode($sales_chart_data['labels']); ?> },
        yaxis: { labels: { formatter: value => "Rp " + new Intl.NumberFormat('id-ID').format(value) } },
        tooltip: { y: { formatter: value => "Rp " + new Intl.NumberFormat('id-ID').format(value) } },
        colors: ['#0d6efd']
    };
    if(document.querySelector("#sales-line-chart")) {
        const salesLineChart = new ApexCharts(document.querySelector("#sales-line-chart"), salesLineChartOptions);
        salesLineChart.render();
    }

    // --- GRAFIK PENJUALAN PER KATEGORI (DONUT CHART) ---
    const categoryDonutChartOptions = {
        series: <?php echo json_encode($sales_by_category_data['data']); ?>,
        labels: <?php echo json_encode($sales_by_category_data['labels']); ?>,
        chart: { type: 'donut', height: 300 },
        colors: ['#0d6efd', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6610f2', '#6f42c1', '#fd7e14'],
        legend: { position: 'bottom' },
        tooltip: { y: { formatter: value => "Rp " + new Intl.NumberFormat('id-ID').format(value) } },
        responsive: [{ breakpoint: 480, options: { chart: { width: 200 }, legend: { position: 'bottom' } } }]
    };
    if(document.querySelector("#category-donut-chart")) {
        const categoryDonutChart = new ApexCharts(document.querySelector("#category-donut-chart"), categoryDonutChartOptions);
        categoryDonutChart.render();
    }

    // --- GRAFIK PRODUK TERLARIS (BAR CHART) ---
    const topProductsBarChartOptions = {
        series: [{
            name: 'Unit Terjual',
            data: <?php echo json_encode($top_products_data['data']); ?>
        }],
        chart: { type: 'bar', height: 300 },
        plotOptions: { bar: { borderRadius: 4, horizontal: true } },
        dataLabels: { enabled: false },
        xaxis: { categories: <?php echo json_encode($top_products_data['labels']); ?> }
    };
    if(document.querySelector("#top-products-bar-chart")) {
        const topProductsBarChart = new ApexCharts(document.querySelector("#top-products-bar-chart"), topProductsBarChartOptions);
        topProductsBarChart.render();
    }
});
</script>
