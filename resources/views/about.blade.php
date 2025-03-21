<x-layout>
    <head>
        @vite('resources/css/about.css')

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>About AQI</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" href="About.css">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap');
        </style>
    </head>
    <body>
        <div class="about-container">
            <div class="top-section">
                <h1>About AQI</h1>
            </div>
            <div class="Text-body-container">
                <h2>What is AQI?</h2>
                <P>The Air Quality Index (AQI) is a scale that measures and reports air pollution levels in a simple way. It helps the public understand how clean or polluted the air is and what health effects it may have.
                    The AQI ranges from 0 to 500, with lower values indicating good air quality and higher values representing hazardous pollution levels. The index is based on key pollutants such as:</P>
                    <ul>
                        <li>Particulate Matter (PM2.5 & PM10) – Fine dust and smoke particles</li>
                        <li>Nitrogen Dioxide (NO₂) – From vehicle and industrial emissions</li>
                        <li>Carbon Monoxide (CO) – A gas from burning fuel</li>
                        <li>Ozone (O₃) – A gas formed by chemical reactions in sunlight</li>
                        <li>Sulfur Dioxide (SO₂) – Emitted from burning fossil fuels</li>
                    </ul>  
                <p>Governments and environmental agencies use AQI to inform people about air pollution risks and recommend protective actions, especially for sensitive groups like children, the elderly, and those with respiratory conditions.</p> 
                <hr>
                <h2>AQI Categories & Health Effects</h2>
                <img src="{{ Vite::asset('resources/assets/images/About-img-01.png') }}" alt="">
                <img src="{{ Vite::asset('resources/assets/images/About-img-02.png') }}" alt="">
                <hr>
                <h2>How to Protect Yourself from Air Pollution</h2>
                <p>Tips for staying safe:</p>
                <ul>
                    <li>Check AQI daily before outdoor activities.</li>
                    <li>Wear a mask in high pollution areas.</li>
                    <li>Stay indoors during high pollution days.</li>
                    <li>Use public transport to reduce pollution.</li>
                    <li>Grow plants that help purify air (e.g., Aloe Vera, Snake Plant).</li>
                </ul>
            </div>
        </div>
    </body>
</x-layout>