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
                <span class="age-rating" hidden></span>
            </div>
        </div>
    </div>
    <script>
        const id = {{ Illuminate\Support\Js::from($id) }};
        const type = {{ Illuminate\Support\Js::from($type) }};
        const backdrop = document.getElementById("backdrop");
        const loadingSpinner = document.getElementById("media-loading");
        const container = document.getElementById("media-content");
        const poster = document.querySelector(".media-poster");
        const title = document.getElementById("media-title");
        const showYear = document.querySelector(".media-year");
        const ratingBadge = document.querySelector(".age-rating");
        const textColorButton = document.querySelector(".text-color-toggle");
        const options = {
            method: 'GET',
            headers: {
                accept: 'application/json', 
                Authorization: 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiJjZDRmOTczOWFlOTAyM2Q1NzVkZDU2MTAxMzk0ZTlkNyIsIm5iZiI6MTc5MTgwNzgwOC43MTgwMDAyLCJzdWIiOiI2YWNjZDE0MDU0MTI4N2U2MGJiN2MzMmIiLCJzY29wZXMiOlsiYXBpX3JlYWQiXSwidmVyc2lvbiI6MX0.YOSZbtJefOt1jU5sqVYsnsyfiggJVOgmUv_qMU63Rno'
            }
        };

        fetch(`https://api.themoviedb.org/3/${type}/${id}?language=en-US`, options)
        .then(res => res.json())
        .then(data => {
            const releaseDate = data.release_date || data.first_air_date;

            if (data.backdrop_path) {
                backdrop.style.backgroundImage = `url("https://media.themoviedb.org/t/p/w1280${data.backdrop_path}")`;
            }

            if (data.poster_path) {
                poster.src = `https://media.themoviedb.org/t/p/w500${data.poster_path}`;
                poster.alt = data.title || data.name || data.original_title || data.original_name || "";
                poster.hidden = false;
            }

            title.textContent = data.name || data.title || data.original_name || data.original_title;
            if (releaseDate) {
                showYear.textContent = `(${releaseDate.slice(0, 4)})`;
                showYear.hidden = false;
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