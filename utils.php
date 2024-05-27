<script>
    async function getLuoghi() {
        return new Promise((resolve, reject) => {
            $.ajax({
                method: "GET",
                url: "api/getLuoghi.php",
                headers: {},
                data: {},
                success: function (data) {
                    resolve(data);
                },
                error: function () {
                    resolve(false);
                }
            });
        });
    }

    async function getLuogoById(placeId) {
        return new Promise((resolve, reject) => {
            $.ajax({
                method: "GET",
                url: `api/getLuogoById.php?placeId=${placeId}`,
                headers: {},
                data: {},
                success: function (data) {
                    resolve(data);
                },
                error: function () {
                    resolve(false);
                }
            });
        });
    }

    async function getPrezzi(placeId) {
        return new Promise((resolve, reject) => {
            $.ajax({
                method: "GET",
                url: `api/getPrezzi.php?placeId=${placeId}`,
                headers: {},
                data: {},
                success: function (data) {
                    resolve(data);
                },
                error: function () {
                    resolve(false);
                }
            });
        });
    }
</script>