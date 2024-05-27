<?php
    session_start();
?>

<?php include 'head.php'; ?> 

<body>
    <!-- <iframe class="map" src="https://www.openstreetmap.org/export/embed.html?bbox=11.125491857528688%2C46.06391491778846%2C11.132352948188782%2C46.06729078732301&amp;layer=hot"></iframe> -->
    <div id="map"></div>
    <div class="input-search">
        <input type="text" name="search" id="search" placeholder="Cerca per andare in un luogo"
            onkeydown="if(event.key==='Enter') geocode()">
        <i onclick="geocode()" class="fa-solid fa-magnifying-glass"></i>
    </div>
    <div id="place-info"></div>
    <?php include 'footer.php'; ?> 
    <?php include 'utils.php'; ?>

    <script>
        var luoghi = undefined;
        var prezzi = undefined;
        let latitude;
        let longitude;
        
        const map = L.map('map',{zoomControl: false})

        if(navigator.geolocation){
            navigator.geolocation.getCurrentPosition(function(position) {
              latitude = position.coords.latitude;
              longitude = position.coords.longitude;
              map.setView([latitude,longitude], 15);
           	}, showError);
        }
        else{
            showError();
        }
        
        function showError() {
            latitude = 46.0655609;
            longitude = 11.1234326;
            map.setView([latitude,longitude], 15);
        }


        const key = '0QICmVaJxwMJNMlvBVgj';
        // const map = L.map('map',{zoomControl: false}).setView([latitude,longitude], 15);
        const mtLayer = L.maptilerLayer({
            apiKey: key,
            style: "dataviz", //optional
        }).addTo(map);

        // const map = L.map('map', {
        //     zoomControl: false // Rimuovi il controllo di zoom
        // });

        // map.setView([46.0655609, 11.1294326], 16);
        // // Sets initial coordinates and zoom level

        // L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
        //     minZoom: 0,
        //     maxZoom: 20,
        //     ext: 'png'
        // }).addTo(map);

        // L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_satellite/{z}/{x}/{y}{r}.{ext}', {
        //     minZoom: 0,
        //     maxZoom: 20,
        //     ext: 'jpg'
        // }).addTo(map);

        const placeInfo = document.getElementById('place-info');
        // $(document.querySelector('#place-info .fa-xmark')).on('click',function (e){
        //     placeInfo.style.display = 'none';
        // }); 
        
        $(document).ready(function(){
            registerSW();
        })

        async function registerSW(){
            if('serviceWorker' in navigator){
                try{
                    await navigator.serviceWorker.register('./sw.js');
                } catch (e){
                    console.log('SW registration failed');
                }
            }
        }

        $(document).ready(async function () {
            luoghi = await getLuoghi();
            if (luoghi !== false) {
                // alert('sku');
                L.marker([latitude,longitude], { 
                    icon: new L.DivIcon({
                        html: `<div class="your-location"></div>`
                    })
                }).addTo(map);
                luoghi.forEach(function (luogo) {
                    // Creazione di un marker con un popup
                    const marker = L.marker([luogo["Latitudine"], luogo["Longitudine"]], { 
                        icon: new L.DivIcon({
                            html: `<div class="custom-tooltip" style="background-image: url(images/location.svg)"><span>${luogo["Nome"]}</span></div>`
                        })
                    }).addTo(map);
                    // marker.bindTooltip(createTooltipContent(luogo), {
                    //     permanent: true,
                    //     direction: 'top',
                    //     className: 'custom-tooltip',
                    //     opacity: .8,
                    //     offset: [-10, 0]
                    // }).openTooltip();
                    marker.on('click', function (e) {
                        // Apri il popup
                        openPopup(luogo);
                    });
                });
            } else {
                alert("Errore nel caricamento dei luoghi");
            }
        });

        function openPopup(luogo) {
            placeInfo.innerHTML = `
            <i class="fa-solid fa-xmark"></i>
            <div class="image" style='background-image: url(${luogo["Foto"]})'>
                <div class="gradient"></div>
            </div>
            <div class="content">
                <h2>${luogo["Nome"]}</h2>
                <p>Prezzo Medio: ${luogo["PrezzoMedio"]} €</p>
                <button onclick="lookAtPrices(${luogo["Id"]})">Guarda prezzi</button>
            </div>
            `;
            placeInfo.style.display = 'block';
            placeInfo.classList.remove('opened');

            $(document.querySelector('#place-info .fa-xmark')).on('click',function (e){
                if(placeInfo.classList.contains('opened')){
                    openPopup(luogo);
                }
                else{
                    placeInfo.style.display = 'none';
                }                
            });
        }

        async function lookAtPrices(placeId){
            if(!placeInfo.classList.contains('opened')){
            	placeInfo.classList.add('opened');
                luogo = await getLuogoById(placeId);

                luogo = luogo[0];

                let htmlContent = `
                    <i class="fa-solid fa-xmark"></i>
                    <div class="image" style='background-image: url(${luogo["Foto"]})'>
                        <div class="gradient"></div>
                    </div>
                    <div class="content">
                        <h2>${luogo["Nome"]}</h2>
                        <p>Prezzo Medio: ${luogo["PrezzoMedio"]} €</p>
                        <button class="add-price" onClick="addPrice(${luogo["Id"]})"><i class="fa-solid fa-plus"></i></button>
                        <div class="prices">
                `;

                prezzi = await getPrezzi(placeId);
                if (prezzi !== false) {
                    prezzi.forEach(function (prezzo) {
                        htmlContent += `
                            <div class="price-element">
                                <div class="picture" style='background-image: url(${prezzo["Foto"]})'></div>
                                <div class="text">
                                    <h2>${prezzo["Nome"]}</h2>
                                    <p>${prezzo["Dettagli"]}</p>
                                </div>
                                <div class="price centered">
                                    <span>${prezzo["Costo"]} <span class="small">€</span></span>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    alert("Errore nel caricamento dei prezzi");
                }

                htmlContent += `</div></div>`;
                placeInfo.innerHTML = htmlContent;

                $(document.querySelector('#place-info .fa-xmark')).on('click',function (e){
                    if(placeInfo.classList.contains('opened')){
                        openPopup(luogo);
                    }
                    else{
                        placeInfo.style.display = 'none';
                    }                
                });
            }
        }

        function addPrice(placeId) {
            // Dati da inviare
            var data = {
                placeId: placeId
            };

            // Creazione di un elemento di form nascosto
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = 'addprice.php'; // URL del tuo script PHP

            // Creazione di un campo nascosto per ogni coppia chiave-valore dei dati
            Object.keys(data).forEach(function(key) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = data[key];
                form.appendChild(input);
            });

            // Aggiunta del form alla pagina e invio dei dati
            document.body.appendChild(form);
            form.submit();
        }

        // Funzione per generare coordinate casuali all'interno di un intervallo
        function getRandomCoordinate(min, max) {
            return Math.random() * (max - min) + min;
        }

        function geocode() {
            const location = document.getElementById('search').value;

            // Effettua una richiesta di geocodifica a Nominatim
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${location}`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.length > 0) {
                        const lat = data[0].lat;
                        const lon = data[0].lon;

                        // Aggiorna la mappa con le nuove coordinate
                        map.setView([lat, lon], 17);
                    } else {
                        alert("Luogo non trovato");
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert("Si è verificato un errore durante la richiesta di geocodifica.");
                });
        }
        // function createTooltipContent(luogo) {
        //     return `
        //         <div class="custom-tooltip">
        //             <h3>${luogo["Nome"]}</h3>
        //         </div>
        //     `;
        // }        
    </script>
    <!-- <script>!function(d,l,e,s,c){e=d.createElement("script");e.src="//ad.altervista.org/js.ad/size=300X250/?ref="+encodeURIComponent(l.hostname+l.pathname)+"&r="+Date.now();s=d.scripts;c=d.currentScript||s[s.length-1];c.parentNode.insertBefore(e,c)}(document,location)</script> -->
</body>