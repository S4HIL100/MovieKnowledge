<x-layout>
    <div class = "toggleContainer">
        <h1 class = "homeHeader">Trending</h1>
        <div class="toggle" data-toggle="trending">
            <span aria-hidden="true"></span>
            <button data-option = "day" class = "homeButton" style = "margin-left: 5px">Today</button>
            <button data-option = "week" class = "homeButton" style = "margin-left: 8px; padding-right: 8px">This Week</button>
        </div>
    </div>
    <div class = "Container" id = "trending"></div>

    <div class = "toggleContainer">
        <h1 class = "homeHeader">What's Popular</h1>
        <div class="toggle" data-toggle="popular">
            <span aria-hidden="true"></span>
            <button data-option="streaming" class = "homeButton" style = "margin-left: 5px">Streaming</button>
            <button data-option="tv" class = "homeButton" style = "margin-left: 8px">On TV</button>
            <button data-option="rent" class = "homeButton" style = "margin-left: 8px">For Rent</button>
            <button data-option="theatres" class = "homeButton" style = "margin-left: 10px">In Theatres</button>
        </div>
    </div>
    <div class = "Container" id = "popular"></div>

    <div class = "toggleContainer">
        <h1 class = "homeHeader">Free To Watch</h1>
        <div class="toggle" data-toggle="free" style = "padding-right: 5px;">
            <span aria-hidden="true"></span>
            <button data-option="movies" class = "homeButton" style = "margin-left: 5px">Movies</button>
            <button data-option="tv" class = "homeButton" style = "margin-left: 6px">TV</button>
        </div>
    </div>
    <div class = "Container" id = "free"></div>

    <script>
        const trendingContainer = document.getElementById("trending");
        const popularContainer = document.getElementById("popular");
        const freeContainer = document.getElementById("free");

        const trendingToggle = document.querySelector('[data-toggle="trending"]');
        const popularToggle = document.querySelector('[data-toggle="popular"]');
        const freeToggle = document.querySelector('[data-toggle="free"]');

        popularToggle.addEventListener("click", event => {
            const button = event.target.closest("button[data-option]");

            if (button) {
                popularToggle.dataset.selected = button.dataset.option;
                if (button.dataset.option == "streaming"){
                    load("https://api.themoviedb.org/3/discover/movie?watch_region=GB&with_watch_monetization_types=flatrate&sort_by=popularity.desc", popularContainer, "movie");
                }
                else if (button.dataset.option == "rent"){
                    load("https://api.themoviedb.org/3/discover/movie?watch_region=GB&with_watch_monetization_types=rent&sort_by=popularity.desc&language=en-US", popularContainer, "movie");
                }
                else if (button.dataset.option == "tv"){
                    load("https://api.themoviedb.org/3/tv/popular?language=en-US&page=1", popularContainer, "tv");
                }
                else{
                    load("https://api.themoviedb.org/3/movie/now_playing?region=GB", popularContainer, "movie");
                }
            }
        });

        freeToggle.addEventListener("click", event => {
            const button = event.target.closest("button[data-option]");

            if (button) {
                freeToggle.dataset.selected = button.dataset.option;
                if(button.dataset.option == "movies"){
                    load("https://api.themoviedb.org/3/discover/movie?watch_region=GB&with_watch_monetization_types=free&sort_by=popularity.desc&language=en-US", freeContainer, "movie");
                }
                else{
                    load("https://api.themoviedb.org/3/discover/tv?watch_region=GB&with_watch_monetization_types=free&sort_by=popularity.desc&language=en-US", freeContainer, "tv");
                }
                
            }
        });

        trendingToggle.addEventListener("click", () => {
            const button = event.target.closest("button[data-option]");

            if (button){
                trendingToggle.dataset.selected = button.dataset.option;
                if(button.dataset.option == "week"){
                    load("https://api.themoviedb.org/3/trending/all/week?language=en-US", trendingContainer);
                }
                else{
                    load("https://api.themoviedb.org/3/trending/all/day?language=en-US", trendingContainer);
                }
            }
        });

        const options = {
            method: 'GET',
            headers: {
                accept: 'application/json',
                Authorization: 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiJjZDRmOTczOWFlOTAyM2Q1NzVkZDU2MTAxMzk0ZTlkNyIsIm5iZiI6MTc5MTgwNzgwOC43MTgwMDAyLCJzdWIiOiI2YWNjZDE0MDU0MTI4N2U2MGJiN2MzMmIiLCJzY29wZXMiOlsiYXBpX3JlYWQiXSwidmVyc2lvbiI6MX0.YOSZbtJefOt1jU5sqVYsnsyfiggJVOgmUv_qMU63Rno'
            }
        };

        function load(link, container, type){
            container.innerHTML = '<div class="spinner-border loading-spinner" role="status"><span class="visually-hidden">Loading...</span></div>';
            fetch(link, options)
            .then(response => response.json())
            .then(data => {
                container.replaceChildren();
                data.results.filter(element => element.media_type !== "person").forEach(element => {
                    const div = document.createElement("div");
                    const image = document.createElement("img");
                    const title = document.createElement("p");
                    const [year, month, day] = (element.release_date != undefined) ? element.release_date.split("-").map(Number) : element.first_air_date.split("-").map(Number);
                    const date = new Date(year, month - 1, day);
                    const mediaType = type || element.media_type;
                    const formattedDate = new Intl.DateTimeFormat("en-GB", {
                        day: "numeric",
                        month: "long",
                        year: "numeric"
                    }).format(date);

                    const releaseDate = document.createElement("p");
                    releaseDate.textContent = formattedDate;

                    title.innerText = (element.title != undefined) ? element.title : element.name;
                    div.classList.add("imgContainer");
                    image.src = `https://media.themoviedb.org/t/p/w500${element.poster_path}`;
                    image.alt = (element.title != undefined) ? element.title : element.name;

                    div.appendChild(image);
                    div.appendChild(title);
                    div.appendChild(releaseDate);

                    div.onclick = () => {
                        window.location.href = `/media/${mediaType}/${element.id}`;                    
                    }

                    container.appendChild(div);
                });

            })
            .catch(error =>{
                container.replaceChildren();
                console.error(error);
            });
        }

        load("https://api.themoviedb.org/3/trending/all/day?language=en-US", trendingContainer);
        load("https://api.themoviedb.org/3/discover/movie?watch_region=GB&with_watch_monetization_types=flatrate&sort_by=popularity.desc", popularContainer, "movie");
        load("https://api.themoviedb.org/3/discover/movie?watch_region=GB&with_watch_monetization_types=free&sort_by=popularity.desc&language=en-US", freeContainer, "movie");
    </script>

</x-layout>
