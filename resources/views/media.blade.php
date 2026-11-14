<x-layout>
    <div id="backdrop">
        <div id="media-loading" class="spinner-border loading-spinner" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div id="media-content" class="media-content" hidden>
            <img class="media-poster" alt="" hidden>
            <div class="media-details">
                <div class="media-title-row">
                    <h1 id="media-title"></h1>
                    <div class="media-title-actions">
                        <span class="media-year" hidden></span>
                        <button class="text-color-toggle" type="button" aria-pressed="false">
                            Make text white
                        </button>
                    </div>
                </div>
                <div class = "media-info">
                    <span class="age-rating" hidden></span>
                    <p class = "date" hidden></p>
                    <span class="production-countries" hidden></span>
                    <div id = "circle1" class = "circle"></div>
                    <span class="media-genres" hidden></span>
                    <div id = "circle2" class = "circle"></div>
                    <span class = "media-runtime" hidden></span>
                </div>
                <div style = "display: flex; align-items: center">
                    <div class="user-score">
                        <div class="progress-ring">
                            <span></span>
                        </div>
                        <p class = "UserScore-title">User Score</p>
                    </div>
                    <form action="/watchlist" method="POST" class="watchlist-form">
                        @csrf
                        <input type="hidden" name="media_id" value="{{ $id }}">
                        <input type="hidden" name="type" value="{{ $type }}">
                        <input type="hidden" name="title" id="watchlist-title">
                        <input type="hidden" name="description" id="watchlist-description">
                        <input type="hidden" name="poster_path" id="watchlist-poster-path">
                        <button
                            class="watchlist"
                            type="submit"
                            title="Add to watchlist"
                            aria-label="Add to watchlist"
                            aria-pressed="false"
                            disabled
                        >+</button>
                        @foreach ($errors->all() as $error)
                            <div class="text-danger" role="alert">{{ $error }}</div>
                        @endforeach
                    </form>
                </div>
                <div class = "media-description">
                    <h4 >Overview</h4>
                    <p class = "overview"></p>
                </div>
            </div>
        </div>
    </div>
    <script>
        const id = {{ Illuminate\Support\Js::from($id) }};
        const type = {{ Illuminate\Support\Js::from($type) }};
        const isInWatchlist = {{ Illuminate\Support\Js::from($isInWatchlist) }};

        const backdrop = document.getElementById("backdrop");
        const loadingSpinner = document.getElementById("media-loading");
        const container = document.getElementById("media-content");
        const poster = document.querySelector(".media-poster");
        const title = document.getElementById("media-title");
        const showYear = document.querySelector(".media-year");
        const showDate = document.querySelector(".date");
        const showProductionCountries = document.querySelector(".production-countries");
        const showGenres = document.querySelector(".media-genres");
        const ratingBadge = document.querySelector(".age-rating");
        const textColorButton = document.querySelector(".text-color-toggle");
        const runtime = document.querySelector(".media-runtime");
        const overview = document.querySelector(".overview");
        const UserScore = document.querySelector(".progress-ring");

        const watchlistTitle = document.getElementById("watchlist-title");
        const watchlistDescription = document.getElementById("watchlist-description");
        const watchlistPosterPath = document.getElementById("watchlist-poster-path");
        const watchlist = document.querySelector(".watchlist");
        const options = {
            method: 'GET',
            headers: {
                accept: 'application/json', 
                Authorization: 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiJjZDRmOTczOWFlOTAyM2Q1NzVkZDU2MTAxMzk0ZTlkNyIsIm5iZiI6MTc5MTgwNzgwOC43MTgwMDAyLCJzdWIiOiI2YWNjZDE0MDU0MTI4N2U2MGJiN2MzMmIiLCJzY29wZXMiOlsiYXBpX3JlYWQiXSwidmVyc2lvbiI6MX0.YOSZbtJefOt1jU5sqVYsnsyfiggJVOgmUv_qMU63Rno'
            }
        };

        watchlist.classList.toggle("is-selected", isInWatchlist);
        watchlist.title = isInWatchlist ? "Added to watchlist" : "Add to watchlist";
        watchlist.setAttribute("aria-label", watchlist.title);
        watchlist.setAttribute("aria-pressed", String(isInWatchlist));

        fetch(`https://api.themoviedb.org/3/${type}/${id}?language=en-US`, options)
        .then(res => res.json())
        .then(data => {
            const releaseDate = data.release_date || data.first_air_date;
            const formatRuntime = (totalMinutes) => {
                const hours = Math.floor(totalMinutes / 60);
                const minutes = totalMinutes % 60;
                const duration = hours > 0 ? `${hours}h ` : "" + minutes > 0 ? `${minutes}m` : "";
                return (duration != "") ? duration : "0m";
            };

            const time = formatRuntime(data.runtime);
            if(time != "0m"){
                runtime.textContent = time;
                runtime.hidden = false;
            }else{
                document.getElementById("circle2").hidden = true;
            }

            if (data.backdrop_path) {
                backdrop.style.backgroundImage = `url("https://media.themoviedb.org/t/p/w1280${data.backdrop_path}")`;
            }

            if (data.poster_path) {
                poster.src = `https://media.themoviedb.org/t/p/w500${data.poster_path}`;
                poster.alt = data.title || data.name || data.original_title || data.original_name || "";
                poster.hidden = false;
            }

            if(data.vote_average){
                UserScore.style.setProperty("--progress", `${Math.ceil(data.vote_average * 10)}%`);
                UserScore.firstElementChild.textContent = `${Math.ceil(data.vote_average * 10)}%`;
            }

            const mediaTitle = data.name || data.title || data.original_name || data.original_title;
            title.textContent = mediaTitle;

            if (mediaTitle) {
                watchlistTitle.value = mediaTitle;
                watchlistDescription.value = data.overview ?? "";
                watchlistPosterPath.value = data.poster_path ?? "";
                watchlist.disabled = false;
            }

            const productionCountries = type === "movie"
                ? data.production_countries?.map(country => country.iso_3166_1) ?? []
                : data.origin_country ?? [];

            if (productionCountries.length > 0) {
                showProductionCountries.textContent = `(${productionCountries.join(", ")})`;
                showProductionCountries.hidden = false;
            }

            const genres = data.genres?.map(genre => genre.name) ?? [];
            if (genres.length > 0) {
                showGenres.textContent = genres.join(", ");
                showGenres.hidden = false;
            }

            if (releaseDate) {
                const [year, month, day] = releaseDate.split("-");
                const paddedYear = year.padStart(4, "0");

                showYear.textContent = `(${paddedYear})`;
                showYear.hidden = false;

                if (month && day) {
                    showDate.textContent = `${day.padStart(2, "0")}/${month.padStart(2, "0")}/${paddedYear}`;
                    showDate.hidden = false;
                }
            }

            if(data.overview){
                overview.textContent = data.overview;
            }

            loadingSpinner.hidden = true;
            container.hidden = false;
        })
        .catch(err => console.error(err));

        textColorButton.addEventListener("click", () => {
            const isWhite = container.classList.toggle("is-white");

            textColorButton.textContent = isWhite ? "Make text black" : "Make text white";
            textColorButton.setAttribute("aria-pressed", String(isWhite));
        });
        
        const ratingUrl = type === "movie" ? `https://api.themoviedb.org/3/movie/${id}/release_dates` : `https://api.themoviedb.org/3/tv/${id}/content_ratings`;

        fetch(ratingUrl, options)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Rating request failed: ${response.status}`);
                }

                return response.json();
            })
            .then(data => {
                let rating;

                if (type === "movie") {
                    const country = data.results.find(result => result.iso_3166_1 === "GB");
                    rating = country?.release_dates.find(item => item.certification.trim())?.certification;
                } else {
                    rating = data.results.find(result => result.iso_3166_1 === "GB")?.rating;
                }

                if (rating) {
                    ratingBadge.textContent = rating;
                    ratingBadge.hidden = false;
                }
            })
            .catch(error => console.error("Could not load age rating:", error));
    </script>
</x-layout>