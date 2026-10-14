$(document).ready(function() {
    // Wait for DataTable to be initialized first
    setTimeout(function() {
        initializeFilters();
    }, 500);
});

function initializeFilters() {
    // Check if DataTable is initialized
    if (!$.fn.DataTable.isDataTable('#laporanTable')) {
        console.log('DataTable not initialized yet, retrying...');
        setTimeout(initializeFilters, 500);
        return;
    }

    // Calculate default date range (start of current year to today)
    var startDate = moment().startOf('year');
    var endDate = moment();

    // Initialize date range picker
    $('#filter-date-range').daterangepicker({
        startDate: startDate,
        endDate: endDate,
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear',
            format: 'DD/MM/YYYY'
        }
    });

    // Set initial value
    $('#filter-date-range').val(startDate.format('DD/MM/YYYY') + ' - ' + endDate.format('DD/MM/YYYY'));
    
    // Apply initial filter
    setTimeout(function() {
        applyFilters();
        updateFilterIndicator($('#filter-date-range'));
    }, 100);

    // Apply date range when selected
    $('#filter-date-range').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        applyFilters();
        updateFilterIndicator(this);
    });

    // Clear date range when cancelled
    $('#filter-date-range').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        applyFilters();
        updateFilterIndicator(this);
    });

    // Add change event listeners to all filter dropdowns
    $('#filter-asal, #filter-pelabuhan, #filter-kategori, #filter-status').on('change', function() {
        applyFilters();
        updateFilterIndicator(this);
    });

    // Add real-time search for text inputs
    $('#filter-date-range').on('input', debounce(function() {
        applyFilters();
        updateFilterIndicator(this);
    }, 300));

    // Reset filters button
    $('#reset-filters').on('click', function() {
        // Clear all filter values
        $('#filter-date-range').val('');
        $('#filter-asal, #filter-pelabuhan, #filter-kategori, #filter-status').val('');
        
        // Remove active indicators from all filter elements
        $('.filter-control').removeClass('filter-active');
        $('#filter-date-range, #filter-asal, #filter-pelabuhan, #filter-kategori, #filter-status').removeClass('filter-active');
        $('.filter-badge').remove();
        
        // Remove filter section active class
        $('.card-header').first().removeClass('filter-section-active');
        
        // Remove active filter count badge
        $('#active-filter-count').remove();
        
        // Apply filters first
        applyFilters();
        
        // Use timeout to ensure DOM is updated before counting active filters
        setTimeout(function() {
            updateActiveFilterCount();
        }, 10);
    });

    // Initialize filter count
    updateActiveFilterCount();
}

// Debounce function for real-time filtering
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Function to update visual indicator for active filters
function updateFilterIndicator(element) {
    var $element = $(element);
    var hasValue = $element.val() && $element.val().trim() !== '';
    
    if (hasValue) {
        $element.addClass('filter-active');
        
        // Add badge if not exists
        if ($element.siblings('.filter-badge').length === 0) {
            $element.after('<span class="filter-badge">●</span>');
        }
    } else {
        $element.removeClass('filter-active');
        $element.siblings('.filter-badge').remove();
    }
    
    updateActiveFilterCount();
}

// Function to update active filter count
function updateActiveFilterCount() {
    var activeFilters = $('.filter-active').length;
    var $resetBtn = $('#reset-filters');
    var $filterCard = $('.card-header').first();
    
    // Update reset button text
    if (activeFilters > 0) {
        $resetBtn.html('<i class="fas fa-undo"></i> Reset (' + activeFilters + ')');
        $resetBtn.removeClass('btn-secondary').addClass('btn-warning');
        $filterCard.addClass('filter-section-active');
        
        // Add active filter count to card header if not exists
        if ($('#active-filter-count').length === 0) {
            $filterCard.find('h3').append(' <span id="active-filter-count" class="badge badge-warning">' + activeFilters + ' filter aktif</span>');
        } else {
            $('#active-filter-count').text(activeFilters + ' filter aktif');
        }
        
        // Show filter results info
        showFilterResultsInfo();
    } else {
        $resetBtn.html('<i class="fas fa-undo"></i> Reset');
        $resetBtn.removeClass('btn-warning').addClass('btn-secondary');
        $filterCard.removeClass('filter-section-active');
        $('#active-filter-count').remove();
        hideFilterResultsInfo();
    }
}

