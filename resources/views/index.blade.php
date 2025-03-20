<x-layout>
@vite('resources/css/home.css')
<div class="top-section">
        <p class="txt-1">Sri lanka / <span class="col-blk">Colombo</span></p>
        <h1>Air quality in Colombo</h1>
        <p class="txt-2">Air quality index (AQI) in Colombo 09:42, Mar 14</p>
    </div>

    <div class="mid-section">
        <div class="left-container">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63371.80392298495!2d79.81492019658107!3d6.921922077097836!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae253d10f7a7003%3A0x320b2e4d32d3838d!2sColombo!5e0!3m2!1sen!2slk!4v1742105686915!5m2!1sen!2slk" width="1000" height="550" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <div class="right-container">
            <p class="loc"><i class="fa-solid fa-location-dot"></i> Location</p>
            <p class="txt-1">Sri lanka / Colombo <span class="col-blk">Colombo 07</span></p>
            <hr class="divider">
            <p class="L-p"><span class="live-indicator"></span>Live</p>
        </div>
    </div>
    <div id="chartDiv" style="max-width: 740px;height: 330px;margin: 0px auto"></div>
                

                <div class="populated-section">
                    <p class="title-mini">Most Populated Divisions</p>
                    <p class="txt-1">Colombo 04</p>
                    <p class="txt-1">Colombo 07</p>
                    <p class="txt-1">Colombo 10</p>
                </div>
                    
                </div>
            
        
            <script>
                // Update timestamp dynamically
                function updateTime() {
                    const now = new Date();
                    const timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                    document.querySelector('.txt-2').innerHTML = `Air quality index (AQI) in Colombo ${timeString}, ${now.toLocaleDateString()}`;
                }
                updateTime();
                setInterval(updateTime, 60000); 
              
        
                var chart = JSC.chart('chartDiv', { 
                    debug: false, 
                    legend_visible: false, 
                    defaultTooltip_enabled: false, 
                    xAxis_spacingPercentage: 0.4, 
                    yAxis: [{ 
                        id: 'ax1', 
                        defaultTick: { padding: 10, enabled: false }, 
                        customTicks: [0, 100, 200, 300, 400, 500], 
                        line: { width: 10, color: 'smartPalette:pal1' }, 
                        scale_range: [0, 500] 
                    }], 
                    defaultSeries: { 
                        type: 'gauge column roundcaps', 
                        shape: { 
                            label: { 
                                text: '%max', 
                                align: 'center', 
                                verticalAlign: 'middle', 
                                style_fontSize: 28 
                            } 
                        } 
                    }, 
                    series: [{ 
                        type: 'column roundcaps', 
                        name: 'AQI', 
                        yAxis: 'ax1', 
                        palette: { 
                            id: 'pal1', 
                            pointValue: '%yValue', 
                            ranges: [ 
                                { value: 0, color: '#21D683' },  
                                { value: 100, color: '#77E6B4' },
                                { value: 200, color: '#FFD221' },
                                { value: 300, color: '#FF9B21' },
                                { value: 400, color: '#FF5353' }, 
                                { value: 500, color: '#D60000' }  
                            ] 
                        }, 
                        points: [['AQI', 250]] 
                    }] 
                }); 
              </script>

</x-layout>