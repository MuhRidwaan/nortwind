<?php
// file: classes/Pagination.php

class Pagination {
    private $total_records;
    private $records_per_page;
    private $current_page;
    private $base_url;
    private $total_pages;

    public function __construct($total_records, $current_page = 1, $records_per_page = 10, $base_url = '') {
        $this->total_records = (int)$total_records;
        $this->records_per_page = (int)$records_per_page;
        $this->base_url = $base_url;
        $this->total_pages = ceil($this->total_records / $this->records_per_page);
        $this->setCurrentPage($current_page);
    }

    private function setCurrentPage($page) {
        $this->current_page = (int)$page;
        if ($this->current_page < 1) {
            $this->current_page = 1;
        } elseif ($this->current_page > $this->total_pages && $this->total_pages > 0) {
            $this->current_page = $this->total_pages;
        }
    }

    public function getLimit(): int {
        return $this->records_per_page;
    }

    public function getOffset(): int {
        return ($this->current_page - 1) * $this->records_per_page;
    }

    public function render(): string {
        if ($this->total_pages <= 1) {
            return '';
        }

        $html = '<ul class="pagination pagination-sm m-0 float-end">';

        // Tombol "Previous"
        if ($this->current_page > 1) {
            $html .= '<li class="page-item"><a class="page-link" href="' . $this->buildUrl($this->current_page - 1) . '">&laquo;</a></li>';
        } else {
            $html .= '<li class="page-item disabled"><span class="page-link">&laquo;</span></li>';
        }

        // --- Logika untuk menampilkan nomor halaman terbatas ---
        $links_to_show = 5; // Jumlah link angka yang ditampilkan
        $start = max(1, $this->current_page - floor($links_to_show / 2));
        $end = min($this->total_pages, $start + $links_to_show - 1);

        if ($end - $start + 1 < $links_to_show) {
            $start = max(1, $end - $links_to_show + 1);
        }

        // Tampilkan "..." di awal jika perlu
        if ($start > 1) {
            $html .= '<li class="page-item"><a class="page-link" href="' . $this->buildUrl(1) . '">1</a></li>';
            if ($start > 2) {
                $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }
        
        // Loop untuk nomor halaman
        for ($i = $start; $i <= $end; $i++) {
            if ($i == $this->current_page) {
                $html .= '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
            } else {
                $html .= '<li class="page-item"><a class="page-link" href="' . $this->buildUrl($i) . '">' . $i . '</a></li>';
            }
        }

        // Tampilkan "..." di akhir jika perlu
        if ($end < $this->total_pages) {
            if ($end < $this->total_pages - 1) {
                $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
            $html .= '<li class="page-item"><a class="page-link" href="' . $this->buildUrl($this->total_pages) . '">' . $this->total_pages . '</a></li>';
        }
        // --- Akhir Logika ---

        // Tombol "Next"
        if ($this->current_page < $this->total_pages) {
            $html .= '<li class="page-item"><a class="page-link" href="' . $this->buildUrl($this->current_page + 1) . '">&raquo;</a></li>';
        } else {
            $html .= '<li class="page-item disabled"><span class="page-link">&raquo;</span></li>';
        }

        $html .= '</ul>';
        return $html;
    }

    private function buildUrl($page): string {
        $url_parts = parse_url($this->base_url);
        if (isset($url_parts['query'])) {
            parse_str($url_parts['query'], $params);
        } else {
            $params = [];
        }
        $params['p'] = $page;
        return $url_parts['path'] . '?' . http_build_query($params);
    }
}
