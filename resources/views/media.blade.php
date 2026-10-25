<x-layout>
    <div id="backdrop"></div>
    <script>
        const id = {{ Illuminate\Support\Js::from($id) }};
        const type = {{ Illuminate\Support\Js::from($type) }};
        const backdrop = document.getElementById("backdrop");
        backdrop.innerHTML = '<div class="spinner-border loading-spinner" role="status"><span class="visually-hidden">Loading...</span></div>';
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
            backdrop.replaceChildren();
            console.log(data);
            if (data.backdrop_path) {
                backdrop.style.backgroundImage = `url("https://media.themoviedb.org/t/p/w1280${data.backdrop_path}")`;
            };
        })
        .catch(err => console.error(err));
    </script>
</x-layout>