// Function to show filter results information
function showFilterResultsInfo() {
    if (!$.fn.DataTable.isDataTable('#laporanTable')) return;
    
    var table = $('#laporanTable').DataTable();
    var info = table.page.info();
    var filteredCount = info.recordsDisplay;
    var totalCount = info.recordsTotal;
    
    var resultInfo = $('#filter-results-info');
    if (resultInfo.length === 0) {
        $('#active-filter-count').after('<span id="filter-results-info" class="badge badge-info ml-1"></span>');
        resultInfo = $('#filter-results-info');
    }
    
    if (filteredCount !== totalCount) {
        resultInfo.text('Menampilkan ' + filteredCount + ' dari ' + totalCount + ' data').show();
    } else {
        resultInfo.hide();
    }
}

// Function to hide filter results information
function hideFilterResultsInfo() {
    $('#filter-results-info').remove();
}

// Function to apply all filters
function applyFilters() {
    // Check if DataTable exists
    if (!$.fn.DataTable.isDataTable('#laporanTable')) {
        console.log('DataTable not initialized yet');
        return;
    }
    
    var table = $('#laporanTable').DataTable();
    
    // Clear existing custom search functions
    $.fn.dataTable.ext.search = [];
    
    // Get filter values
    var dateRange = $('#filter-date-range').val();
    var asal = $('#filter-asal').val();
    var pelabuhan = $('#filter-pelabuhan').val();
    var kategori = $('#filter-kategori').val();
    var status = $('#filter-status').val();
    
    // Apply date range filter (column 1 - Waktu Submit)
    if (dateRange) {
        var dates = dateRange.split(' - ');
        if (dates.length === 2) {
            var startDate = moment(dates[0], 'DD/MM/YYYY');
            var endDate = moment(dates[1], 'DD/MM/YYYY');
            
            // Custom search function for date range
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                if (settings.nTable.id !== 'laporanTable') {
                    return true;
                }
                
                var dateStr = data[1]; // Waktu Submit column (index 1)
                if (!dateStr || dateStr === '-') {
                    return false;
                }
                
                // Extract date part from datetime string
                var datePart = dateStr.split(' ')[0];
                var rowDate = moment(datePart, 'DD/MM/YYYY');
                
                if (!rowDate.isValid()) {
                    return false;
                }
                
                return rowDate.isBetween(startDate, endDate, 'day', '[]');
            });
        }
    }
    
    // Apply category filter (column 7 - Kategori) - maps to "Dalam Negeri"/"Luar Negeri"
    if (asal) {
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            if (settings.nTable.id !== 'laporanTable') {
                return true;
            }
            var kategoriValue = data[7]; // Kategori column (index 7)
            return kategoriValue.indexOf(asal) !== -1;
        });
    }
    
    // Apply destination port filter (column 4 - Ke Pelabuhan)
    if (pelabuhan) {
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            if (settings.nTable.id !== 'laporanTable') {
                return true;
            }
            var pelabuhanValue = data[4]; // Ke Pelabuhan column (index 4)
            return pelabuhanValue.indexOf(pelabuhan) !== -1;
        });
    }
    
    // Apply risk category filter (column 9 - Kresiko)
    if (kategori) {
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            if (settings.nTable.id !== 'laporanTable') {
                return true;
            }
            var kresikoValue = data[9]; // Kresiko column (index 9)
            return kresikoValue.indexOf(kategori) !== -1;
        });
    }
    
    // Apply status filter (column 10 - Status)
    if (status) {
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            if (settings.nTable.id !== 'laporanTable') {
                return true;
            }
            var statusValue = data[10]; // Status column (index 10)
            
            // Handle special case for "Pending" filter which now displays as "Belum Verifikasi"
            if (status === 'pending' || status === 'Pending') {
                return statusValue.indexOf('Belum Verifikasi') !== -1;
            }
            
            return statusValue.indexOf(status) !== -1;
        });
    }
    
    // Redraw table
    table.draw();
    
    // Show filter results info after table is redrawn
    setTimeout(function() {
        showFilterResultsInfo();
    }, 100);
}

    // Custom export functions that respect filters
    window.exportToExcel = function() {
        var table = $('#laporanTable').DataTable();
        var filteredData = table.rows({ search: 'applied' }).data().toArray();
        
        // Create workbook
        var wb = XLSX.utils.book_new();
        
        // Prepare data for export
        var exportData = [];
        
        // Add headers
        var headers = [];
        $('#laporanTable thead th').each(function() {
            headers.push($(this).text().trim());
        });
        exportData.push(headers);
        
        // Add filtered data
        filteredData.forEach(function(row) {
            var rowData = [];
            for (var i = 0; i < row.length; i++) {
                // Clean HTML tags from data
                var cellData = $('<div>').html(row[i]).text();
                rowData.push(cellData);
            }
            exportData.push(rowData);
        });
        
        // Create worksheet
        var ws = XLSX.utils.aoa_to_sheet(exportData);
        
        // Add worksheet to workbook
        XLSX.utils.book_append_sheet(wb, ws, "Laporan Pengawasan Kapal");
        
        // Generate filename with current date
        var today = new Date();
        var filename = "Laporan_Pengawasan_Kapal_" + 
                      today.getFullYear() + 
                      String(today.getMonth() + 1).padStart(2, '0') + 
                      String(today.getDate()).padStart(2, '0') + ".xlsx";
        
        // Save file
        XLSX.writeFile(wb, filename);
    };

    // Override print function to only print filtered data
    window.print = function() {
        var table = $('#laporanTable').DataTable();
        var filteredData = table.rows({ search: 'applied' }).data().toArray();
        
        // Create print window
        var printWindow = window.open('', '_blank');
        var printContent = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Laporan Pengawasan Kapal</title>
                <style>
                    body { font-family: Arial, sans-serif; font-size: 12px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; font-weight: bold; }
                    .header { text-align: center; margin-bottom: 20px; }
                    .badge { padding: 2px 6px; border-radius: 3px; color: white; font-size: 10px; }
                    .badge-success { background-color: #28a745; }
                    .badge-warning { background-color: #ffc107; color: #212529; }
                    .badge-danger { background-color: #dc3545; }
                    @media print {
                        body { margin: 0; }
                        .no-print { display: none; }
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <h2>Laporan Pengawasan Kapal</h2>
                    <p>Tanggal Cetak: ${new Date().toLocaleDateString('id-ID')}</p>
                </div>
                <table>
                    <thead>
                        <tr>`;
        
        // Add headers
        $('#laporanTable thead th').each(function() {
            printContent += `<th>${$(this).text().trim()}</th>`;
        });
        
        printContent += `</tr></thead><tbody>`;
        
        // Add filtered data
        filteredData.forEach(function(row) {
            printContent += '<tr>';
            for (var i = 0; i < row.length; i++) {
                printContent += `<td>${row[i]}</td>`;
            }
            printContent += '</tr>';
        });
        
        printContent += `
                    </tbody>
                </table>
            </body>
            </html>`;
        
        printWindow.document.write(printContent);
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    };

    // GT and PNBP Infographics Functions

    // GT Categories and Tariffs based on the provided table
    const gtCategories = [
        { min: 7, max: 100, label: '7 - 100 GT', tariff: 50000 },
        { min: 101, max: 200, label: '101 - 200 GT', tariff: 60000 },
        { min: 201, max: 350, label: '201 - 350 GT', tariff: 70000 },
        { min: 351, max: 1000, label: '351 - 1.000 GT', tariff: 85000 },
        { min: 1001, max: 2000, label: '1.001 - 2.000 GT', tariff: 120000 },
        { min: 2001, max: 3500, label: '2.001 - 3.500 GT', tariff: 150000 },
        { min: 3501, max: 7000, label: '3.501 - 7.000 GT', tariff: 175000 },
        { min: 7001, max: 10000, label: '7.001 - 10.000 GT', tariff: 200000 },
        { min: 10001, max: 15000, label: '10.001 - 15.000 GT', tariff: 250000 },
        { min: 15001, max: 20000, label: '15.001 - 20.000 GT', tariff: 275000 },
        { min: 20001, max: Infinity, label: '> 20.000 GT', tariff: 300000 }
    ];

    // Function to get GT category for a given GT value
    function getGTCategory(gt) {
        for (let category of gtCategories) {
            if (gt >= category.min && gt <= category.max) {
                return category;
            }
        }
        return gtCategories[gtCategories.length - 1]; // Default to highest category
    }

    // Function to format currency in Rupiah
    function formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(amount);
    }

    // Function to format number with thousand separators
    function formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }

    // Function to update infographics based on filtered data
    function updateInfographics() {
        if (!$.fn.DataTable.isDataTable('#laporanTable')) {
            return;
        }

        const table = $('#laporanTable').DataTable();
        const filteredData = table.rows({ search: 'applied' }).data().toArray();
        
        // Initialize pelabuhan counters
        const pelabuhanStats = {};
        let totalShipsAll = 0;
        let totalShipsPNBP = 0; // Ships that appear in PNBP table (GT > 0)
        let totalGT = 0;
        let totalPNBP = 0;
        let kapalDalamNegeri = 0;
        let kapalLuarNegeri = 0;

        // Process each row of filtered data
        filteredData.forEach((row, index) => {
            // GT is in column index 6 (Gross Tonnage column)
            let gtString = row[6];
            if (gtString === '-' || gtString === '' || gtString === null || gtString === undefined) {
                gtString = '0';
            }
            const gtValue = parseFloat(gtString) || 0;
            
            // Pelabuhan is in column index 4 (Ke Pelabuhan column)
            const pelabuhan = row[4] || 'Tidak Diketahui';
            
            // Kategori is in column index 7 (Kategori column)
            // Extract text from badge HTML if needed
            let kategori = row[7] || '';
            if (kategori.includes('badge')) {
                // Extract text from HTML badge
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = kategori;
                kategori = tempDiv.textContent || tempDiv.innerText || '';
            }
            kategori = kategori.trim();
            
            // Count all ships (regardless of GT value)
            totalShipsAll++;
            
            // Count ships by category
            if (kategori === 'Dalam Negeri') {
                kapalDalamNegeri++;
            } else if (kategori === 'Luar Negeri') {
                kapalLuarNegeri++;
            }
            
            // Only process GT and PNBP calculations if GT > 0
            if (gtValue > 0) {
                // Count ships that will appear in PNBP table
                totalShipsPNBP++;
                
                const category = getGTCategory(gtValue);
                
                // Initialize pelabuhan stats if not exists
                if (!pelabuhanStats[pelabuhan]) {
                    pelabuhanStats[pelabuhan] = {
                        count: 0,
                        totalGT: 0,
                        totalPNBP: 0
                    };
                }
                
                pelabuhanStats[pelabuhan].count++;
                pelabuhanStats[pelabuhan].totalGT += gtValue;
                pelabuhanStats[pelabuhan].totalPNBP += category.tariff;
                
                totalGT += gtValue;
                totalPNBP += category.tariff;
            }
        });

        // Update summary statistics
        $('#total-kapal-gt').text(formatNumber(totalShipsAll));
        $('#kapal-dalam-negeri').text(formatNumber(kapalDalamNegeri));
        $('#kapal-luar-negeri').text(formatNumber(kapalLuarNegeri));

        // Update footer totals (use PNBP ships count for PNBP table)
        $('#total-kapal-footer').text(formatNumber(totalShipsPNBP));
        $('#total-gt-footer').text(formatNumber(totalGT.toFixed(0)));
        $('#total-pnbp-footer').text(formatRupiah(totalPNBP));

        // Update pelabuhan table
        updatePelabuhanTable(pelabuhanStats, totalShipsPNBP, totalGT, totalPNBP);
    }

    // Function to update pelabuhan table
    function updatePelabuhanTable(pelabuhanStats, totalShips, totalGT, totalPNBP) {
        const tbody = $('#pnbpPelabuhanTableBody');
        tbody.empty();

        // Sort pelabuhan by PNBP (descending)
        const sortedPelabuhan = Object.entries(pelabuhanStats)
            .sort((a, b) => b[1].totalPNBP - a[1].totalPNBP);

        sortedPelabuhan.forEach(([pelabuhan, stats]) => {
            if (stats.count > 0) {
                // Calculate percentage
                const percentage = totalPNBP > 0 ? ((stats.totalPNBP / totalPNBP) * 100).toFixed(1) : '0.0';
                
                const row = `
                    <tr>
                        <td>${pelabuhan}</td>
                        <td>${formatNumber(stats.count)}</td>
                        <td>${formatRupiah(stats.totalPNBP)}</td>
                        <td class="text-left">${percentage}%</td>
                    </tr>
                `;
                tbody.append(row);
            }
        });

        // Update footer totals
        $('#total-kapal-footer').text(formatNumber(totalShips));
        $('#total-pnbp-footer').text(formatRupiah(totalPNBP));
    }

    // Function to initialize tables
    function initializeTables() {
        // Initialize empty tables
        updateTables();
    }

    // Function to update tables with filtered data
    function updateTables() {
        if (!$.fn.DataTable.isDataTable('#laporanTable')) {
            return;
        }

        const table = $('#laporanTable').DataTable();
        const filteredData = table.rows({ search: 'applied' }).data().toArray();
        
        // Initialize counters
        const kresikoStats = {
            'RENDAH': 0,
            'SEDANG': 0,
            'TINGGI': 0
        };
        
        const statusStats = {
            'SANDAR': 0,
            'ZONA KARANTINA': 0,
            'Belum Verifikasi': 0
        };

        // Process each row of filtered data
        filteredData.forEach((row) => {
            // Kresiko is in column index 9
            let kresiko = row[9] || '';
            if (kresiko.includes('<span') || kresiko.includes('badge')) {
                // Extract text from HTML badge
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = kresiko;
                kresiko = tempDiv.textContent || tempDiv.innerText || '';
            }
            kresiko = kresiko.trim();
            
            if (kresikoStats.hasOwnProperty(kresiko)) {
                kresikoStats[kresiko]++;
            }

            // Status is in column index 10
            let status = row[10] || '';
            if (status.includes('<span') || status.includes('badge')) {
                // Extract text from HTML badge
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = status;
                // Get only the badge text, ignore additional elements like <small>
                const badgeElement = tempDiv.querySelector('.badge');
                if (badgeElement) {
                    status = badgeElement.textContent || badgeElement.innerText || '';
                } else {
                    // Fallback: get first text content
                    const textNodes = tempDiv.childNodes;
                    for (let i = 0; i < textNodes.length; i++) {
                        if (textNodes[i].nodeType === Node.TEXT_NODE) {
                            status = textNodes[i].textContent.trim();
                            if (status) break;
                        } else if (textNodes[i].tagName === 'SPAN') {
                            status = textNodes[i].textContent || textNodes[i].innerText || '';
                            if (status) break;
                        }
                    }
                }
            }
            status = status.trim();
            
            if (statusStats.hasOwnProperty(status)) {
                statusStats[status]++;
            }
        });

        // Calculate totals
        const totalKresiko = Object.values(kresikoStats).reduce((a, b) => a + b, 0);
        const totalStatus = Object.values(statusStats).reduce((a, b) => a + b, 0);

        // Update Kresiko Table
        const kresikoTableBody = document.querySelector('#kresikoTable tbody');
        if (kresikoTableBody) {
            kresikoTableBody.innerHTML = '';
            
            // Define colors for each kresiko level
            const kresikoColors = {
                'RENDAH': '#28a745',
                'SEDANG': '#ffc107', 
                'TINGGI': '#dc3545'
            };
            
            Object.entries(kresikoStats).forEach(([kresiko, count]) => {
                const percentage = totalKresiko > 0 ? ((count / totalKresiko) * 100).toFixed(1) : '0.0';
                const color = kresikoColors[kresiko] || '#6c757d';
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <span class="badge" style="background-color: ${color}; color: white;">
                            ${kresiko}
                        </span>
                    </td>
                    <td class="text-right font-weight-bold">${count.toLocaleString()}</td>
                    <td class="text-right">${percentage}%</td>
                `;
                kresikoTableBody.appendChild(row);
            });
        }

        // Update Status Table
        const statusTableBody = document.querySelector('#statusTable tbody');
        if (statusTableBody) {
            statusTableBody.innerHTML = '';
            
            // Define colors for each status
            const statusColors = {
                'SANDAR': '#28a745',
                'ZONA KARANTINA': '#dc3545',
                'Belum Verifikasi': '#6c757d'
            };
            
            Object.entries(statusStats).forEach(([status, count]) => {
                const percentage = totalStatus > 0 ? ((count / totalStatus) * 100).toFixed(1) : '0.0';
                const color = statusColors[status] || '#6c757d';
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <span class="badge" style="background-color: ${color}; color: white;">
                            ${status}
                        </span>
                    </td>
                    <td class="text-right font-weight-bold">${count.toLocaleString()}</td>
                    <td class="text-right">${percentage}%</td>
                `;
                statusTableBody.appendChild(row);
            });
        }

        // Update footer totals
        const kresikoFooter = document.getElementById('total-kresiko-footer');
        if (kresikoFooter) {
            kresikoFooter.textContent = totalKresiko.toLocaleString();
        }

        const statusFooter = document.getElementById('total-status-footer');
        if (statusFooter) {
            statusFooter.textContent = totalStatus.toLocaleString();
        }
    }

    // Override the original applyFilters function to include infographics and tables update
    const originalApplyFilters = window.applyFilters || applyFilters;
    window.applyFilters = function() {
        originalApplyFilters();
        // Update infographics and tables after filters are applied
        setTimeout(function() {
            updateInfographics();
            updateTables();
        }, 200);
    };

    // Initialize infographics and tables when document is ready
    $(document).ready(function() {
        // Wait for DataTable to be fully loaded
        setTimeout(function() {
            initializeTables();
            updateInfographics();
            updateTables();
        }, 1000);
    });