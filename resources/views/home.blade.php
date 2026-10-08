<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MovieKnowledge</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
      .navbar-search .form-control {
        flex: 1;
        min-width: 0;
      }

      .btn{
        margin-left:8px;
      }

      @media (min-width: 992px) {
        .navbar .container-fluid {
          position: relative;
        }

        .navbar-search {
          position: absolute;
          left: 50%;
          transform: translateX(-50%);
          width: min(40vw, 36rem);
        }
      }

      footer p{
        text-align: center;
      }

      @media (max-width: 991px) {
        .navbar-search {
          width: 100%;
          margin-top: 0.5rem;
        }
      }
    </style>
  </head>
  <body class="bs-body-bg min-vh-100 d-flex flex-column text-light" style="background-color: #2b2e31;">
    <nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">MovieKnowledge</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <form class="navbar-search d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"/>
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
            <button type="button" class="btn btn-outline-primary">Sign Up</button>
            <button type="button" class="btn btn-outline-primary">Login</button>
        </div>
    </nav>
    <main class="flex-grow-1"></main>
    <footer class = "bg-dark text-light" data-bs-theme="dark"><p>©2026 Sahil Bhandal<p></footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  </body>
</html>