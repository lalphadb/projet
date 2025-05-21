<div>
    <div id="chart" style="height: 350px;"></div>

    <script>
        document.addEventListener('livewire:load', function () {
            const options = {
                chart: {
                    type: 'bar',
                    height: 350
                },
                series: [{
                    name: 'Membres',
                    data: @json($data)
                }],
                xaxis: {
                    categories: @json($labels)
                },
                colors: ['#3c8dbc'],
                title: {
                    text: 'Membres par école',
                    align: 'left'
                }
            };

            const chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        });
    </script>
</div>
