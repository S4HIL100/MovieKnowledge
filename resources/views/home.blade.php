<x-layout>
    <div class = "toggleContainer">
        <h1 style = "font-size: 1.5em; margin: 15px 15px">Trending</h1>
        <div class="trending-toggle" style = "border: 1px solid; border-color: #b40fd2; border-radius: 15px; margin-top: 5px">
            <span class="trending-toggle-indicator" aria-hidden="true"></span>
            <button class="button1" style = "background-color: transparent; border: none; color: white; border-radius: 15px; margin-left: 5px">Today</button>
            <button class="button2" style = "background-color: transparent; border: none; color: white; border-radius: 15px; margin-left: 8px; padding-right: 8px;">This Week</button>
        </div>
    </div>
    <div class = "Container" id = "trending"></div>

    <div class = "toggleContainer">
        <h1 style = "font-size: 1.5em; margin: 15px 15px">What's Popular</h1>
        <div class="popular-toggle" style = "border: 1px solid; border-color: #b40fd2; border-radius: 15px; margin-top: 5px">
            <span class="popular-toggle-indicator" aria-hidden="true"></span>
            <button data-option="streaming" style = "background-color: transparent; border: none; color: white; border-radius: 15px; margin-left: 5px">Streaming</button>
            <button data-option="tv" style = "background-color: transparent; border: none; color: white; border-radius: 15px; margin-left: 8px">On TV</button>
            <button data-option="rent" style = "background-color: transparent; border: none; color: white; border-radius: 15px; margin-left: 8px">For Rent</button>
            <button data-option="theatres" style = "background-color: transparent; border: none; color: white; border-radius: 15px; margin-left: 10px">In Theatres</button>
        </div>
    </div>
    <div class = "Container" id = "popular"></div>

    <div class = "toggleContainer">
        <h1 style = "font-size: 1.5em; margin: 15px 15px">Free To Watch</h1>
        <div class="free-toggle" style = "border: 1px solid; border-color: #b40fd2; border-radius: 15px; margin-top: 5px; padding-right: 5px;">
            <span class="free-toggle-indicator" aria-hidden="true"></span>
            <button data-option="movies" style = "background-color: transparent; border: none; color: white; border-radius: 15px; margin-left: 5px">Movies</button>
            <button data-option="tv" style = "background-color: transparent; border: none; color: white; border-radius: 15px; margin-left: 6px">TV</button>
        </div>
    </div>
    <div class = "Container" id = "free"></div>

    <script>
        const todayButton = document.querySelector(".button1");
        const weekButton = document.querySelector(".button2");

        const trendingContainer = document.getElementById("trending");
        const popularContainer = document.getElementById("popular");
        const freeContainer = document.getElementById("free");

        const toggle = document.querySelector(".trending-toggle");
        const popularToggle = document.querySelector(".popular-toggle");
        const freeToggle = document.querySelector(".free-toggle");

        popularToggle.addEventListener("click", event => {
            const button = event.target.closest("button[data-option]");

            if (button) {
                popularToggle.dataset.selected = button.dataset.option;
                if (button.dataset.option == "streaming"){
                    load("https://api.themoviedb.org/3/discover/movie?watch_region=GB&with_watch_monetization_types=flatrate&sort_by=popularity.desc", popularContainer);
                }
                else if (button.dataset.option == "rent"){
                    load("https://api.themoviedb.org/3/discover/movie?watch_region=GB&with_watch_monetization_types=rent&sort_by=popularity.desc&language=en-US", popularContainer);
                }
                else if (button.dataset.option == "tv"){
                    load("https://api.themoviedb.org/3/tv/popular?language=en-US&page=1", popularContainer);
                }
                else{
                    load("https://api.themoviedb.org/3/movie/now_playing?region=GB", popularContainer);
                }
            }
        });

        freeToggle.addEventListener("click", event => {
            const button = event.target.closest("button[data-option]");

            if (button) {
                freeToggle.dataset.selected = button.dataset.option;
                if(button.dataset.option == "movies"){
                    load("https://api.themoviedb.org/3/discover/movie?watch_region=GB&with_watch_monetization_types=free&sort_by=popularity.desc&language=en-US", freeContainer);
                }
                else{
                    load("https://api.themoviedb.org/3/discover/tv?watch_region=GB&with_watch_monetization_types=free&sort_by=popularity.desc&language=en-US", freeContainer);
                }
                
            }
        });

        todayButton.addEventListener("click", () => {
            toggle.classList.remove("is-week");
            load("https://api.themoviedb.org/3/trending/all/day?language=en-US", trendingContainer);
        });

        weekButton.addEventListener("click", () => {
            toggle.classList.add("is-week");
            load("https://api.themoviedb.org/3/trending/all/week?language=en-US", trendingContainer);
        });

        const options = {
            method: 'GET',
            headers: {
                accept: 'application/json',
                Authorization: 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiJjZDRmOTczOWFlOTAyM2Q1NzVkZDU2MTAxMzk0ZTlkNyIsIm5iZiI6MTc5MTgwNzgwOC43MTgwMDAyLCJzdWIiOiI2YWNjZDE0MDU0MTI4N2U2MGJiN2MzMmIiLCJzY29wZXMiOlsiYXBpX3JlYWQiXSwidmVyc2lvbiI6MX0.YOSZbtJefOt1jU5sqVYsnsyfiggJVOgmUv_qMU63Rno'
            }
        };

        function load(link, container){
            container.innerHTML = '<div class="spinner-border loading-spinner" role="status"><span class="visually-hidden">Loading...</span></div>';

            fetch(link, options)
            .then(response => response.json())
            .then(data => {
                container.replaceChildren();
                data.results.forEach(element => {
                    const div = document.createElement("div");
                    const image = document.createElement("img");
                    const title = document.createElement("p");
                    const [year, month, day] = (element.release_date != undefined) ? element.release_date.split("-").map(Number) : element.first_air_date.split("-").map(Number) ;
                    const date = new Date(year, month - 1, day);
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
                    container.appendChild(div);
                });

            })
            .catch(error =>{
                container.replaceChildren();
                console.error(error);
            });
        }

        load("https://api.themoviedb.org/3/trending/all/day?language=en-US", trendingContainer);
        load("https://api.themoviedb.org/3/discover/movie?watch_region=GB&with_watch_monetization_types=flatrate&sort_by=popularity.desc", popularContainer);
        load("https://api.themoviedb.org/3/discover/movie?watch_region=GB&with_watch_monetization_types=free&sort_by=popularity.desc&language=en-US", freeContainer);
    </script>

</x-layout>
