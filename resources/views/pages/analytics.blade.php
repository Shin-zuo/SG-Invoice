<x-layouts.app title="Analytics">

    <div class="container-fluid px-0">
        
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted text-uppercase fw-bold small mb-1">Total Invoices</p>
                            <h2 class="fw-bold mb-0 text-bright">{{ number_format($totalInvoices) }}</h2>
                        </div>
                        <div class="d-flex align-items-center justify-content-center rounded-circle" 
                             style="width: 50px; height: 50px; background-color: rgba(0, 121, 242, 0.15); color: var(--accent-primary);">
                            <i class="fa-solid fa-file-invoice fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted text-uppercase fw-bold small mb-1">Total Revenue</p>
                            <h2 class="fw-bold mb-0 text-success">₱{{ number_format($grandTotal, 2) }}</h2>
                        </div>
                        <div class="d-flex align-items-center justify-content-center rounded-circle" 
                             style="width: 50px; height: 50px; background-color: rgba(25, 135, 84, 0.15); color: #198754;">
                            <i class="fa-solid fa-coins fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-subtle">
            <div class="card-header bg-panel border-bottom border-subtle py-3 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <h6 class="fw-bold m-0 text-bright">
                    <i class="fa-solid fa-chart-simple me-2 text-accent"></i>Revenue Analytics
                </h6>
                
                <div class="d-flex gap-2">
                    <select id="filterType" class="form-select form-select-sm" style="width: 120px;">
                        <option value="monthly">Monthly</option>
                        <option value="yearly">Yearly</option>
                    </select>

                    <select id="yearSelector" class="form-select form-select-sm" style="width: 100px;">
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="card-body p-4">
                <div style="height: 400px; width: 100%;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            const filterSelect = document.getElementById('filterType');
            const yearSelect = document.getElementById('yearSelector');
            
            let myChart;

            // --- THEME AWARE COLORS ---
            // We read the CSS variables from your app.css so the chart matches the theme
            const style = getComputedStyle(document.body);
            const textMuted = style.getPropertyValue('--text-muted').trim() || '#8b949e';
            const borderSubtle = style.getPropertyValue('--border-subtle').trim() || '#30363d';
            const accentPrimary = style.getPropertyValue('--accent-primary').trim() || '#0079F2';

            function initChart(labels, data) {
                if (myChart) myChart.destroy(); 

                myChart = new Chart(ctx, {
                    type: 'bar', // changed to 'line' or 'bar' as you prefer
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Total Revenue',
                            data: data,
                            backgroundColor: accentPrimary, // Use Replit Blue
                            hoverBackgroundColor: accentPrimary,
                            borderRadius: 4,
                            barPercentage: 0.5,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: style.getPropertyValue('--bg-panel').trim(),
                                titleColor: style.getPropertyValue('--text-bright').trim(),
                                bodyColor: style.getPropertyValue('--text-main').trim(),
                                borderColor: borderSubtle,
                                borderWidth: 1,
                                padding: 10,
                                displayColors: false,
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) { label += ': '; }
                                        if (context.parsed.y !== null) {
                                            label += new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(context.parsed.y);
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: borderSubtle, // Theme border color for grid lines
                                    drawBorder: false,
                                },
                                ticks: {
                                    color: textMuted, // Theme text color for numbers
                                    font: { family: "'Inter', sans-serif", size: 11 },
                                    callback: function(value) {
                                        return '₱' + value.toLocaleString();
                                    }
                                }
                            },
                            x: {
                                grid: { display: false },
                                ticks: {
                                    color: textMuted,
                                    font: { family: "'Inter', sans-serif", size: 12 }
                                }
                            }
                        }
                    }
                });
            }

            function loadChartData() {
                const filter = filterSelect.value;
                const year = yearSelect.value;
                
                // Toggle year selector visibility
                yearSelect.style.display = (filter === 'yearly') ? 'none' : 'block';

                fetch(`/analytics/chart-data?filter=${filter}&year=${year}`)
                    .then(response => response.json())
                    .then(data => {
                        initChart(data.labels, data.totals);
                    })
                    .catch(error => console.error('Error fetching chart data:', error));
            }

            filterSelect.addEventListener('change', loadChartData);
            yearSelect.addEventListener('change', loadChartData);

            loadChartData();
        });
    </script>

</x-layouts.app>