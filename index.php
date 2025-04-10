<?php
include('api/_config.php');
include('api/_lista-produtos.php');
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="plugins/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="plugins/fontawesome-free-6.6.0-web/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>AGA - Logística</title>
    <style>

    </style>
</head>

<body>
    <div class="ctn-toggle-menu">
        <img src="img/logo.png" alt="" width="75px">
        <button class="mobile-menu-toggle bg-dark d-flex gap-2">
            <span>Menu</span>
            <i class="fas fa-bars"></i>
        </button>
    </div>
    <div class="container-fluid p-0 d-flex">
        <nav class="sidebar bg-light d-flex flex-column flex-shrink-0">
            <div class="d-flex justify-content-center align-items-center p-4">
                <img src="img/logo.png" alt="" width="100%" height="100%">
            </div>
            <div class="px-3">
                <hr class="border border-1 border-dark">
            </div>
            <div class="nav flex-column">
                <a href="#" class="sidebar-link active text-decoration-none p-3">
                    <i class="fas fa-home me-3"></i>
                    <span class="hide-on-collapse">Pedidos</span>
                </a>
                <a href="#" class="sidebar-link text-decoration-none p-3">
                    <i class="fas fa-box me-3"></i>
                    <span class="hide-on-collapse">Produtos</span>
                </a>
                <a href="#" class="sidebar-link text-decoration-none p-3">
                    <i class="fas fa-clipboard-list	 me-3"></i>
                    <span class="hide-on-collapse">Relatórios</span>
                </a>
                <a href="#" class="sidebar-link text-decoration-none p-3">
                    <i class="fas fa-users me-3"></i>
                    <span class="hide-on-collapse">Clientes</span>
                </a>
                <a href="#" class="sidebar-link text-decoration-none p-3">
                    <i class="fa-solid fa-screwdriver-wrench me-3"></i>
                    <span class="hide-on-collapse">Ferramentas</span>
                </a>
                <a href="#" class="sidebar-link text-decoration-none p-3">
                    <i class="fas fa-map me-3"></i>
                    <span class="hide-on-collapse">Mapa</span>
                </a>
            </div>
        </nav>

        <div class="main-content">
            <iframe class="iframe-dashboard" src="/pedidos.php" title="Main Content"></iframe>
        </div>
    </div>
    <script src="./node_modules/jquery/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Defina os links para cada item do menu
            const menuLinks = {
                "Pedidos": "pedidos.php",
                "Produtos": "lista-produtos.php",
                "Relatórios": "relatorio.php",
                "Clientes": "clientes.php",
                "Ferramentas": "ferramentas.php",
                "Mapa": "mapa.php"
            };

            // Toggle mobile menu
            $(".mobile-menu-toggle").on("click", function() {
                $(".sidebar").toggleClass("show");
            });

            // Close sidebar when clicking outside on mobile
            $(document).on("click", function(e) {
                if ($(window).width() <= 768) {
                    if (!$(e.target).closest(".sidebar").length && !$(e.target).closest(".mobile-menu-toggle").length) {
                        $(".sidebar").removeClass("show");
                    }
                }
            });
            $("iframe").on("load", function() {
                $(this).contents().on("click", function() {
                    $(".sidebar").removeClass("show");
                });
            });

            // Manipulador de evento para todos os links da barra lateral
            $(".sidebar-link").on("click", function(e) {
                e.preventDefault();

                // Remove a classe 'active' de todos os links
                $(".sidebar-link").removeClass("active");

                // Adiciona a classe 'active' ao link clicado
                $(this).addClass("active");

                // Obtém o texto do link clicado (Dashboard, Produtos, etc.)
                const menuText = $(this).find(".hide-on-collapse").text();

                // Atualiza o src do iframe com base no texto do menu
                if (menuLinks[menuText]) {
                    $(".main-content iframe").attr("src", menuLinks[menuText]);
                    console.log(menuLinks[menuText])
                }

                // Close sidebar on mobile after clicking a link
                if ($(window).width() <= 768) {
                    $(".sidebar").removeClass("show");
                }
            });

            // Handle window resize
            $(window).on("resize", function() {
                if ($(window).width() > 768) {
                    $(".sidebar").removeClass("show");
                }
            });
        });
    </script>
</body>

</html>