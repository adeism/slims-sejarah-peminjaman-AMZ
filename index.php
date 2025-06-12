<?php
// Set timezone explicitly to Asia/Jakarta (WIB - Indonesia)
date_default_timezone_set('Asia/Jakarta');

// IP based access limitation
require LIB.'ip_based_access.inc.php';
do_checkIP('smc');
do_checkIP('smc-reporting');

// start the session
require SB . 'admin/default/session.inc.php';
require SB . 'admin/default/session_check.inc.php';

// privileges checking
$can_read = utility::havePrivilege('reporting', 'r');
if (!$can_read) {
    die('<div class="errorBox">' . __('You don\'t have enough privileges to view this section!') . '</div>');
}

// GUI libraries
require SIMBIO . 'simbio_GUI/table/simbio_table.inc.php';
require SIMBIO . 'simbio_GUI/paging/simbio_paging.inc.php';
?>

<style>    /* SLiMS Native Design with Compact Optimization */
    :root {
        --slims-primary: #0056b3;
        --slims-secondary: #6c757d;
        --slims-light: #f8f9fa;
        --slims-border: #dee2e6;
        --slims-hover: #e9ecef;
        --compact-spacing: 8px;
        --compact-radius: 6px;
    }

    /* Compact menuBox styling following SLiMS design */
    .menuBoxInner {
        padding: 12px 16px !important;
        background: #fff;
        border: 1px solid var(--slims-border);
        border-radius: var(--compact-radius);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .per_title {
        background: var(--slims-primary);
        color: white;
        padding: 12px 16px !important;
        margin: -12px -16px 12px -16px !important;
        border-radius: var(--compact-radius) var(--compact-radius) 0 0;
        font-size: 1.1em;
    }
    
    .per_title h2 {
        margin: 0 !important;
        font-size: 1.2em !important;
        font-weight: 600;
        display: flex;
        align-items: center;
    }    /* Compact search container */
    .search-main-container {
        background: white;
        border: 1px solid var(--slims-border);
        border-radius: var(--compact-radius);
        padding: 12px !important;
        margin: 0 !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    /* Horizontal layout for search elements */
    .compact-search-row {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
    }

    .compact-search-row:last-child {
        margin-bottom: 0;
    }/* Dropdown search type selector */
    .search-type-dropdown {
        position: relative;
        flex: 0 0 auto;
        min-width: 120px;
    }    .search-type-dropdown select {
        width: 100%;
        padding: 6px 12px;
        font-size: 0.9rem;
        border: 1px solid var(--slims-border);
        border-radius: var(--compact-radius);
        background: white;
        color: var(--slims-secondary);
        outline: none;
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 8px center;
        background-repeat: no-repeat;
        background-size: 16px;
        padding-right: 32px;
        cursor: pointer;
        transition: all 0.15s;
        height: 38px;
    }

    .search-type-dropdown select:focus {
        border-color: var(--slims-primary);
        box-shadow: 0 0 0 2px rgba(0,86,179,0.1);
    }

    .search-type-dropdown select:hover {
        border-color: var(--slims-primary);
    }    /* Combined date range with better styling */    .date-range-wrapper {
        display: flex;
        align-items: center;
        gap: 6px;
        background: white;
        border: 1px solid var(--slims-border);
        border-radius: var(--compact-radius);
        padding: 6px 10px;
        flex: 0 1 300px;
        font-size: 0.9rem;
        height: 38px;
    }

    .date-range-wrapper .date-range-label {
        font-size: 0.9rem;
        color: var(--slims-secondary);
        white-space: nowrap;
        margin-right: 4px;
    }

    .date-input {
        border: none;
        background: transparent;
        padding: 2px;
        font-size: 0.9rem;
        outline: none;
        width: 105px;
        color: var(--slims-primary);
    }

    .date-separator {
        color: var(--slims-secondary);
        font-size: 0.8em;
        margin: 0 2px;
        font-weight: 500;
    }

    /* Enhanced quick filters with year option */
    .quick-filters {
        display: flex;
        gap: 3px;
        flex-wrap: nowrap;
        align-items: center;
    }    .quick-filter-btn {
        padding: 8px 12px;
        font-size: 0.9rem;
        background: white;
        border: 1px solid var(--slims-border);
        border-radius: var(--compact-radius);
        cursor: pointer;
        transition: all 0.15s;
        color: var(--slims-secondary);
        font-weight: 500;
        white-space: nowrap;
    }

    .quick-filter-btn:hover {
        background: var(--slims-primary);
        color: white;
        border-color: var(--slims-primary);
        transform: translateY(-1px);
    }/* Search input and button - more compact */
    .plugin-search-container {
        display: flex;
        gap: 6px;
        align-items: stretch;
        flex: 1 1 400px;
    }    .plugin-search-container input[type="text"] {
        flex: 1;
        padding: 8px 12px;
        border: 1px solid var(--slims-border);
        border-radius: var(--compact-radius);
        font-size: 0.9rem;
        outline: none;
        background: white;
        height: 38px;
    }

    .plugin-search-container input[type="text"]:focus {
        border-color: var(--slims-primary);
        box-shadow: 0 0 0 2px rgba(0,86,179,0.1);
    }    .plugin-search-container .s-btn {
        padding: 8px 20px;
        font-size: 0.9rem;
        background: var(--slims-primary);
        color: white;
        border: none;
        border-radius: var(--compact-radius);
        cursor: pointer;
        transition: all 0.15s;
        white-space: nowrap;
        font-weight: 500;
        height: 38px;
        min-width: 60px;
    }

    .plugin-search-container .s-btn:hover {
        background: #004494;
        transform: translateY(-1px);
    }/* Compact statistics cards */
    .stat-cards-container {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        margin: 12px 0;
        padding: 0;
    }

    .stat-card {
        background: white;
        border: 1px solid var(--slims-border);
        border-radius: var(--compact-radius);
        padding: 16px;
        text-align: center;
        transition: all 0.2s;
    }

    .stat-card:hover {
        border-color: var(--slims-primary);
        box-shadow: 0 2px 6px rgba(0,86,179,0.1);
    }    .stat-card h3 {
        font-size: 0.9rem;
        color: var(--slims-secondary);
        margin: 0 0 8px 0;
        font-weight: 600;
        text-transform: uppercase;
    }

    .stat-card p {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--slims-primary);
        margin: 0;
        line-height: 1;
    }    /* Compact table styling */
    .s-table {
        font-size: 0.9rem;
        margin-top: 12px;
    }

    .s-table th {
        padding: 8px 10px;
        background: var(--slims-light);
        border-bottom: 2px solid var(--slims-border);
        font-size: 0.9rem;
        font-weight: 600;
    }

    .s-table td {
        padding: 8px 10px;
        border-bottom: 1px solid #f0f0f0;
        font-size: 0.9rem;
    }/* Compact info and button styling */
    .infoBox {
        padding: 12px 16px;
        margin: 8px 0;
        font-size: 0.9em;
        background-color: #f3f4f6;
        border-radius: var(--compact-radius);
        border-left: 4px solid #6c757d;
    }

    .infoBox .s-btn {
        padding: 8px 16px;
        font-size: 0.9em;
        margin-left: 6px;
        border-radius: var(--compact-radius);
        background-color: #6c757d;
        color: white;
        border: none;
        font-weight: 500;
    }
    
    .infoBox .s-btn:hover {
        background-color: #5a6268;
    }

    /* Hide search hints for compact design */
    .search-hints {
        display: none;
    }

    .search-type-label {
        display: none;
    }    /* Responsive adjustments for compact design */
    @media (max-width: 1200px) {
        .compact-search-row {
            flex-wrap: wrap;
            gap: 6px;
        }
        
        .search-type-dropdown {
            flex: 1 1 120px;
            margin-bottom: 6px;
        }
        
        .date-range-wrapper {
            min-width: 200px;
        }
    }

    @media (max-width: 768px) {
        .compact-search-row {
            flex-direction: column;
            align-items: stretch;
            gap: 8px;
        }
        
        .search-type-dropdown,
        .date-range-wrapper,
        .plugin-search-container,
        .quick-filters {
            width: 100%;
            min-width: 100%;
        }
        
        .search-type-dropdown select {
            font-size: 0.9em;
            padding: 8px 12px;
        }
        
        .date-range-wrapper {
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .date-input {
            width: 120px;
        }
        
        .quick-filters {
            justify-content: center;
        }
        
        .stat-cards-container {
            grid-template-columns: 1fr 1fr;
        }
    }
        
        .quick-filters {
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .stat-cards-container {
            grid-template-columns: 1fr;
        }
        
        .tab-search {
            flex-direction: column;
            gap: 2px;
        }
          .tab-search label {
            width: 100%;
            text-align: center;
        }
    }
    
    /* Search hints and helpers */
    .search-hints {
        margin-top: 12px;
        display: flex;
        gap: 16px;
        align-items: center;
        font-size: 0.85em;
        color: #6b7280;
    }
    .search-hint {
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .search-hint-icon {
        width: 16px;
        height: 16px;
        background: #e5e7eb;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        color: #6b7280;
    }

    /* Minimalist stat cards */
    .stat-cards-container {
        display: flex;
        flex-wrap: wrap;
        gap: 18px;
        margin: 24px 0 18px 0;
        justify-content: space-between;
    }
    .stat-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        flex: 1 1 180px;
        min-width: 180px;
        padding: 22px 18px 18px 18px;
        text-align: center;
        transition: box-shadow 0.2s, transform 0.2s;
        border: 1.5px solid #e3eaf2;
    }
    .stat-card:hover {
        box-shadow: 0 6px 18px rgba(0,86,179,0.10);
        transform: translateY(-2px) scale(1.02);
    }
    .stat-card h3 {
        font-size: 1.08em;
        color: #444;
        font-weight: 600;
        margin: 0 0 10px 0;
        letter-spacing: 0.2px;
    }
    .stat-card p {
        font-size: 2.1em;
        font-weight: 700;
        color: #222;
        margin: 0;
        letter-spacing: 0.5px;
    }

    /* Table improvements */
    .s-table {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        background: #fff;
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
        margin-bottom: 24px;
    }
    .s-table th {
        background: #e9eef6;
        color: #222;
        font-weight: 700;
        padding: 14px 16px;
        border-bottom: 2px solid #e3eaf2;
        position: sticky;
        top: 0;
        z-index: 2;
    }
    .s-table td {
        padding: 13px 16px;
        border-bottom: 1px solid #f0f0f0;
        color: #333;
        background: #fff;
    }
    .s-table tr:nth-child(even) td {
        background: #f7fafd;
    }
    .s-table tr:hover td {
        background: #e9f2ff;
    }
    .s-table tr:last-child td {
        border-bottom: none;
    }

    /* Badges and buttons */
    .badge {
        padding: 7px 14px;
        border-radius: 16px;
        font-weight: 600;
        font-size: 1em;
        background: #e9eef6;
        color: #0056b3;
        border: none;
    }
    .badge-info {
        background: #dbe7f6;
        color: #0056b3;
    }
    .s-btn {
        border-radius: 999px;
        font-weight: 600;
        font-size: 1.05em;
    }

    /* Info box styling */
    .infoBox {
        background: #f4f7fb;
        border-radius: 12px;
        padding: 16px 18px;
        margin-bottom: 22px;
        border-left: 5px solid #0056b3;
        font-size: 1.08em;
        color: #222;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
    }

    /* Pagination styling */
    .dataListHeader, .paging-area {
        background: transparent;
        margin: 18px 0;
    }
    .pagingList {
        display: inline-flex;
        padding: 0;
        margin: 0;
        gap: 2px;
    }
    .pagingList li {
        display: inline-block;
    }
    .pagingList li a {
        padding: 7px 14px;
        border: 1.5px solid #e3eaf2;
        border-radius: 999px;
        color: #0056b3;
        text-decoration: none;
        transition: background 0.2s, color 0.2s;
        font-weight: 500;
    }
    .pagingList li a:hover {
        background: #e9eef6;
        color: #003e80;
    }    .pagingList li.active a {
        background: #0056b3;
        color: #fff;
        border-color: #0056b3;
    }

    @media (max-width: 900px) {
        .stat-cards-container {
            flex-direction: column;
            gap: 14px;
        }
        .stat-card {
            min-width: 0;
        }
        .infoBox {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }        .plugin-search-container .s-btn {
            padding: 12px 20px;
            font-size: 1em;
        }
        .tab-search {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
        .date-range-wrapper {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
        .date-range-container {
            width: 100%;
        }
        .search-hints {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }        .quick-filters {
            justify-content: flex-start;
        }    }
    @media (max-width: 600px) {
        .menuBoxInner, .sub_section.p-3 {
            padding: 8px !important;
        }
        .search-main-container {
            padding: 16px;
            margin: 12px 0;
        }
        .stat-card {
            padding: 14px 8px 12px 8px;
        }
        .plugin-search-container {
            flex-direction: column;
            border-radius: 12px;
        }
        .plugin-search-container input[name="keywords"].form-control {
            padding: 12px 16px;
            font-size: 1em;
            width: 100%;
        }
        .plugin-search-container .s-btn {
            width: 100%;
            margin: 8px 0 4px 0;
            border-radius: 8px;
        }
        .date-input {
            min-width: 110px;
            font-size: 0.85em;
        }
        .s-table th, .s-table td {
            padding: 8px 6px;
            font-size: 0.9em;
        }
    }    @media print {
        /* Hide non-essential elements */
        .no-print, #mainMenu, #sideMenu, #header, #footer, .s-header, .s-main-menu, .s-sidebar, .menuBox, .paging-area, 
        .per_title, .print-buttons, button.s-btn, a.s-btn, .quick-filters, .search-main-container, .dataListHeader {
            display: none !important;
            visibility: hidden !important;
        }
        
        /* Hide unwanted text that appears in the print */
        #footer-wrap, .s-footer, div.subfoot, .subfoot-content, .footer-content, 
        .simbio-version, .slims-version, .s-footer-tagline, .SLiMS-info, .icon-question-sign {
            display: none !important;
            visibility: hidden !important;
        }
        
        /* Basic page setup for printing */
        body, html {
            background-color: #fff !important;
            color: #000 !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            height: auto !important;
            overflow: visible !important;
            font-size: 10pt !important;
            font-family: Arial, sans-serif !important;
        }
        
        /* Layout fixes */
        #content, #contentArea, .content, div.container-fluid, div.main-content {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
            float: none !important;
            display: block !important;
            box-shadow: none !important;
            background-color: #fff !important;
        }
        
        .sub_section.p-3 {
            padding: 0 !important;
            margin: 0 !important;
            border: none !important;
            box-shadow: none !important;
        }
        
        /* Add the title at the top */
        .sub_section.p-3::before {
            content: "Sejarah Peminjaman AMZ" !important;
            display: block !important;
            font-size: 18pt !important;
            font-weight: bold !important;
            text-align: center !important;
            margin-bottom: 10px !important;
            padding-bottom: 5px !important;
            border-bottom: 1px solid #000 !important;
        }
        
        /* Make KPI cards match the provided screenshot */
        .stat-cards-container {
            display: block !important;
            width: 100% !important;
            margin: 0 0 20px 0 !important;
            border-spacing: 0 !important;
        }
        
        .stat-card {
            display: block !important;
            width: 100% !important;
            border: 1px solid #000 !important;
            margin-bottom: 8px !important;
            padding: 10px 0 !important;
            text-align: center !important;
            box-shadow: none !important;
            background: #fff !important;
            border-radius: 0 !important;
        }
        
        .stat-card h3 {
            text-transform: uppercase !important;
            font-size: 11pt !important;
            font-weight: bold !important;
            margin: 0 0 5px 0 !important;
            text-align: center !important;
        }
        
        .stat-card p {
            font-size: 13pt !important;
            font-weight: bold !important;
            margin: 0 !important;
            text-align: center !important;
        }
        
        /* Records found text */
        .infoBox {
            display: block !important;
            margin: 15px 0 !important;
            padding: 0 !important;
            border: none !important;
            background: transparent !important;
            text-align: left !important;
        }
        
        .infoBox span {
            display: block !important;
            font-weight: normal !important;
            font-size: 11pt !important;
        }
        
        /* Hide action buttons */
        .infoBox div, .action-buttons {
            display: none !important;
        }
        
        /* Table styling to match the screenshot */
        table.s-table {
            width: 100% !important;
            border-collapse: collapse !important;
            border-spacing: 0 !important;
            margin-top: 15px !important;
            table-layout: fixed !important;
        }
        
        /* Force all columns to display */
        table.s-table tr, table.s-table th, table.s-table td {
            display: table-cell !important;
            page-break-inside: avoid !important;
            border: 1px solid #000 !important;
        }
        
        /* Table header styling */
        table.s-table th {
            background-color: #fff !important;
            font-weight: bold !important;
            text-transform: uppercase !important;
            text-align: center !important;
            padding: 5px 3px !important;
            font-size: 9pt !important;
            vertical-align: middle !important;
            border-bottom: 1px solid #000 !important;
        }
        
        /* Table data styling */
        table.s-table td {
            padding: 5px 3px !important;
            font-size: 9pt !important;
            vertical-align: top !important;
            word-wrap: break-word !important;
        }
        
        /* Badge styling */
        .badge {
            background: transparent !important;
            border: none !important;
            padding: 0 !important;
            font-weight: normal !important;
            color: #000 !important;
            font-size: 9pt !important;
        }
        
        /* Fix table width allocation */
        table.s-table colgroup {
            display: table-column-group !important;
        }
        
        /* Column widths */
        table.s-table th:nth-child(1), table.s-table td:nth-child(1) { width: 25% !important; } /* Judul */
        table.s-table th:nth-child(2), table.s-table td:nth-child(2) { width: 10% !important; } /* Kode */
        table.s-table th:nth-child(3), table.s-table td:nth-child(3) { width: 10% !important; } /* ID */
        table.s-table th:nth-child(4), table.s-table td:nth-child(4) { width: 20% !important; } /* Nama */
        table.s-table th:nth-child(5), table.s-table td:nth-child(5) { width: 10% !important; } /* Pinjam */
        table.s-table th:nth-child(6), table.s-table td:nth-child(6) { width: 15% !important; } /* Kembali */
        table.s-table th:nth-child(7), table.s-table td:nth-child(7) { width: 10% !important; } /* Hari */
        
        /* Remove unwanted SLiMS version info and icons */
        body::after, html::after {
            display: none !important;
            content: '' !important;
        }
        
        /* Page settings */
        @page {
            margin: 15mm 10mm !important;
            size: portrait !important;
        }
    }
</style>



<div class="menuBox no-print">
    <div class="menuBoxInner loanIcon">        <div class="per_title">
            <h2><?php echo __('Sejarah Peminjaman AMZ'); ?></h2>
        </div>
        <div class="sub_section">            <div class="search-main-container">
                <form action="<?php echo $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']; ?>" method="get" id="searchForm">
                    <input type="hidden" name="search" value="true">
                    
                    <!-- Single Row Horizontal Layout -->
                    <div class="compact-search-row">
                        <!-- Search Type Dropdown -->
                        <div class="search-type-dropdown">
                            <select name="search_type">
                                <option value="all" <?php echo (!isset($_GET['search_type']) || $_GET['search_type'] == 'all') ? 'selected' : ''; ?>><?php echo __('Semua'); ?></option>
                                <option value="member_id" <?php echo (isset($_GET['search_type']) && $_GET['search_type'] == 'member_id') ? 'selected' : ''; ?>><?php echo __('ID'); ?></option>
                                <option value="member_name" <?php echo (isset($_GET['search_type']) && $_GET['search_type'] == 'member_name') ? 'selected' : ''; ?>><?php echo __('Nama'); ?></option>
                                <option value="title" <?php echo (isset($_GET['search_type']) && $_GET['search_type'] == 'title') ? 'selected' : ''; ?>><?php echo __('Judul'); ?></option>
                                <option value="item_code" <?php echo (isset($_GET['search_type']) && $_GET['search_type'] == 'item_code') ? 'selected' : ''; ?>><?php echo __('Kode'); ?></option>
                            </select>
                        </div>
                          <!-- Search Input and Button -->
                        <div class="plugin-search-container">
                            <input type="text" name="keywords" class="form-control" placeholder="<?php echo __('Kata kunci...'); ?>" value="<?php echo isset($_GET['keywords']) ? htmlspecialchars($_GET['keywords']) : ''; ?>">
                            <button type="submit" class="s-btn btn btn-primary">Cari</button>
                        </div>
                          <!-- Combined Date Range -->
                        <div class="date-range-wrapper">
                            <span class="date-range-label">📅</span>
                            <input type="date" name="start_date" id="start_date" class="date-input" value="<?php echo isset($_GET['start_date']) ? htmlspecialchars($_GET['start_date']) : ''; ?>" title="<?php echo __('Tanggal Mulai'); ?>">
                            <span class="date-separator">—</span>
                            <input type="date" name="end_date" id="end_date" class="date-input" value="<?php echo isset($_GET['end_date']) ? htmlspecialchars($_GET['end_date']) : ''; ?>" title="<?php echo __('Tanggal Akhir'); ?>">
                        </div>
                        
                        <!-- Quick Date Filters with Year -->
                        <div class="quick-filters">
                            <button type="button" class="quick-filter-btn" onclick="setQuickDate('today')" title="<?php echo __('Hari Ini'); ?>">Hari</button>
                            <button type="button" class="quick-filter-btn" onclick="setQuickDate('week')" title="<?php echo __('Minggu Ini'); ?>">Minggu</button>
                            <button type="button" class="quick-filter-btn" onclick="setQuickDate('month')" title="<?php echo __('Bulan Ini'); ?>">Bulan</button>
                            <button type="button" class="quick-filter-btn" onclick="setQuickDate('year')" title="<?php echo __('Tahun Ini'); ?>">Tahun</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
// Debug info (remove in production)
// if (isset($_GET['debug'])) {
//     echo '<pre>GET parameters: '; print_r($_GET); echo '</pre>';
// }

// main logic for search and display - show all data by default
$show_data = true; // Always show data
$is_search = isset($_GET['search']);

if ($show_data) {
    // get the search terms
    $keywords = isset($_GET['keywords']) ? $dbs->escape_string(trim($_GET['keywords'])) : '';
    $search_type = $_GET['search_type'] ?? 'all';
    $start_date = isset($_GET['start_date']) ? $dbs->escape_string(trim($_GET['start_date'])) : '';
    $end_date = isset($_GET['end_date']) ? $dbs->escape_string(trim($_GET['end_date'])) : '';

    // conditions
    $criteria = "1=1"; // Default condition to show all data
    $keyword_criteria = "";
    $date_criteria = "";

    if (!empty($keywords)) {
        switch ($search_type) {
            case 'member_id':
                $keyword_criteria = "lh.member_id LIKE '%$keywords%'";
                break;
            case 'member_name':
                $keyword_criteria = "lh.member_name LIKE '%$keywords%'";
                break;
            case 'title':
                $keyword_criteria = "lh.title LIKE '%$keywords%'";
                break;
            case 'item_code':
                $keyword_criteria = "lh.item_code LIKE '%$keywords%'";
                break;
            case 'all':
            default:
                $keyword_criteria = "(lh.member_id LIKE '%$keywords%' OR lh.member_name LIKE '%$keywords%' OR lh.title LIKE '%$keywords%' OR lh.item_code LIKE '%$keywords%')";
                break;
        }
    }

    // Date filter logic
    if (!empty($start_date) && !empty($end_date)) {
        $date_criteria = "DATE(lh.loan_date) BETWEEN '$start_date' AND '$end_date'";
    } elseif (!empty($start_date)) {
        $date_criteria = "DATE(lh.loan_date) >= '$start_date'";
    } elseif (!empty($end_date)) {
        $date_criteria = "DATE(lh.loan_date) <= '$end_date'";
    }    // Combine criteria
    if (!empty($keyword_criteria) && !empty($date_criteria)) {
        $criteria = "$keyword_criteria AND $date_criteria";
    } elseif (!empty($keyword_criteria)) {
        $criteria = $keyword_criteria;
    } elseif (!empty($date_criteria)) {
        $criteria = $date_criteria;
    }
    // If no filters, criteria remains "1=1" to show all data

    // number of records per page
    $num_recs_show = 20;

    // Always show data section
    echo '<div class="sub_section p-3">';
        
        // Paging
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page > 1) ? ($page - 1) * $num_recs_show : 0;

        // Total records query
        $total_q = $dbs->query("SELECT COUNT(DISTINCT lh.loan_id) FROM loan_history AS lh WHERE $criteria");
        $total_recs = $total_q->fetch_row()[0];

        // Statistic Card Queries
        $total_still_borrowed = 0;
        $total_unique_members = 0;
        $popular_month_year_text = __('Data Tidak Cukup'); // Default text
        $avg_loan_duration_text = __('Data Tidak Cukup'); // Default for new card

        if ($total_recs > 0) {
            // Total items still borrowed from the found set
            $still_borrowed_q = $dbs->query("SELECT COUNT(DISTINCT lh.loan_id) FROM loan_history AS lh WHERE $criteria AND lh.is_return = 0");
            $total_still_borrowed = $still_borrowed_q->fetch_row()[0];

            // Total unique members from the found set
            $unique_members_q = $dbs->query("SELECT COUNT(DISTINCT lh.member_id) FROM loan_history AS lh WHERE $criteria");
            $total_unique_members = $unique_members_q->fetch_row()[0];

            // Popular month and year query
            $popular_month_year_q_sql = "SELECT MONTHNAME(lh.loan_date) as loan_month, YEAR(lh.loan_date) as loan_year, COUNT(lh.loan_id) as loan_count 
                                         FROM loan_history AS lh 
                                         WHERE $criteria 
                                         GROUP BY loan_year, MONTH(lh.loan_date), loan_month
                                         ORDER BY loan_count DESC, loan_year DESC, MONTH(lh.loan_date) DESC
                                         LIMIT 1";
            $popular_month_year_q = $dbs->query($popular_month_year_q_sql);
            if ($popular_data = $popular_month_year_q->fetch_assoc()) {
                $month_name = $popular_data['loan_month'];
                $popular_month_year_text = $month_name . ' ' . $popular_data['loan_year'] . ' (' . $popular_data['loan_count'] . ' ' . __('peminjaman') . ')';
            }

            // Average loan duration query (only for returned items)
            $avg_duration_q_sql = "SELECT AVG(DATEDIFF(lh.return_date, lh.loan_date)) as avg_days 
                                   FROM loan_history AS lh 
                                   WHERE $criteria AND lh.is_return = 1 AND lh.return_date IS NOT NULL AND lh.loan_date IS NOT NULL";
            $avg_duration_q = $dbs->query($avg_duration_q_sql);
            if ($avg_data = $avg_duration_q->fetch_assoc()) {
                if ($avg_data['avg_days'] !== null) {
                    $avg_loan_duration_text = round($avg_data['avg_days'], 1) . ' ' . __('hari');
                }
            }
        }          // Display Compact Statistic Cards
        if ($total_recs > 0) {
            echo '<div class="stat-cards-container">';
            echo '  <div class="stat-card">';
            echo '    <h3>' . __('TOTAL UNIK PEMINJAM') . '</h3>';
            echo '    <p>' . $total_unique_members . '</p>'; 
            echo '  </div>';
            echo '  <div class="stat-card">';
            echo '    <h3>' . __('MASIH DIPINJAM') . '</h3>';
            echo '    <p>' . $total_still_borrowed . '</p>';
            echo '  </div>';
            echo '  <div class="stat-card">';
            echo '    <h3>' . __('RATA-RATA HARI') . '</h3>';
            echo '    <p>' . $avg_loan_duration_text . '</p>';
            echo '  </div>';
            echo '  <div class="stat-card">';
            echo '    <h3>' . __('PERIODE POPULER') . '</h3>';
            echo '    <p style="font-size: 1.2em;">' . $popular_month_year_text . '</p>';
            echo '  </div>';
            echo '</div>';
        }

        // Prepare data for spreadsheet export
        // The SLiMS spreadsheet.php utility will use these session variables
        $_SESSION['xls_card_data'] = [
            ['title' => __('Total Unik Peminjam'), 'value' => $total_unique_members],
            ['title' => __('Masih Dipinjam'), 'value' => $total_still_borrowed],
            ['title' => __('Periode Populer'), 'value' => $popular_month_year_text],
            ['title' => __('Rata-rata Lama Pinjam'), 'value' => $avg_loan_duration_text],
        ];

        $xlsquery = "SELECT lh.title AS '" . __('Judul') . "', ".
                    "lh.item_code AS '" . __('Kode Eksemplar') . "', ".
                    "lh.member_id AS '" . __('ID Anggota') . "', ".
                    "lh.member_name AS '" . __('Nama Anggota') . "', ".
                    "DATE_FORMAT(lh.loan_date, '%d-%m-%Y') AS '" . __('Tanggal Pinjam') . "', ".
                    "IF(lh.is_return=0, '" . __('Masih Dipinjam') . "', DATE_FORMAT(lh.return_date, '%d-%m-%Y')) AS '" . __('Tanggal Kembali') . "', ".
                    "IF(lh.is_return = 1, DATEDIFF(lh.return_date, lh.loan_date), DATEDIFF(CURDATE(), lh.loan_date)) AS '" . __('Total Hari') . "' ".
             "FROM loan_history AS lh WHERE $criteria ".
             "ORDER BY lh.loan_date DESC";
        $_SESSION['xlsquery'] = $xlsquery;
        $_SESSION['tblout'] = "Sejarah_eminjaman_AMZ_" . date('Ymd');


        // MODIFIED: Added DATEDIFF calculation to SQL query and changed date format
        $sql = "SELECT lh.title, lh.item_code, lh.member_id, lh.member_name, 
                       DATE_FORMAT(lh.loan_date, '%d-%m-%Y') as loan_date, 
                       IF(lh.is_return=0, 'Masih Dipinjam', DATE_FORMAT(lh.return_date, '%d-%m-%Y')) as return_date,
                       IF(lh.is_return = 1, DATEDIFF(lh.return_date, lh.loan_date), DATEDIFF(CURDATE(), lh.loan_date)) AS total_hari
                FROM loan_history AS lh WHERE $criteria 
                ORDER BY lh.loan_date DESC LIMIT $offset, $num_recs_show";
        $main_q = $dbs->query($sql);
        
        // REMOVED DUPLICATE STATISTIC CARD BLOCK (this was the primary fix for the duplicate card issue)
        // The block that previously started with "if ($total_recs > 0) { echo '<div class="stat-cards-container no-print">';"
        // and displayed cards with <h5> tags has been removed. The cards are now displayed only once above.        // Combined info bar: records found, export, print
        echo '<div class="infoBox" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">';
            // Left side: Record count and search criteria
            $search_info = $total_recs . ' ' . __('records found');
            if (!empty($start_date) && !empty($end_date)) {
                $search_info .= ' | ' . __('Periode') . ': ' . htmlspecialchars($start_date) . ' - ' . htmlspecialchars($end_date);
            }
            echo '<span>' . $search_info . '</span>';
            
            // Right side: Buttons (only if records exist for export/print)
            if ($total_recs > 0) {
                // Store title and date range information for Excel export
                $_SESSION['tblout_title'] = __('Sejarah Peminjaman AMZ');
                $_SESSION['tblout_date'] = (!empty($start_date) && !empty($end_date)) ? 
                    __('Periode') . ': ' . htmlspecialchars($start_date) . ' - ' . htmlspecialchars($end_date) : 
                    __('Tanggal Cetak') . ': ' . date('d-m-Y');
                // Add buttons wrapped in a div that will be hidden in print
                echo '<div class="action-buttons no-print">';
                $spreadsheet_url = AWB . 'modules/reporting/spreadsheet.php';
                echo '<a href="' . htmlspecialchars($spreadsheet_url) . '" target="_blank" class="s-btn btn btn-secondary">' . __('Ekspor ke format spreadsheet') . '</a>';
                // Disabled print button with tooltip
                echo '<button class="s-btn btn btn-secondary" style="margin-left: 8px;" disabled title="Fitur cetak halaman masih dalam proses pengembangan">' . __('Cetak Halaman') . ' <span style="font-size:12px;">(coming soon)</span></button>';
                echo '</div>';
            }
        echo '</div>'; // Close infoBox flex container
        
        if ($total_recs > 0) {
            // Pagination at the top, similar to dataListHeader in cr1.php
            echo '<div class="dataListHeader no-print" style="padding: 3px; margin-bottom:10px;"><div class="text-right" id="pagingBoxTop">' . simbio_paging::paging($total_recs, $num_recs_show, 10) . '</div></div>';            $table = new simbio_table();
            $table->table_attr = 'class="s-table table table-bordered" id="loan-history-table"';
            $table->table_header_attr = 'class="dataListHeader" style="font-weight: bold; background-color: #e9eef6;"';
            // Set header with proper translations matching the screenshot
            $table->setHeader(array(
                __('Judul'),
                __('Kode Eksemplar'),
                __('ID Anggota'),
                __('Nama Anggota'),
                __('Tanggal Pinjam'),
                __('Tanggal Kembali'),
                __('Total Hari')
            ));

            $row_num = 1;
            while ($data = $main_q->fetch_assoc()) {
                // Formatting data
                $data['title'] = htmlspecialchars($data['title']);
                $data['item_code'] = htmlspecialchars($data['item_code']);
                $data['member_id'] = htmlspecialchars($data['member_id']);
                $data['member_name'] = htmlspecialchars($data['member_name']);
                
                if ($data['return_date'] == 'Masih Dipinjam') {
                    $data['return_date'] = '<span class="badge badge-info">' . __('Masih Dipinjam') . '</span>';
                }

                // ADDED: Format total days column
                $data['total_hari'] = (is_numeric($data['total_hari'])) ? $data['total_hari'] . ' ' . __('hari') : '-';

                $table->appendTableRow(array_values($data));
                $row_class = ($row_num % 2 == 0) ? 'alterCell' : 'alterCell2';
                $table->setCellAttr($row_num, null, 'class="' . $row_class . '"');
                $row_num++;
            }            echo $table->printTable();
            // Pagination at the bottom
            echo '<div class="paging-area no-print"><div class="pt-3 pr-3 text-right" id="pagingBoxBottom">' . simbio_paging::paging($total_recs, $num_recs_show, 10) . '</div></div>';
        } else {
            echo '<div class="infoBox">' . __('Tidak ada data peminjaman yang cocok dengan kriteria pencarian.') . '</div>';
        }        echo '</div>'; // Close sub_section p-3
}
?>

<script>
// Compact JavaScript for SLiMS Native Design
function setQuickDate(period) {
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    
    // Use pre-defined PHP server dates to avoid timezone issues
    switch(period) {
        case 'today':
            startDate.value = '<?php echo date('Y-m-d'); ?>';
            endDate.value = '<?php echo date('Y-m-d'); ?>';
            break;
            
        case 'week':
            // Calculate first day of current week (Monday)
            startDate.value = '<?php echo date('Y-m-d', strtotime('monday this week')); ?>';
            // Calculate last day of current week (Sunday)
            endDate.value = '<?php echo date('Y-m-d', strtotime('sunday this week')); ?>';
            break;
            
        case 'month':
            // First day of current month
            startDate.value = '<?php echo date('Y-m-01'); ?>';
            // Current day
            endDate.value = '<?php echo date('Y-m-d'); ?>';
            break;
            
        case 'year':
            // First day of current year
            startDate.value = '<?php echo date('Y-01-01'); ?>';
            // Current day
            endDate.value = '<?php echo date('Y-m-d'); ?>';
            break;
    }
}

// Basic form validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('searchForm');
    const keywords = document.querySelector('input[name="keywords"]');
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    
    // Log server date for debugging
    console.log('Server date: <?php echo date('Y-m-d'); ?>');
    console.log('Server month start: <?php echo date('Y-m-01'); ?>');
    console.log('Server year start: <?php echo date('Y-01-01'); ?>');
    console.log('Server week start: <?php echo date('Y-m-d', strtotime('monday this week')); ?>');
    
    // Remove validation that requires keywords or dates - show all data by default
    // form.addEventListener('submit', function(e) {
    //     if (!keywords.value.trim() && !startDate.value && !endDate.value) {
    //         e.preventDefault();
    //         alert('<?php echo __("Silakan masukkan kata kunci atau pilih tanggal"); ?>');
    //         keywords.focus();
    //         return false;
    //     }
    // });
    
    // Date validation
    startDate.addEventListener('change', function() {
        if (endDate.value && this.value && new Date(this.value) > new Date(endDate.value)) {
            endDate.value = this.value;
        }
    });
    
    endDate.addEventListener('change', function() {
        if (startDate.value && this.value && new Date(this.value) < new Date(startDate.value)) {
            startDate.value = this.value;
        }
    });
      // Setup print functionality enhancements
    window.addEventListener('beforeprint', function() {
        // Make sure table headers are properly displayed
        const tables = document.querySelectorAll('.s-table');
        tables.forEach(table => {
            // Make sure we have a thead element
            if (!table.querySelector('thead')) {
                const headerRow = table.rows[0];
                if (headerRow) {
                    const thead = document.createElement('thead');
                    thead.appendChild(headerRow.cloneNode(true));
                    table.insertBefore(thead, table.firstChild);
                }
            }
            
            // Ensure each cell has proper width - match the screenshot layout
            if (table.rows.length > 0) {
                // Create a colgroup with specific widths if it doesn't exist
                if (!table.querySelector('colgroup')) {
                    const colgroup = document.createElement('colgroup');
                    const columnWidths = ['25%', '10%', '10%', '20%', '10%', '15%', '10%']; // Width percentages
                    
                    columnWidths.forEach(width => {
                        const col = document.createElement('col');
                        col.style.width = width;
                        colgroup.appendChild(col);
                    });
                    
                    table.insertBefore(colgroup, table.firstChild);
                }
            }
        });
    });
});
</script>