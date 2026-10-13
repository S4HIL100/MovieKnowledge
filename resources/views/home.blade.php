<x-layout>
    <style>
        .trending-toggle {
            position: relative;
            display: flex;
            box-sizing: border-box;
            width: 160px;
            height: 30px;
        }

        .trending-toggle-indicator {
            position: absolute;
            inset: 0px;
            width: 41%;
            border-radius: 15px;
            background-color: #b40fd2;
            transition: transform 200ms ease;
        }

        .trending-toggle.is-week .trending-toggle-indicator {
            transform: translateX(79%);
            width: 56%;
        }

        .trending-toggle button {
            z-index: 1;
        }

        .popularContainer{
            margin-left: 15px;
            display: flex;
            overflow: auto;
        }

        .popularContainer img{
            width: 200px;
            height: 300px;
            border-radius: 10px;
            margin-right: 15px;
        }

        .popularContainer .imgContainer{
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

    </style>
    <div style = "display: flex; align-items: center">
        <h1 style = "font-size: 1.5em; margin: 15px 15px">Trending</h1>
        <div class="trending-toggle" style = "border: 1px solid; border-color: #b40fd2; border-radius: 15px; margin-top: 5px">
            <span class="trending-toggle-indicator" aria-hidden="true"></span>
            <button class="button1" style = "background-color: transparent; border: none; color: white; border-radius: 15px; margin-left: 5px">Today</button>
            <button class="button2" style = "background-color: transparent; border: none; color: white; border-radius: 15px; margin-left: 8px">This Week</button>
        </div>
    </div>
    <div class = "popularContainer"></div>
    <script>
        const toggle = document.querySelector(".trending-toggle");
        const todayButton = document.querySelector(".button1");
        const weekButton = document.querySelector(".button2");
        const popularContainer = document.querySelector(".popularContainer");

        todayButton.addEventListener("click", () => {
            toggle.classList.remove("is-week");
            loadTrending("day");
        });

        weekButton.addEventListener("click", () => {
            toggle.classList.add("is-week");
            loadTrending("week");
        });

        const options = {
            method: 'GET',
            headers: {
                accept: 'application/json',
                Authorization: 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiJjZDRmOTczOWFlOTAyM2Q1NzVkZDU2MTAxMzk0ZTlkNyIsIm5iZiI6MTc5MTgwNzgwOC43MTgwMDAyLCJzdWIiOiI2YWNjZDE0MDU0MTI4N2U2MGJiN2MzMmIiLCJzY29wZXMiOlsiYXBpX3JlYWQiXSwidmVyc2lvbiI6MX0.YOSZbtJefOt1jU5sqVYsnsyfiggJVOgmUv_qMU63Rno'
            }
        };

        function loadTrending(choice){
            popularContainer.replaceChildren();

            fetch(`https://api.themoviedb.org/3/trending/all/${choice}?language=en-US`, options)
            .then(response => response.json())
            .then(data => {
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
                    image.src = `https://image.tmdb.org/t/p/w500${element.poster_path}`;
                    image.alt = element.title;

                    div.appendChild(image);
                    div.appendChild(title);
                    div.appendChild(releaseDate);
                    popularContainer.appendChild(div);
                });
            })
            .catch(error => console.log(error));
        }

        loadTrending("day");
    </script>

</x-layout>
