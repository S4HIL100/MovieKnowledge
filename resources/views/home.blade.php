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
    </style>
    <div style = "display: flex; align-items: center">
        <h1 style = "font-size: 1.5em; margin: 15px 15px">Trending</h1>
        <div class="trending-toggle" style = "border: 1px solid; border-color: #b40fd2; border-radius: 15px; margin-top: 5px">
            <span class="trending-toggle-indicator" aria-hidden="true"></span>
            <button class="button1" style = "background-color: transparent; border: none; color: white; border-radius: 15px; margin-left: 5px">Today</button>
            <button class="button2" style = "background-color: transparent; border: none; color: white; border-radius: 15px; margin-left: 8px">This Week</button>
        </div>
    </div>
    <script>
        const toggle = document.querySelector(".trending-toggle");
        const todayButton = document.querySelector(".button1");
        const weekButton = document.querySelector(".button2");

        todayButton.addEventListener("click", () => {
            toggle.classList.remove("is-week");
        });

        weekButton.addEventListener("click", () => {
            toggle.classList.add("is-week");
        });
    </script>

</x-layout>
