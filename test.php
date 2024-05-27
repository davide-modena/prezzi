<?php include 'head.php' ?>

<body>
    <div id="map"></div>

    <script>
        var map = L.map('map',{zoomControl: false}).setView([46,11],8);
        L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
            minZoom: 0,
            maxZoom: 20,
            ext: 'png'
        }).addTo(map);

        var markers = L.markerClusterGroup();
        for(let i = 0; i < 10; i++){
            var marker = L.marker([Math.floor(Math.random() * 2 + 46),Math.floor(Math.random() * 3 + 11)]);
            markers.addLayer(marker);
        }

        map.addLayer(markers);
    </script>
</body>