@extends('layout.layout')
<?php 
session_start();

if (isset($_SESSION["data"])){
  $formattedData = $_SESSION["data"];
  $labels = $_SESSION["labels"];
  $totalRuns = $_SESSION["totalRuns"];

  foreach ($totalRuns as $run) {
  foreach($run["tracks"] as $track) {
    if($track->songGenre != 'null'){
      $genres[] = $track->songGenre;
      }
    }
  }

  $genres = array_count_values($genres);
  $genreLabels = array_keys($genres);
  $genreData = array_values($genres);
?> 

<h1> Your runs </h1>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <h3 class="box-title">Hover over icons for run title</h3>
  <div class="chart">
    <canvas id="displayChart" height="100" style="display: block;"></canvas>
  </div>
  <h1> Your most played genres </h1>
  <div class="chart-container" style="position: relative; height:75vh;">
    <canvas id="pie-chart" style="display: block;"></canvas>
  </div>

<script>
var displayChart = document.getElementById('displayChart').getContext('2d');

// Global Options
Chart.defaults.defaultFontFamily = 'Lato';
Chart.defaults.defaultFontSize = 18;
Chart.defaults.color = '#000000';
Chart.defaults.showLine = true;

var jsArray = JSON.parse('<?php echo json_encode($formattedData); ?>');
var myLabel = JSON.parse('<?php echo json_encode($labels); ?>');

var songTempoChart = new Chart(displayChart, {
  type:'scatter', 
  data:{
    labels: "Hover over for run title",
    datasets:[{
      label: myLabel,
      data: jsArray,
      backgroundColor:[
        'rgba(255, 99, 132, 0.6)',
        'rgba(54, 162, 235, 0.6)',
        'rgba(255, 206, 86, 0.6)',
        'rgba(75, 192, 192, 0.6)',
        'rgba(153, 102, 255, 0.6)',
        'rgba(255, 159, 64, 0.6)',
        'rgba(255, 99, 132, 0.6)'
      ],
      borderWidth:1,
      borderColor:'#000000',
      hoverBorderWidth:3,
      hoverBorderColor:'#000',
      pointRadius: 7
    }]
  },
  options:{
    layout:{
      padding:{
        left:10,
        right:10,
        bottom:50,
        top:50
      }
    },
    plugins: {
      title:{
        display:true,
        text: "BPM Analysis",
        fontSize:25,
        responsive: true
      },
      legend:{
        display: false,
        position:'right',
        labels:{
          fontColor:'#000'
        }
      },
      tooltip: {
        display: true,
        callbacks: {
          label: function(tooltipItem, data) {
           var label = songTempoChart.data.datasets[tooltipItem.datasetIndex].label[tooltipItem.dataIndex];
           return label;
          }
        }
      }
    },
    scales: {
      y: {
        display: true,
        reverse: true,
        title: {
          display: true,
          text: 'Pace'
        }
      },
      x: {
        display: true,
        title: {
          display: true,
          text: 'Average song BPM'
        }
      }
    }
  }
});
  </script>

<script>
var genreLabels = JSON.parse('<?php echo json_encode($genreLabels); ?>');
var genreData = JSON.parse('<?php echo json_encode($genreData); ?>');
  // Global Options
  Chart.defaults.color = '#000000';
  
  new Chart(document.getElementById("pie-chart"), {
      type: 'pie',
      data: {
        labels: genreLabels,
        datasets: [{
          label: "Genre",
          backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
          data: genreData
        }]
      },
      options: {
        plugins: {
          legend: {
            display: true,
            position: 'right'
          }
        },
          layout:{
            padding:{
              left:10,
              right:10,
              bottom:50,
              top:50
            }
          },
        responsive: true,
        maintainAspectRatio: false
      }
  });
  </script>

<?php } 
else {
?>
<h1> Please sign into services to see graphs! </h1>

<?php
}
?>