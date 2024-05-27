<?php
    session_start();

    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }
?>

<?php include 'head.php' ?>
<link rel="stylesheet" href="css/add.css">

<body>
    <div class="add-smth-container">
        <h1>Aggiungi luogo</h1>
        <form action="placeToDb.php" method="post" enctype="multipart/form-data">
            <div class="form-element">
                <label for="name">Nome</label>
                <input type="text" name="name" id="name" placeholder="Inserisci il nome del luogo..." required>
            </div>
            <div class="form-element">
                <label for="picture">Foto</label>
                <input type="file" name="picture" id="picture" placeholder="Inserisci l'URL di una foto..." required>
            </div>
            <div class="form-element">
                <label for="location">Posizione <a onclick="locationByCoords()">Ti trovi qui ora?</a></label>
                <input type="text" name="location" id="location" class="ellipsis-text" placeholder="Inserisci un luogo..." oninput="locationAutocomplete()" required>
                <!-- <input type="text" name="location" id="location" placeholder="Inserisci la posizione o incolla l'URL di Google Maps..." onkeyup="locationAutocomplete()" required class="ellipsis-text"> -->
                <i id="positionIcon" class="fa-solid fa-location-dot" onclick="clearInput()"></i>
                <ul id="resultList"></ul>
            </div>
            <div class="form-element">
                <label for="lat">Latitudine</label>
                <input type="text" name="latitude" id="lat" placeholder="Questo campo si completa in automatico" required readonly>
            </div>
            <div class="form-element">
                <label for="lat">Longitudine</label>
                <input type="text" name="longitude" id="lon" placeholder="Questo campo si completa in automatico" required readonly>
            </div>
            <button type="submit">Aggiungi Luogo</button>
        </form>
    </div>

    <?php include 'footer.php'; ?> 

    <script>
        if(<?php echo(!isset($_SESSION["username"]) ? "true" : "false"); ?>){
           // window.location.href="login.php";
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        const input = document.getElementById('location');
        const resultList = document.getElementById('resultList');

        function getCoordinatesFromURL() {
            changeLocationIcon()
            var url = input.value;
            // Verifica se l'URL contiene le stringhe "maps.google" e "place"
            if (url.includes("www.google.it/maps")) {
                var regex = /@(-?\d+\.\d+),(-?\d+\.\d+)/;
                var match = url.match(regex);

                if (match && match.length >= 3) {
                    var latitude = match[1];
                    var longitude = match[2];

                    // Stampa le coordinate
                    console.log("Latitudine:", latitude);
                    console.log("Longitudine:", longitude);

                    document.getElementById('lat').value = latitude;
                    document.getElementById('lon').value = longitude;
                } else {
                    console.error("Coordinate non trovate nella URL.");
                    return null;
                }
            } else {
                if(input.value != ""){
                    console.error("L'URL non è un URL di Google Maps.");
                    alert('URL non valido');
                }
                return null;
            }
        }

        function getCoordinatesFromName(locationName) {
            var url = 'https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(locationName);

            axios.get(url)
                .then(function (response) {
                    var data = response.data;
                    if (data.length > 0) {
                        var firstResult = data[0]; // Prendi solo il primo risultato per semplicità
                        var latitude = firstResult.lat;
                        var longitude = firstResult.lon;
                        console.log('Coordinate trovate:', latitude, longitude);

                        // Ora puoi fare quello che vuoi con le coordinate, come assegnarle agli input del modulo
                        document.getElementById('lat').value = latitude;
                        document.getElementById('lon').value = longitude;
                    } else {
                        console.log('Nessuna corrispondenza trovata per:', locationName);
                    }
                })
                .catch(function (error) {
                    console.error('Errore durante la ricerca delle coordinate:', error);
                });
        }

        function locationAutocomplete() {
            changeLocationIcon();
            var url = 'https://nominatim.openstreetmap.org/search?format=json&q=' + input.value;
                
            axios.get(url)
                .then(function (response) {
                    handleAutocompleteResults(response.data);
                })
                .catch(function (error) {
                    console.error('Error fetching autocomplete results:', error);
                });

            // if (navigator.geolocation) {
            //     navigator.geolocation.getCurrentPosition(function (position) {
            //         var latitude = position.coords.latitude;
            //         var longitude = position.coords.longitude;

            //         var url = `https://nominatim.openstreetmap.org/search?format=json&minlon=${longitude - 1}&minlat=${latitude - 1}&maxlon=${longitude + 1}&maxlat=${latitude + 1}&q=` + input.value;
                    
            //         axios.get(url)
            //             .then(function (response) {
            //                 handleAutocompleteResults(response.data);
            //             })
            //             .catch(function (error) {
            //                 console.error('Error fetching autocomplete results:', error);
            //             });
            //     });
            // } else {
                
            // }
        }

        function handleAutocompleteResults(suggestions) {
            if (resultList.querySelectorAll('li').length > 4) {
                resultList.innerHTML = ''; // Clear previous results
            }

            suggestions.forEach(function (suggestion) {
                var listItem = document.createElement('li');
                listItem.textContent = suggestion.display_name;
                resultList.appendChild(listItem);
            });

            document.querySelectorAll('li').forEach((el) => {
                el.addEventListener('click', () => {
                    input.value = el.textContent;
                    resultList.innerHTML = '';
                    getCoordinatesFromName(input.value);
                });
            });
        }

    function locationByCoords(){
        if(!navigator.geolocation){
            alert('Abilita la geolocalizzazione');
        }
        navigator.geolocation.getCurrentPosition(function(position) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;

            document.getElementById('lat').value = latitude;
            document.getElementById('lon').value = longitude;

            var url = 'https://nominatim.openstreetmap.org/reverse?lat=' + latitude + '&lon=' + longitude + '&format=json';

            axios.get(url)
                .then(function(response) {
                    var place = response.data.display_name;
                    document.getElementById('location').value = place;
                    changeLocationIcon();
                })
                .catch(function(error) {
                    console.error('Error fetching location by coordinates:', error);
                    alert('Per favore, abilita la geolocalizzazione per accedere alla tua posizione.');
                });
        });
    }
    
    function changeLocationIcon(){
        const positionIcon = document.getElementById('positionIcon');
        if(input.value != ""){
            positionIcon.className = 'fa-solid fa-xmark';
        }
        else{
            positionIcon.className = 'fa-solid fa-location-dot';
        }
    }

    function clearInput(){
        input.value = "";
        document.getElementById('lat').value = "";
        document.getElementById('lon').value = "";
        resultList.innerHTML = "";
        changeLocationIcon();
    }
</script>
</body>
</html>
