@include('components.private.header')
@include('components.private.sidebar')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



<section class="home-section" style="margin-bottom: 500px">
    <div class="home-content">
        <i class='bx bx-menu'></i>
        <span class="text">Statistiques des Enseignants</span>
</div>
<div class="mycontent">
    <div class="container mt-5">

        <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Nombre total d’enseignants : <span class="badge bg-primary">{{ $totalEmployees }}</span>
            </li>
           
          
           
            
            @foreach ($ageDistribution as $range => $count)
                <span class="list-group-item list-group-item-action list-group-item-dark">
                    Âge {{ $range }} : <span class="badge bg-dark">{{ $count }}</span>
                </span>
            @endforeach
            @foreach ($positionCounts as $position => $count)
                <span class="list-group-item list-group-item-action" style="background-color: #7e50d3; color: white;">
                    {{ $position }} : <span class="badge" style="background-color: #9966cc;">{{ $count }}</span>
                </span>
            @endforeach
        </ul>
        <br><br>

        <div class="accordion" id="nationalityAccordion">
            <div class="accordion-item">
              
                  
                </div>
            </div>

            <div class="container-fluid"> <!-- Ensure there's span container wrapping your rows -->
                <div class="row flex-wrap">
                   
                    <div class=" col-md-5 bg-white shadow rounded my-2 mx-1">
                        <canvas id="ageDistributionChart" width="400" height="400"></canvas>
                    </div>
                    <div class=" col-md-5 bg-white shadow rounded my-2 mx-1">
                        <canvas id="positionDistributionChart" width="400" height="400"></canvas>
                    </div>
                    <div style="visibility: hidden" class=" col-md-5 bg-white shadow rounded my-2 mx-1">
                        <canvas id="salaryDistributionChart" width="400" height="400"></canvas>
                    </div>
                   
                </div>
            </div>


        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var genderData = @json($genderDistribution);
        var ageData = @json($ageDistribution);
        var salaryData = @json([$averageSalary, $maxSalary, $minSalary]);
        var positionData = @json($positionDistribution);
        var countryData = @json($nationalityDistribution);

        // Gender Distribution Pie Chart
        new Chart(document.getElementById('genderDistributionChart'), {
            type: 'pie',
            data: {
                labels: Object.keys(genderData),
                datasets: [{
                    label: 'Gender Distribution',
                    data: Object.values(genderData),
                    backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                    ],
                    borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            }
        });

        // Age Distribution Bar Chart
        new Chart(document.getElementById('ageDistributionChart'), {
            type: 'bar',
            data: {
                labels: Object.keys(ageData),
                datasets: [{
                    label: 'Age Distribution',
                    data: Object.values(ageData),
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Salary Distribution Line Chart
        new Chart(document.getElementById('salaryDistributionChart'), {
            type: 'line',
            data: {
                labels: ['Average Salary', 'Max Salary', 'Min Salary'],
                datasets: [{
                    label: 'Salary Statistics',
                    data: salaryData,
                    fill: false,
                    borderColor: 'rgba(255, 159, 64, 1)',
                    tension: 0.1
                }]
            }
        });

        // Position Distribution Doughnut Chart
        new Chart(document.getElementById('positionDistributionChart'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(positionData),
                datasets: [{
                    label: 'Position Distribution',
                    data: Object.values(positionData),
                    backgroundColor: [
                        'rgba(255, 205, 86, 0.2)', 'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)', 'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 205, 86, 1)', 'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)', 'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            }
        });

        // Country Distribution Doughnut Chart
        new Chart(document.getElementById('countryDistributionChart'), {
            type: 'bar',
            data: {
                labels: Object.keys(countryData),
                datasets: [{
                    label: 'Country Distribution',
                    data: Object.values(countryData),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)', 'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)', 'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false // This will hide the legend
                    }
                }
            }
        });;
    });
</script>

@include('components.private.footer')
